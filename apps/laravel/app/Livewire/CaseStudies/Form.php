<?php

namespace App\Livewire\CaseStudies;

use App\Models\CaseStudy;
use App\Models\Client;
use App\Support\CaseStudyImage;
use App\Support\CaseStudySource;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?int $editingId = null;

    public ?string $imagenActual = null;

    public string $titulo = '';

    public string $descripcion = '';

    public string $url_demo = '';

    public string $categoria = 'web';

    public ?string $source_type = null;

    public $client_id = null;

    public $linked_case_study_id = null;

    public string $video_url = '';

    public string $video_orientation = 'vertical';

    public string $video_duration = '';

    public string $metrica_valor = '';

    public string $metrica_label = '';

    public string $tags_input = '';

    public string $gradient_inicio = '#6366f1';

    public string $gradient_fin = '#8b5cf6';

    public $imagen_nueva = null;

    public string $imagen_url = '';

    public bool $quitar_imagen = false;

    public bool $activo = true;

    public bool $proyecto_usa = false;

    public int $orden = 0;

    protected array $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:1000',
        'url_demo' => 'nullable|string|max:255',
        'categoria' => 'required|in:web,ecommerce,sistema,branding,films,social',
        'source_type' => 'nullable|in:client,website',
        'client_id' => 'nullable|integer|exists:clients,id',
        'linked_case_study_id' => 'nullable|integer|exists:case_studies,id',
        'video_url' => 'nullable|string|max:1000',
        'video_orientation' => 'nullable|in:vertical,horizontal',
        'video_duration' => 'nullable|string|max:50',
        'metrica_valor' => 'nullable|string|max:50',
        'metrica_label' => 'nullable|string|max:100',
        'tags_input' => 'nullable|string|max:500',
        'gradient_inicio' => 'required|string|max:50',
        'gradient_fin' => 'required|string|max:50',
        'imagen_url' => 'nullable|url|max:2000',
        'imagen_nueva' => 'nullable|image|max:10240',
        'activo' => 'boolean',
        'proyecto_usa' => 'boolean',
        'orden' => 'integer|min:0|max:999',
    ];

    protected array $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'categoria.required' => 'Selecciona una categoría.',
        'categoria.in' => 'Categoría no válida.',
        'source_type.in' => 'El tipo de anclaje debe ser Empresa o Sitio Web.',
        'client_id.exists' => 'La empresa seleccionada no es válida.',
        'linked_case_study_id.exists' => 'El sitio web seleccionado no es válido.',
        'imagen_url.url' => 'La URL de la imagen debe ser un enlace válido (ej. https://...).',
        'imagen_nueva.image' => 'El archivo debe ser una imagen válida (JPG, PNG, WebP).',
        'imagen_nueva.max' => 'La imagen no puede superar los 10 MB.',
        'imagen_nueva.uploaded' => 'No se pudo subir la imagen. Comprueba que no supere los 10 MB y sea un formato válido (JPG, PNG, WebP), o ingresa un enlace directo en el campo de URL.',
    ];

    public function updatedClientId($value): void
    {
        $this->client_id = filled($value) ? (int) $value : null;
    }

    public function updatedLinkedCaseStudyId($value): void
    {
        $this->linked_case_study_id = filled($value) ? (int) $value : null;
    }

    public function setSourceType(?string $type): void
    {
        if ($this->source_type === $type || empty($type)) {
            $this->source_type = null;
            $this->client_id = null;
            $this->linked_case_study_id = null;
        } else {
            $this->source_type = $type;
            if ($type === 'client') {
                $this->linked_case_study_id = null;
            } elseif ($type === 'website') {
                $this->client_id = null;
            } else {
                $this->client_id = null;
                $this->linked_case_study_id = null;
            }
        }
    }

    public function mount(?CaseStudy $caseStudy = null): void
    {
        CaseStudySource::ensureSchema();

        if ($caseStudy && $caseStudy->exists) {
            $this->editingId = $caseStudy->id;
            $this->titulo = $caseStudy->titulo;
            $this->descripcion = $caseStudy->descripcion ?? '';
            $this->url_demo = $caseStudy->url_demo ?? '';
            $this->categoria = $caseStudy->categoria === 'social' ? 'films' : $caseStudy->categoria;
            $this->source_type = $caseStudy->source_type;
            $this->client_id = $caseStudy->client_id;
            $this->linked_case_study_id = $caseStudy->linked_case_study_id;
            $this->video_url = $caseStudy->video_url ?? '';
            $this->video_orientation = $caseStudy->video_orientation ?? 'vertical';
            $this->video_duration = $caseStudy->video_duration ?? '';
            $this->metrica_valor = $caseStudy->metrica_valor ?? '';
            $this->metrica_label = $caseStudy->metrica_label ?? '';
            $this->tags_input = implode(', ', $caseStudy->tags ?? []);
            $this->gradient_inicio = $caseStudy->gradient_inicio ?? '#6366f1';
            $this->gradient_fin = $caseStudy->gradient_fin ?? '#8b5cf6';
            $this->activo = (bool) $caseStudy->activo;
            $this->proyecto_usa = (bool) $caseStudy->proyecto_usa;
            $this->orden = (int) $caseStudy->orden;
            $this->imagenActual = $caseStudy->image_url;
            if ($caseStudy->imagen && (filter_var($caseStudy->imagen, FILTER_VALIDATE_URL) || preg_match('#^https?://#i', (string) $caseStudy->imagen))) {
                $this->imagen_url = (string) $caseStudy->imagen;
            }
        }
    }

    public function removeImage(): void
    {
        $this->imagen_nueva = null;
        $this->imagen_url = '';
        $this->imagenActual = null;
        $this->quitar_imagen = true;
    }

    public function save(): void
    {
        CaseStudySource::ensureSchema();

        $this->titulo = trim($this->titulo);
        $this->categoria = $this->categoria === 'social' ? 'films' : ($this->categoria ?: 'web');
        $this->client_id = filled($this->client_id) ? (int) $this->client_id : null;
        $this->linked_case_study_id = filled($this->linked_case_study_id) ? (int) $this->linked_case_study_id : null;
        $this->source_type = filled($this->source_type) ? $this->source_type : null;

        if ($this->source_type === 'client' && ! $this->client_id) {
            $this->source_type = null;
        }
        if ($this->source_type === 'website' && ! $this->linked_case_study_id) {
            $this->source_type = null;
        }

        $this->gradient_inicio = $this->gradient_inicio ?: '#6366f1';
        $this->gradient_fin = $this->gradient_fin ?: '#8b5cf6';
        $this->video_orientation = in_array($this->video_orientation, ['vertical', 'horizontal']) ? $this->video_orientation : 'vertical';

        $this->imagen_url = trim($this->imagen_url);
        if ($this->imagen_url !== '' && ! preg_match('#^https?://#i', $this->imagen_url)) {
            $this->imagen_url = 'https://'.$this->imagen_url;
        }

        $this->validate();

        $study = $this->editingId ? CaseStudy::findOrFail($this->editingId) : null;

        $tags = array_values(array_filter(
            array_map('trim', explode(',', $this->tags_input))
        ));

        $categoriaEfectiva = $this->categoria;

        $data = [
            'titulo' => $this->titulo,
            'descripcion' => trim($this->descripcion) ?: null,
            'url_demo' => trim($this->url_demo) ?: null,
            'categoria' => $categoriaEfectiva,
            'source_type' => ($categoriaEfectiva === 'films') ? $this->source_type : null,
            'client_id' => ($categoriaEfectiva === 'films' && $this->source_type === 'client') ? $this->client_id : null,
            'linked_case_study_id' => ($categoriaEfectiva === 'films' && $this->source_type === 'website') ? $this->linked_case_study_id : null,
            'metrica_valor' => trim($this->metrica_valor) ?: null,
            'metrica_label' => trim($this->metrica_label) ?: null,
            'tags' => $tags ?: null,
            'gradient_inicio' => $this->gradient_inicio,
            'gradient_fin' => $this->gradient_fin,
            'activo' => $this->activo,
            'proyecto_usa' => $this->proyecto_usa,
            'orden' => (int) $this->orden,
        ];

        // Configuración específica de Videos para Films (enlace directo o streaming ultra-rápido)
        if ($categoriaEfectiva === 'films') {
            $data['video_url'] = trim($this->video_url) ?: null;
            $data['video_orientation'] = $this->video_orientation;
            $data['video_duration'] = trim($this->video_duration) ?: null;
        }

        // Si vincula un sitio web y no escribió URL propia, autovincular la URL del sitio
        if ($categoriaEfectiva === 'films' && $this->source_type === 'website' && empty($data['url_demo']) && $this->linked_case_study_id) {
            $linked = CaseStudy::find($this->linked_case_study_id);
            if ($linked && ! empty($linked->url_demo)) {
                $data['url_demo'] = $linked->url_demo;
            }
        }

        if ($this->imagen_nueva) {
            if ($study?->imagen && ! filter_var($study->imagen, FILTER_VALIDATE_URL) && $study->imagen !== CaseStudyImage::DATABASE_MARKER) {
                Storage::disk('public')->delete($study->imagen);
                @unlink(public_path('storage/'.$study->imagen));
            }
            $data = array_merge($data, CaseStudyImage::attributesFrom($this->imagen_nueva));
        } elseif (filled($this->imagen_url)) {
            if ($study?->imagen && ! filter_var($study->imagen, FILTER_VALIDATE_URL) && $study->imagen !== CaseStudyImage::DATABASE_MARKER) {
                Storage::disk('public')->delete($study->imagen);
                @unlink(public_path('storage/'.$study->imagen));
            }
            $data['imagen'] = $this->imagen_url;
            $data['imagen_data'] = null;
            $data['imagen_mime'] = null;
        } elseif ($this->quitar_imagen) {
            if ($study?->imagen && ! filter_var($study->imagen, FILTER_VALIDATE_URL) && $study->imagen !== CaseStudyImage::DATABASE_MARKER) {
                Storage::disk('public')->delete($study->imagen);
                @unlink(public_path('storage/'.$study->imagen));
            }
            $data['imagen'] = null;
            $data['imagen_data'] = null;
            $data['imagen_mime'] = null;
        }

        try {
            if ($study) {
                $study->update($data);
                $msg = 'Caso de estudio actualizado correctamente.';
            } else {
                CaseStudy::create($data);
                $msg = 'Caso de estudio creado correctamente.';
            }
        } catch (\Throwable $e) {
            report($e);
            $this->addError('general', 'Error al guardar el proyecto en la base de datos: '.$e->getMessage());

            return;
        }

        session()->flash('notify', $msg);

        $this->redirectRoute('case-studies.index', navigate: true);
    }

    public function render()
    {
        $clients = Client::query()->orderBy('name')->get(['id', 'name', 'tax_id']);

        $existingWebsites = CaseStudy::query()
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->whereIn('categoria', ['web', 'ecommerce', 'sistema'])
            ->orderBy('titulo')
            ->get(['id', 'titulo', 'url_demo']);

        return view('livewire.case-studies.form', [
            'clients' => $clients,
            'existingWebsites' => $existingWebsites,
        ]);
    }
}
