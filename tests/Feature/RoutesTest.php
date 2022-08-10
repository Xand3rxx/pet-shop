<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    /**
     * Test to ascertain the admin login route exists.
     *
     * @return void
     */
    public function admin_login_route_exists()
    {
        $response = $this->get('/api/v1/admin/login');

        $response->assertStatus(200);
    }
}
