<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Endpoint referensi wilayah harus merespons 200.
     * Regression guard untuk RateLimiting namespace yang sempat menyebabkan 500.
     */
    public function test_ref_pulau_endpoint_returns_successful_response(): void
    {
        $response = $this->getJson('/api/ref/pulau');

        $response->assertStatus(200);
    }

    /**
     * Endpoint data sekolah untuk peta harus merespons 200.
     */
    public function test_sekolah_api_endpoint_returns_successful_response(): void
    {
        $response = $this->getJson('/api/sekolah');

        $response->assertStatus(200);
    }
}
