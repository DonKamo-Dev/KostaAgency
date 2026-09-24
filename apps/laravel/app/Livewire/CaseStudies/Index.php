<?php

namespace App\Livewire\CaseStudies;

use App\Models\CaseStudy;
use App\Support\CaseStudyImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    #[Url(as: 'cat')]
    public string $categoriaFiltro = '';

    public array $selectedIds = [];

    public bool $selectAll = false;

    public bool $showForm = false;

    #[Locked]
    public ?int $editingId = null;

    // Current image path when editing (read-only, used in view)
    public ?string $imagenActual = null;

    // Form fields
    public string $titulo = '';

    public string $descripcion = '';

    public string $url_demo = '';

    public string $categoria = 'web';

    public ?string $source_type = null;

    public ?int $client_id = null;

    public ?int $linked_case_study_id = null;

    public string $metrica_valor = '';

    public string $metrica_label = '';

    public string $tags_input = '';

    public string $gradient_inicio = '#6366f1';

    public string $gradient_fin = '#8b5cf6';

    public $imagen_nueva = null;

    public bool $activo = true;

    public int $orden = 0;

    protected array $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:1000',
        'url_demo' => 'nullable|string|max:255',
        'categoria' => 'required|in:web,ecommerce,sistema,branding,films,social',
        'source_type' => 'nullable|in:client,website',
        'client_id' => 'nullable|integer|exists:clients,id',
        'linked_case_study_id' => 'nullable|integer|exists:case_studies,id',
        'metrica_valor' => 'nullable|string|max:50',
        'metrica_label' => 'nullable|string|max:100',
        'tags_input' => 'nullable|string|max:500',
        'gradient_inicio' => 'required|string|max:50',
        'gradient_fin' => 'required|string|max:50',
        'imagen_nueva' => 'nullable|image|max:2048',
        'activo' => 'boolean',
        'orden' => 'integer|min:0|max:999',
    ];

    protected array $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'categoria.required' => 'Selecciona una categoría.',
        'categoria.in' => 'Categoría no válida.',
        'imagen_nueva.image' => 'El archivo debe ser una imagen (JPG, PNG, WebP).',
        'imagen_nueva.max' => 'La imagen no puede superar 2 MB.',
    ];

    public function render()
    {
        \App\Support\CaseStudySource::ensureSchema();

        $studies = $this->filteredStudiesQuery()
            ->orderBy('orden')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.case-studies.index', ['studies' => $studies]);
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->titulo = '';
        $this->descripcion = '';
        $this->url_demo = '';
        $this->categoria = 'web';
        $this->metrica_valor = '';
        $this->metrica_label = '';
        $this->tags_input = '';
        $this->gradient_inicio = '#6366f1';
        $this->gradient_fin = '#8b5cf6';
        $this->imagen_nueva = null;
        $this->imagenActual = null;
        $this->activo = true;
        $this->orden = 0;
        $this->resetErrorBag();
    }

    public function edit(int $id): void
    {
        $study = $this->findStudy($id);
        $this->editingId = $id;
        $this->titulo = $study->titulo;
        $this->descripcion = $study->descripcion ?? '';
        $this->url_demo = $study->url_demo ?? '';
        $this->categoria = $study->categoria;
        $this->metrica_valor = $study->metrica_valor ?? '';
        $this->metrica_label = $study->metrica_label ?? '';
        $this->tags_input = implode(', ', $study->tags ?? []);
        $this->gradient_inicio = $study->gradient_inicio;
        $this->gradient_fin = $study->gradient_fin;
        $this->activo = $study->activo;
        $this->orden = $study->orden;
        $this->imagen_nueva = null;
        $this->imagenActual = $study->image_url;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $study = $this->editingId ? $this->findStudy($this->editingId) : null;

        $tags = array_values(array_filter(
            array_map('trim', explode(',', $this->tags_input))
        ));

        $data = [
            'titulo' => trim($this->titulo),
            'descripcion' => trim($this->descripcion) ?: null,
            'url_demo' => trim($this->url_demo) ?: null,
            'categoria' => $this->categoria,
            'metrica_valor' => trim($this->metrica_valor) ?: null,
            'metrica_label' => trim($this->metrica_label) ?: null,
            'tags' => $tags ?: null,
            'gradient_inicio' => $this->gradient_inicio,
            'gradient_fin' => $this->gradient_fin,
            'activo' => $this->activo,
            'orden' => $this->orden,
        ];

        if ($this->imagen_nueva) {
            if ($study?->imagen) {
                Storage::disk('public')->delete($study->imagen);
            }
            $data = array_merge($data, CaseStudyImage::attributesFrom($this->imagen_nueva));
        }

        if ($study) {
            $study->update($data);
            $msg = 'Caso de estudio actualizado';
        } else {
            CaseStudy::create($data);
            $msg = 'Caso de estudio creado';
        }

        $this->dispatch('notify', message: $msg);
        $this->closeForm();
    }

    public function toggleActivo(int $id): void
    {
        $study = $this->findStudy($id);
        $study->update(['activo' => ! $study->activo]);
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->filteredStudiesQuery()
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function updatedCategoriaFiltro(): void
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) {
            return;
        }

        $studies = $this->studiesQuery()
            ->whereIn('id', $this->selectedIds)
            ->get();
        $count = $studies->count();

        foreach ($studies as $study) {
            if ($study->imagen) {
                Storage::disk('public')->delete($study->imagen);
            }
            $study->delete();
        }

        $this->selectedIds = [];
        $this->selectAll = false;
        $this->dispatch('notify', message: "Se eliminaron {$count} caso(s) de estudio correctamente.");
    }

    public function bulkSetVisibility(bool $activo): void
    {
        if (empty($this->selectedIds)) {
            return;
        }

        $count = $this->studiesQuery()
            ->whereIn('id', $this->selectedIds)
            ->update(['activo' => $activo]);

        $statusText = $activo ? 'visibles' : 'ocultos';
        $this->dispatch('notify', message: "Se marcaron {$count} caso(s) como {$statusText}.");
    }

    public function delete(int $id): void
    {
        $study = $this->findStudy($id);
        if ($study->imagen) {
            Storage::disk('public')->delete($study->imagen);
        }
        $study->delete();
        $this->selectedIds = array_values(array_diff($this->selectedIds, [(string) $id]));
        $this->dispatch('notify', message: 'Caso eliminado');
    }

    private function studiesQuery(): Builder
    {
        return CaseStudy::query()->forDisplay()->with(['client', 'linkedCaseStudy']);
    }

    private function filteredStudiesQuery(): Builder
    {
        return $this->studiesQuery()
            ->when($this->search, fn (Builder $query) => $query->where(
                fn (Builder $searchQuery) => $searchQuery
                    ->where('titulo', 'like', "%{$this->search}%")
                    ->orWhere('categoria', 'like', "%{$this->search}%")
            ))
            ->when(
                $this->categoriaFiltro,
                fn (Builder $query) => in_array($this->categoriaFiltro, ['films', 'social'], true)
                    ? $query->whereIn('categoria', ['films', 'social'])
                    : $query->where('categoria', $this->categoriaFiltro)
            );
    }

    private function findStudy(int $id): CaseStudy
    {
        return $this->studiesQuery()->findOrFail($id);
    }
}
