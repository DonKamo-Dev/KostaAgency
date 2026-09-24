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
        'source_type.in' => 'El tipo de anclaje debe ser Empresa o Sitio Web.',
        'client_id.exists' => 'La empresa seleccionada no es válida.',
        'linked_case_study_id.exists' => 'El sitio web seleccionado no es válido.',
        'imagen_nueva.image' => 'El archivo debe ser una imagen (JPG, PNG, WebP).',
        'imagen_nueva.max' => 'La imagen no puede superar 2 MB.',
    ];

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
            $this->metrica_valor = $caseStudy->metrica_valor ?? '';
            $this->metrica_label = $caseStudy->metrica_label ?? '';
            $this->tags_input = implode(', ', $caseStudy->tags ?? []);
            $this->gradient_inicio = $caseStudy->gradient_inicio ?? '#6366f1';
            $this->gradient_fin = $caseStudy->gradient_fin ?? '#8b5cf6';
            $this->activo = (bool) $caseStudy->activo;
            $this->orden = (int) $caseStudy->orden;
            $this->imagenActual = $caseStudy->image_url;
        }
    }

    public function save(): void
    {
        CaseStudySource::ensureSchema();

        $this->validate();

        $study = $this->editingId ? CaseStudy::findOrFail($this->editingId) : null;

        $tags = array_values(array_filter(
            array_map('trim', explode(',', $this->tags_input))
        ));

        $categoriaEfectiva = $this->categoria === 'social' ? 'films' : $this->categoria;

        $data = [
            'titulo' => trim($this->titulo),
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
            'orden' => $this->orden,
        ];

        // Si vincula un sitio web y no escribió URL propia, autovincular la URL del sitio
        if ($categoriaEfectiva === 'films' && $this->source_type === 'website' && empty($data['url_demo']) && $this->linked_case_study_id) {
            $linked = CaseStudy::find($this->linked_case_study_id);
            if ($linked && ! empty($linked->url_demo)) {
                $data['url_demo'] = $linked->url_demo;
            }
        }

        if ($this->imagen_nueva) {
            if ($study?->imagen) {
                Storage::disk('public')->delete($study->imagen);
                @unlink(public_path('storage/'.$study->imagen));
            }
            $data = array_merge($data, CaseStudyImage::attributesFrom($this->imagen_nueva));
        }

        if ($study) {
            $study->update($data);
            $msg = 'Caso de estudio actualizado correctamente.';
        } else {
            CaseStudy::create($data);
            $msg = 'Caso de estudio creado correctamente.';
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
