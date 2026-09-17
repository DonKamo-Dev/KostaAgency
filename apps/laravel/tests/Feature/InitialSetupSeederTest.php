<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\InitialSetupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InitialSetupSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_the_admin_with_the_development_email_address(): void
    {
        $this->seed(InitialSetupSeeder::class);

        $admin = User::where('email', 'yohanblaro18@gmail.com')->firstOrFail();

        $this->assertDatabaseHas('users', ['email' => 'yohanblaro18@gmail.com']);
        $this->assertDatabaseMissing('users', ['email' => 'yohanblanco18@gmail.com']);
        $this->assertTrue(Hash::check('admin123', $admin->password));
        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
    }

    public function test_it_repairs_the_previous_email(): void
    {
        $this->seed(InitialSetupSeeder::class);
        User::where('email', 'yohanblaro18@gmail.com')->update(['email' => 'yohanblanco18@gmail.com']);

        $this->seed(InitialSetupSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'yohanblaro18@gmail.com']);
        $this->assertDatabaseMissing('users', ['email' => 'yohanblanco18@gmail.com']);
    }
}
