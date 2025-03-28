<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user registration.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'phone' => $this->faker->phoneNumber,
            'role' => 'candidat',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
            'phone' => $userData['phone'],
            'role' => $userData['role'],
        ]);
    }

    /**
     * Test user registration with duplicate email.
     */
    public function test_user_cannot_register_with_duplicate_email(): void
    {
        // Create a user first
        $user = User::factory()->create();

        $userData = [
            'name' => $this->faker->name,
            'email' => $user->email, // Use the same email
            'password' => 'password123',
            'phone' => $this->faker->phoneNumber,
            'role' => 'candidat',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Cet email existe déjà',
            ]);
    }

    /**
     * Test user login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Connexion réussie.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'user',
            ]);
    }

    /**
     * Test user login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(202)
            ->assertJson([
                'success' => false,
                'message' => "le mode passé n'est pas valide",
            ]);
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ]);
    }

    /**
     * Test token refresh.
     */
    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Token rafraîchi avec succès',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'token',
            ]);
    }
}