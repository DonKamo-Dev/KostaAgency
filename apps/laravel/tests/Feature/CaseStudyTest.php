<?php

namespace Tests\Feature;

use App\Livewire\CaseStudies\Form;
use App\Livewire\CaseStudies\Index;
use App\Models\CaseStudy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CaseStudyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_visit_create_and_edit_case_study_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('case-studies.create'))
            ->assertOk()
            ->assertSee('Nuevo Caso de Estudio');

        $study = CaseStudy::create([
            'titulo' => 'Proyecto Existente',
            'categoria' => 'web',
            'gradient_inicio' => '#111111',
            'gradient_fin' => '#222222',
        ]);

        $this->actingAs($user)
            ->get(route('case-studies.edit', $study))
            ->assertOk()
            ->assertSee('Editar Caso de Estudio')
            ->assertSee('Proyecto Existente');
    }

    public function test_admin_can_create_case_study_via_dedicated_form(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Nuevo Proyecto Web')
            ->set('descripcion', 'Descripción completa del proyecto')
            ->set('categoria', 'web')
            ->set('metrica_valor', '+250%')
            ->set('metrica_label', 'tráfico web')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Nuevo Proyecto Web',
            'categoria' => 'web',
            'metrica_valor' => '+250%',
        ]);
    }

    public function test_admin_can_update_case_study_via_dedicated_form(): void
    {
        $user = User::factory()->create();

        $study = CaseStudy::create([
            'titulo' => 'Caso Inicial',
            'categoria' => 'branding',
            'gradient_inicio' => '#111111',
            'gradient_fin' => '#222222',
        ]);

        Livewire::actingAs($user)
            ->test(Form::class, ['caseStudy' => $study])
            ->set('titulo', 'Caso Actualizado')
            ->set('categoria', 'ecommerce')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'id' => $study->id,
            'titulo' => 'Caso Actualizado',
            'categoria' => 'ecommerce',
        ]);
    }

    public function test_admin_can_create_and_manage_case_studies(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('titulo', 'Proyecto Kamo')
            ->set('descripcion', 'Caso de estudio propio')
            ->call('save')
            ->assertHasNoErrors();

        $study = CaseStudy::where('titulo', 'Proyecto Kamo')->sole();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('toggleActivo', $study->id)
            ->call('delete', $study->id);

        $this->assertSoftDeleted('case_studies', ['id' => $study->id]);
        $this->assertNull(CaseStudy::find($study->id));
        $this->assertNotNull(CaseStudy::withTrashed()->find($study->id));
    }
}
