<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class DevelopmentRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_development_routes_are_hidden_outside_local_environment(): void
    {
        $this->reloadWebRoutesForEnvironment('production');

        $this->get('/entrar-kamo')->assertNotFound();
        $this->get('/entrar-kosta')->assertNotFound();
        $this->get('/react-test')->assertNotFound();
    }

    public function test_local_access_authenticates_configured_email_instead_of_fixed_id(): void
    {
        User::factory()->create(['email' => 'fixed-id@kamo.test']);
        $configuredUser = User::factory()->create(['email' => 'local@kamo.test']);
        config(['kamo.local_access_email' => $configuredUser->email]);
        $this->reloadWebRoutesForEnvironment('local');

        $this->get('/entrar-kamo')->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($configuredUser);
    }

    public function test_local_access_alias_authenticates_the_configured_user(): void
    {
        $configuredUser = User::factory()->create(['email' => 'local@kosta.test']);
        config(['kamo.local_access_email' => $configuredUser->email]);
        $this->reloadWebRoutesForEnvironment('local');

        $this->get('/entrar-kosta')->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($configuredUser);
    }

    public function test_local_access_fails_closed_when_email_is_not_configured(): void
    {
        User::factory()->create();
        config(['kamo.local_access_email' => null]);
        $this->reloadWebRoutesForEnvironment('local');

        $this->get('/entrar-kamo')->assertNotFound();
        $this->assertGuest();
    }

    public function test_local_access_fails_closed_when_configured_email_does_not_match_a_user(): void
    {
        User::factory()->create(['email' => 'existing@kamo.test']);
        config(['kamo.local_access_email' => 'missing@kamo.test']);
        $this->reloadWebRoutesForEnvironment('local');

        $this->get('/entrar-kamo')->assertNotFound();

        $this->assertGuest();
    }

    public function test_local_access_regenerates_the_session(): void
    {
        $user = User::factory()->create(['email' => 'local@kamo.test']);
        config(['kamo.local_access_email' => $user->email]);
        config(['session.driver' => 'array']);
        app('session')->forgetDrivers();
        $this->reloadWebRoutesForEnvironment('local');
        Session::setId(str_repeat('a', 40));
        Session::start();
        $previousSessionId = Session::getId();

        $this->get('/entrar-kamo');

        $this->assertNotSame($previousSessionId, Session::getId());
    }

    public function test_react_test_route_is_available_in_local_environment(): void
    {
        $this->reloadWebRoutesForEnvironment('local');

        $this->get('/react-test')->assertOk();
    }

    private function reloadWebRoutesForEnvironment(string $environment): void
    {
        app()->detectEnvironment(fn () => $environment);
        Route::setRoutes(new RouteCollection);

        Route::middleware('web')->group(base_path('routes/web.php'));
        Route::getRoutes()->refreshNameLookups();
        app('url')->setRoutes(Route::getRoutes());
    }
}
