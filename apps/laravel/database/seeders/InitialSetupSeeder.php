<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Create the private dashboard administrator.
        $user = User::firstOrNew(['email' => 'yohanblaro18@gmail.com']);

        if (! $user->exists) {
            $user = User::where('email', 'yohanblanco18@gmail.com')->first() ?? $user;
        }

        $user->forceFill([
            'name' => 'Admin Kamo',
            'email' => 'yohanblaro18@gmail.com',
            'password' => Hash::make('{KB-B8U$21'),
            'email_verified_at' => now(),
        ])->save();

        // Also ensure kamodesingdj@gmail.com exists
        $userKamo = User::firstOrNew(['email' => 'kamodesingdj@gmail.com']);
        $userKamo->forceFill([
            'name' => 'Kamo Design',
            'email' => 'kamodesingdj@gmail.com',
            'password' => Hash::make('{KB-B8U$21'),
            'email_verified_at' => now(),
        ])->save();
    }
}
