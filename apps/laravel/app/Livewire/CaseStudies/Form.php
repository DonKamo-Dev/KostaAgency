<?php

namespace App\Livewire\CaseStudies;

use App\Models\CaseStudy;
use App\Support\CaseStudyImage;
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
        'categoria' => 'required|in:web,ecommerce,sistema,branding,social',
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

    public function mount(?CaseStudy $caseStudy = null): void
    {
        if ($caseStudy && $caseStudy->exists) {
            $this->editingId = $caseStudy->id;
            $this->titulo = $caseStudy->titulo;
            $this->descripcion = $caseStudy->descripcion ?? '';
            $this->url_demo = $caseStudy->url_demo ?? '';
            $this->categoria = $caseStudy->categoria;
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
        $this->validate();

        $study = $this->editingId ? CaseStudy::findOrFail($this->editingId) : null;

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
        return view('livewire.case-studies.form');
    }
}
