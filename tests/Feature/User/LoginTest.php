<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    /**
     * Teste de login de usuário quando não autorizados
     *
     * @return void
     */
    public function testLoginWhenUnauthorized()
    {
        $user = User::factory()->create();

        $credentials = [
            'email' => $user->email,
            'password' => 'wrong_password',
        ];

        $response = $this->post('/api/users/login', $credentials);

        $response->assertStatus(401);
        $response->assertJson(
            [
                'success' => false,
                'method'  => 'POST',
                'code'    => 401,
                'data'    => null
            ],
            true
        );
    }

    /**
     * Teste de login de usuário com sucesso
     *
     * @return void
     */
    public function testLoginWithSuccess()
    {
        $password = 'correct_password';

        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->post('/api/users/login', $credentials);

        $response->assertStatus(200);
        $response->assertJson(
            [
                'success' => true,
                'method'  => 'POST',
                'code'    => 200,
            ],
            true
        );
        $response->assertJsonStructure(
            [
                'data' => [
                    'access_token'
                ]
            ]
        );
    }
}
