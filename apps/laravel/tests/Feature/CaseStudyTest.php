<?php

namespace Tests\Feature;

use App\Livewire\CaseStudies\Form;
use App\Livewire\CaseStudies\Index;
use App\Models\CaseStudy;
use App\Models\Client;
use App\Models\User;
use App\Support\CaseStudyVideo;
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

    public function test_cloudflare_r2_and_stream_videos_are_properly_handled(): void
    {
        // 1. Cloudflare R2 Public Storage URL (.mp4)
        $r2Url = 'https://pub-ab12cd34ef56.r2.dev/reels/kosta-reel.mp4';
        $this->assertTrue(CaseStudyVideo::isDirectVideo($r2Url));

        // 2. Cloudflare R2 custom domain
        $r2CustomDomain = 'https://media.kosta.studio/reels/drone-4k.mp4';
        $this->assertTrue(CaseStudyVideo::isDirectVideo($r2CustomDomain));

        // 3. Cloudflare Stream direct MP4
        $streamDirect = 'https://customer-12345.cloudflarestream.com/5d5ba37468faec03e289166e004b8f34/downloads/default.mp4';
        $this->assertTrue(CaseStudyVideo::isDirectVideo($streamDirect));

        // 4. Cloudflare Stream embed / watch URL
        $streamWatch = 'https://videodelivery.net/5d5ba37468faec03e289166e004b8f34';
        $resolvedEmbed = CaseStudyVideo::resolveEmbedUrl($streamWatch);
        $this->assertEquals('https://iframe.videodelivery.net/5d5ba37468faec03e289166e004b8f34?autoplay=true&muted=false&preload=true', $resolvedEmbed);
    }

    public function test_admin_can_toggle_proyecto_usa_from_index(): void
    {
        $user = User::factory()->create();
        $study = CaseStudy::create([
            'titulo' => 'Dentista Internacional Miami',
            'categoria' => 'web',
            'proyecto_usa' => false,
            'gradient_inicio' => '#000000',
            'gradient_fin' => '#111111',
        ]);

        $this->assertFalse($study->fresh()->proyecto_usa);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('toggleProyectoUsa', $study->id);

        $this->assertTrue($study->fresh()->proyecto_usa);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('toggleProyectoUsa', $study->id);

        $this->assertFalse($study->fresh()->proyecto_usa);
    }

    public function test_admin_can_save_case_study_with_proyecto_usa_flag(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Luxury Agency NY')
            ->set('categoria', 'web')
            ->set('proyecto_usa', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('case-studies.index'));

        $this->assertDatabaseHas('case_studies', [
            'titulo' => 'Luxury Agency NY',
            'proyecto_usa' => true,
        ]);
    }

    public function test_proyecto_usa_badge_is_displayed_on_public_portfolio(): void
    {
        CaseStudy::create([
            'titulo' => 'Exclusive Luxury USA',
            'categoria' => 'web',
            'proyecto_usa' => true,
            'activo' => true,
            'gradient_inicio' => '#111111',
            'gradient_fin' => '#222222',
        ]);

        CaseStudy::create([
            'titulo' => 'Standard Local Project',
            'categoria' => 'web',
            'proyecto_usa' => false,
            'activo' => true,
            'gradient_inicio' => '#333333',
            'gradient_fin' => '#444444',
        ]);

        $this->get(route('portfolio'))
            ->assertOk()
            ->assertSee('Proyecto USA')
            ->assertSee('Exclusive Luxury USA');
    }

    public function test_case_study_image_rejects_files_larger_than_10mb(): void
    {
        $user = User::factory()->create();
        $largeImage = UploadedFile::fake()->create('large-image.png', 11000, 'image/png');

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Caso con imagen gigante')
            ->set('categoria', 'sistema')
            ->set('imagen_nueva', $largeImage)
            ->call('save')
            ->assertHasErrors(['imagen_nueva' => 'max']);
    }

    public function test_admin_can_save_case_study_with_external_image_url(): void
    {
        $user = User::factory()->create();
        $externalUrl = 'https://pub-abc123xyz.r2.dev/11-HiCustomBlinds.png';

        Livewire::actingAs($user)
            ->test(Form::class)
            ->set('titulo', 'Caso con imagen en Cloudflare R2')
            ->set('categoria', 'web')
            ->set('imagen_url', $externalUrl)
            ->call('save')
            ->assertHasNoErrors();

        $study = CaseStudy::where('titulo', 'Caso con imagen en Cloudflare R2')->sole();

        $this->assertSame($externalUrl, $study->imagen);
        $this->assertSame($externalUrl, $study->image_url);

        $this->get(route('portfolio'))
            ->assertOk()
            ->assertSee($externalUrl, false);
    }

    public function test_admin_can_remove_image(): void
    {
        $user = User::factory()->create();
        $study = CaseStudy::create([
            'titulo' => 'Caso con imagen previa',
            'categoria' => 'web',
            'gradient_inicio' => '#111111',
            'gradient_fin' => '#222222',
            'imagen' => 'https://pub-abc123xyz.r2.dev/foto.png',
        ]);

        Livewire::actingAs($user)
            ->test(Form::class, ['caseStudy' => $study])
            ->call('removeImage')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull($study->fresh()->imagen);
        $this->assertNull($study->fresh()->image_url);
    }
}
