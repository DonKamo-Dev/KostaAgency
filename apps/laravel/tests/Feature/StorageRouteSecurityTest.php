<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageRouteSecurityTest extends TestCase
{
    public function test_public_storage_file_can_be_served(): void
    {
        Storage::disk('public')->put('route-test/example.txt', 'safe file');

        try {
            $this->get('/storage/route-test/example.txt')
                ->assertOk()
                ->assertHeader('content-type', 'text/plain; charset=UTF-8');
        } finally {
            Storage::disk('public')->deleteDirectory('route-test');
        }
    }

    public function test_storage_route_rejects_path_traversal(): void
    {
        foreach ([
            '/storage/%2e%2e/%2e%2e/.env',
            '/storage/..%2F..%2F.env',
            '/storage/..%5C..%5C.env',
        ] as $path) {
            $this->get($path)->assertNotFound();
        }
    }
}
