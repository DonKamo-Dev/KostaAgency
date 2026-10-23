<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Obtiene el valor de una configuración con fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('settings')) {
                return $default;
            }

            return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
                $setting = self::where('key', $key)->first();

                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Guarda o actualiza una configuración.
     */
    public static function set(string $key, mixed $value, string $group = 'automations'): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            self::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value, 'group' => $group]
            );

            Cache::forget("setting:{$key}");
        } catch (\Throwable $e) {
            // Silently handle if table is unavailable
        }
    }

    /**
     * Obtiene todas las configuraciones de un grupo como un array clave => valor.
     */
    public static function allGroup(string $group): array
    {
        try {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            return self::where('group', $group)->pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
