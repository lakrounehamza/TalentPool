<?php

namespace Tests\Feature\Annonce;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Annonce;
use App\Models\Recruteur;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnnonceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing all job postings.
     */
    public function test_can_list_all_annonces(): void
    {
        // Create some job postings
        Annonce::factory()->count(3)->create();

        // Make request to list all job postings
        $response = $this->getJson('/api/annonces');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test viewing a specific job posting.
     */
    public function test_can_view_single_annonce(): void
    {
        // Create a job posting
        $annonce = Annonce::factory()->create();

        // Make request to view the job posting
        $response = $this->getJson('/api/annonces/' . $annonce->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'id' => $annonce->id,
                'title' => $annonce->title,
                'description' => $annonce->description,
                'status' => $annonce->status,
                'recruteur_id' => $annonce->recruteur_id,
            ]);
    }

    /**
     * Test creating a new job posting.
     */
    public function test_authenticated_recruteur_can_create_annonce(): void
    {
        // Create a recruiter
        $recruteur = Recruteur::factory()->create();
        $user = User::find($recruteur->id);
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Prepare job posting data
        $annonceData = [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'status' => 'active',
            'recruteur_id' => $recruteur->id,
        ];

        // Make authenticated request to create job posting
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/annonces', $annonceData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Annonce created successfully',
            ]);

        // Assert job posting was created in database
        $this->assertDatabaseHas('annonces', [
            'title' => $annonceData['title'],
            'description' => $annonceData['description'],
            'status' => $annonceData['status'],
            'recruteur_id' => $annonceData['recruteur_id'],
        ]);
    }

    /**
     * Test that unauthenticated users cannot create job postings.
     */
    public function test_unauthenticated_user_cannot_create_annonce(): void
    {
        // Prepare job posting data
        $annonceData = [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'status' => 'active',
            'recruteur_id' => 1,
        ];

        // Make unauthenticated request to create job posting
        $response = $this->postJson('/api/annonces', $annonceData);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Test updating a job posting.
     */
    public function test_authenticated_recruteur_can_update_annonce(): void
    {
        // Create a recruiter and job posting
        $recruteur = Recruteur::factory()->create();
        $user = User::find($recruteur->id);
        $annonce = Annonce::factory()->create([
            'recruteur_id' => $recruteur->id,
        ]);
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Prepare updated data
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated description for testing',
            'status' => 'inactive',
        ];

        // Make authenticated request to update job posting
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/annonces/' . $annonce->id, $updatedData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Annonce updated successfully',
            ]);

        // Assert job posting was updated in database
        $this->assertDatabaseHas('annonces', [
            'id' => $annonce->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
            'status' => $updatedData['status'],
        ]);
    }

    /**
     * Test that unauthenticated users cannot update job postings.
     */
    public function test_unauthenticated_user_cannot_update_annonce(): void
    {
        // Create a job posting
        $annonce = Annonce::factory()->create();

        // Prepare updated data
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated description for testing',
            'status' => 'inactive',
        ];

        // Make unauthenticated request to update job posting
        $response = $this->putJson('/api/annonces/' . $annonce->id, $updatedData);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Test deleting a job posting.
     */
    public function test_authenticated_recruteur_can_delete_annonce(): void
    {
        // Create a recruiter and job posting
        $recruteur = Recruteur::factory()->create();
        $user = User::find($recruteur->id);
        $annonce = Annonce::factory()->create([
            'recruteur_id' => $recruteur->id,
        ]);
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Make authenticated request to delete job posting
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/annonces/' . $annonce->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Annonce deleted successfully',
            ]);

        // Assert job posting was deleted from database
        $this->assertDatabaseMissing('annonces', [
            'id' => $annonce->id,
        ]);
    }

    /**
     * Test that unauthenticated users cannot delete job postings.
     */
    public function test_unauthenticated_user_cannot_delete_annonce(): void
    {
        // Create a job posting
        $annonce = Annonce::factory()->create();

        // Make unauthenticated request to delete job posting
        $response = $this->deleteJson('/api/annonces/' . $annonce->id);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }
}