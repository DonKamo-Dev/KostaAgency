<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ImportRenderData extends Command
{
    protected $signature = 'kosta:import-data {file? : Ruta al archivo JSON} {--url= : URL remota para descargar el dump directamente}';
    protected $description = 'Importa y sincroniza todos los registros desde un volcado JSON de producción';

    public function handle(): int
    {
        $url = $this->option('url');
        $filePath = $this->argument('file');
        $payload = null;

        if ($url) {
            $this->info("Descargando volcado desde: {$url}...");
            $response = Http::timeout(120)->get($url);
            if (! $response->successful()) {
                $this->error("Error descargando datos: HTTP {$response->status()}");
                return 1;
            }
            $payload = $response->json();
        } elseif ($filePath && file_exists($filePath)) {
            $this->info("Leyendo volcado desde: {$filePath}...");
            $payload = json_decode(file_get_contents($filePath), true);
        } else {
            $defaultFile = database_path('seeders/data/render_export.json');
            if (file_exists($defaultFile)) {
                $this->info("Cargando archivo predeterminado: {$defaultFile}...");
                $payload = json_decode(file_get_contents($defaultFile), true);
            } else {
                $this->error("No se encontró ningún archivo ni URL.");
                return 1;
            }
        }

        if (! is_array($payload)) {
            $this->error("El contenido del volcado no es un JSON válido.");
            return 1;
        }

        $data = $payload['data'] ?? $payload;
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        }

        $tableOrder = [
            'users',
            'clients',
            'services',
            'documents',
            'document_items',
            'payments',
            'expenses',
            'leads',
            'case_studies',
        ];

        foreach ($tableOrder as $table) {
            if (! isset($data[$table]) || ! Schema::hasTable($table)) {
                continue;
            }

            $rows = $data[$table];
            $count = count($rows);
            $this->line("Restaurando tabla <comment>{$table}</comment> ({$count} registros)...");

            $columns = Schema::getColumnListing($table);
            $columnsFlip = array_flip($columns);

            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $filteredRow = array_intersect_key($rowArray, $columnsFlip);

                if (! empty($filteredRow['id'])) {
                    DB::table($table)->updateOrInsert(
                        ['id' => $filteredRow['id']],
                        $filteredRow
                    );
                } else {
                    DB::table($table)->insert($filteredRow);
                }
            }
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }

        $this->info("¡Todos los datos han sido sincronizados y restaurados con éxito!");
        return 0;
    }
}
