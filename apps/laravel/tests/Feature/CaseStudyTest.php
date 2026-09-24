<?php

namespace Tests\Feature;

use App\Livewire\CaseStudies\Form;
use App\Livewire\CaseStudies\Index;
use App\Models\CaseStudy;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

    public function test_admin_can_create_case_study_with_sistema_category(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Plataforma SaaS Kamo')
            ->set('descripcion', 'Sistema operativo para agencias digitales')
            ->set('categoria', 'sistema')
            ->set('metrica_valor', '10x')
            ->set('metrica_label', 'eficiencia operativa')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Plataforma SaaS Kamo',
            'categoria' => 'sistema',
            'metrica_valor' => '10x',
        ]);
    }

    public function test_case_study_image_is_persisted_and_served_from_the_database(): void
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('case-study.png', 1200, 630);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Caso con imagen persistente')
            ->set('categoria', 'sistema')
            ->set('imagen_nueva', $image)
            ->call('save')
            ->assertHasNoErrors();

        $study = CaseStudy::where('titulo', 'Caso con imagen persistente')->sole();

        $this->assertSame('__database__', $study->imagen);
        $this->assertNotEmpty($study->imagen_data);
        $this->assertSame('image/png', $study->imagen_mime);

        $this->get(route('case-studies.image', $study))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->get(route('portfolio'))
            ->assertOk()
            ->assertSee('data-case-preview', false)
            ->assertSee('data-case-viewer', false)
            ->assertSee('Ver imagen ampliada de Caso con imagen persistente');
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

    public function test_admin_can_create_films_case_study_linked_to_client(): void
    {
        $user = User::factory()->create();
        $client = Client::create([
            'name' => 'Restaurante El Caribe',
            'tax_id' => '901234567-1',
            'email' => 'contacto@elcaribe.com',
        ]);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Reel Promocional Verano')
            ->set('descripcion', 'Producción de 3 reels para campaña de alta temporada')
            ->set('categoria', 'films')
            ->set('source_type', 'client')
            ->set('client_id', $client->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Reel Promocional Verano',
            'categoria' => 'films',
            'source_type' => 'client',
            'client_id' => $client->id,
        ]);

        $this->actingAs($user)
            ->get(route('case-studies.index'))
            ->assertOk()
            ->assertSee('Restaurante El Caribe')
            ->assertSee('901234567-1');
    }

    public function test_admin_can_create_films_case_study_linked_to_website(): void
    {
        $user = User::factory()->create();
        $website = CaseStudy::create([
            'titulo' => 'Portal Turístico Cartagena',
            'categoria' => 'web',
            'url_demo' => 'turismocartagena.com',
            'gradient_inicio' => '#000000',
            'gradient_fin' => '#333333',
        ]);

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Film Cinematográfico Destino')
            ->set('categoria', 'films')
            ->set('source_type', 'website')
            ->set('linked_case_study_id', $website->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Film Cinematográfico Destino',
            'categoria' => 'films',
            'source_type' => 'website',
            'linked_case_study_id' => $website->id,
        ]);

        $this->actingAs($user)
            ->get(route('case-studies.index'))
            ->assertOk()
            ->assertSee('Portal Turístico Cartagena');
    }

    public function test_admin_can_create_films_case_study_with_video_and_orientation(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Reel Gastronómico 4K')
            ->set('categoria', 'films')
            ->set('video_url', 'https://cdn.ejemplo.com/videos/reel-promo.mp4')
            ->set('video_orientation', 'vertical')
            ->set('video_duration', '0:45')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Reel Gastronómico 4K',
            'categoria' => 'films',
            'video_url' => 'https://cdn.ejemplo.com/videos/reel-promo.mp4',
            'video_orientation' => 'vertical',
            'video_duration' => '0:45',
        ]);

        $study = CaseStudy::where('titulo', 'Reel Gastronómico 4K')->firstOrFail();
        $this->assertTrue($study->is_reel);
        $this->assertFalse($study->is_horizontal);
        $this->assertTrue($study->is_direct_video);

        $this->get(route('portfolio'))
            ->assertOk()
            ->assertSee('data-film-viewer', false)
            ->assertSee('data-film-play', false)
            ->assertSee('Reel 9:16')
            ->assertSee('0:45')
            ->assertSee('Reel Gastronómico 4K');
    }
}

