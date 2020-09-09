<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testRegistration()
    {
        $body = [
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'email' => 'some.email@ex.com',
            'password' => 'admin123'
        ];

        $response = $this->post('/api/auth/register', $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
                'updated_at'
            ],
            'access_token'
        ]);
        $this->assertDatabaseHas(
            'users',
            [
                'first_name' => 'Firstname',
                'last_name' => 'Lastname',
                'email' => 'some.email@ex.com'
            ]
        );
    }

    public function testLogin()
    {
        $password = 'admin123';
        $hash = Hash::make($password);

        $user = factory(User::class)->create(['password' => $hash]);
        $body = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->post('/api/auth/login', $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
                'updated_at'
            ],
            'access_token'
        ]);
    }
}
