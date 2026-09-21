<?php

namespace Tests\Feature;

use App\Livewire\CaseStudies\Index;
use App\Models\CaseStudy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CaseStudyTest extends TestCase
{
    use RefreshDatabase;

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
