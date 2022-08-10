<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\AttachesJWT;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminLoginTest extends TestCase
{
    use AttachesJWT;

    /**
     * A test to determine if an email field is provided.
     * @test
     * @return void
     */
    public function admin_email_login_field_is_required()
    {
        $this->post('/api/v1/admin/login', array_merge($this->loginPayload(), ['email' => '']))->assertStatus(422);
    }

    /**
     * A test to determine if an password field is provided.
     * @test
     * @return void
     */
    public function admin_password_login_field_is_required()
    {
        $this->post('/api/v1/admin/login', array_merge($this->loginPayload(), ['password' => '']))->assertStatus(422);
    }

    /**
     * @test
     * Test to ascertain the admin login route exists.
     *
     *  @return  \Illuminate\Contracts\Auth\Authenticatable $admin
     */
    public function admin_can_login()
    {
        return $this->post('/api/v1/admin/login', $this->loginPayload())->assertStatus(Response::HTTP_OK);
    }


    /**
     * A test to assertain that a user list can only be requested by an authenticated  administrator.
     * @return void
     */
    public function only_an_authenticated_administrator_can_request_for_user_listing()
    {
        $this->loginAs($this->admin_can_login())->withMiddleware(['auth.api' => true])->get('/api/v1/admin/user-listing')->assertRedirect('/api/v1/admin/login');

        $this->assertCount(0, User::basicUsers()->limit(100)->get());
    }

    /**
     * Dummy login payload for an administrative user.
     * @return array
     */
    private function loginPayload()
    {
        return [
            'email'     => 'admin@buckhill.co.uk',
            'password'  => 'admin',
        ];
    }
}
