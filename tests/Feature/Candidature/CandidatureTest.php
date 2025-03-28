<?php

namespace Tests\Feature\Candidature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Candidature;
use App\Models\Candidate;
use App\Models\Annonce;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CandidatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing all job applications.
     */
    public function test_authenticated_user_can_list_all_candidatures(): void
    {
        // Create a user and generate token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Create some job applications
        Candidature::factory()->count(3)->create();

        // Make authenticated request to list all job applications
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/candidatures');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test viewing a specific job application.
     */
    public function test_authenticated_user_can_view_single_candidature(): void
    {
        // Create a user and generate token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Create a job application
        $candidature = Candidature::factory()->create();

        // Make authenticated request to view the job application
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/candidatures/' . $candidature->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'id' => $candidature->id,
                'annonce_id' => $candidature->annonce_id,
                'candidate_id' => $candidature->candidate_id,
                'status' => $candidature->status,
                'cv' => $candidature->cv,
            ]);
    }

    /**
     * Test creating a new job application.
     */
    public function test_authenticated_candidate_can_create_candidature(): void
    {
        // Create a candidate
        $candidate = Candidate::factory()->create();
        $user = User::find($candidate->id);
        
        // Create a job posting
        $annonce = Annonce::factory()->create();
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Prepare job application data
        $candidatureData = [
            'annonce_id' => $annonce->id,
            'candidate_id' => $candidate->id,
            'status' => 'pending',
            'cv' => 'test_cv.pdf',
        ];

        // Make authenticated request to create job application
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/candidatures', $candidatureData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Candidature created successfully',
            ]);

        // Assert job application was created in database
        $this->assertDatabaseHas('candidatures', [
            'annonce_id' => $candidatureData['annonce_id'],
            'candidate_id' => $candidatureData['candidate_id'],
            'status' => $candidatureData['status'],
            'cv' => $candidatureData['cv'],
        ]);
    }

    /**
     * Test that unauthenticated users cannot create job applications.
     */
    public function test_unauthenticated_user_cannot_create_candidature(): void
    {
        // Create a job posting and a candidate
        $annonce = Annonce::factory()->create();
        $candidate = Candidate::factory()->create();

        // Prepare job application data
        $candidatureData = [
            'annonce_id' => $annonce->id,
            'candidate_id' => $candidate->id,
            'status' => 'pending',
            'cv' => 'test_cv.pdf',
        ];

        // Make unauthenticated request to create job application
        $response = $this->postJson('/api/candidatures', $candidatureData);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Test updating a job application.
     */
    public function test_authenticated_candidate_can_update_candidature(): void
    {
        // Create a candidate and job application
        $candidate = Candidate::factory()->create();
        $user = User::find($candidate->id);
        $candidature = Candidature::factory()->create([
            'candidate_id' => $candidate->id,
        ]);
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Prepare updated data
        $updatedData = [
            'status' => 'in_review',
            'cv' => 'updated_cv.pdf',
        ];

        // Make authenticated request to update job application
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/candidatures/' . $candidature->id, $updatedData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Candidature updated successfully',
            ]);

        // Assert job application was updated in database
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'status' => $updatedData['status'],
            'cv' => $updatedData['cv'],
        ]);
    }

    /**
     * Test that unauthenticated users cannot update job applications.
     */
    public function test_unauthenticated_user_cannot_update_candidature(): void
    {
        // Create a job application
        $candidature = Candidature::factory()->create();

        // Prepare updated data
        $updatedData = [
            'status' => 'in_review',
            'cv' => 'updated_cv.pdf',
        ];

        // Make unauthenticated request to update job application
        $response = $this->putJson('/api/candidatures/' . $candidature->id, $updatedData);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Test deleting a job application.
     */
    public function test_authenticated_candidate_can_delete_candidature(): void
    {
        // Create a candidate and job application
        $candidate = Candidate::factory()->create();
        $user = User::find($candidate->id);
        $candidature = Candidature::factory()->create([
            'candidate_id' => $candidate->id,
        ]);
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Make authenticated request to delete job application
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/candidatures/' . $candidature->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Candidature deleted successfully',
            ]);

        // Assert job application was deleted from database
        $this->assertDatabaseMissing('candidatures', [
            'id' => $candidature->id,
        ]);
    }

    /**
     * Test that unauthenticated users cannot delete job applications.
     */
    public function test_unauthenticated_user_cannot_delete_candidature(): void
    {
        // Create a job application
        $candidature = Candidature::factory()->create();

        // Make unauthenticated request to delete job application
        $response = $this->deleteJson('/api/candidatures/' . $candidature->id);

        // Assert response (should be unauthorized)
        $response->assertStatus(401);
    }

    /**
     * Test getting job applications by candidate.
     */
    public function test_can_get_candidatures_by_candidate(): void
    {
        // Create a candidate and job applications
        $candidate = Candidate::factory()->create();
        $user = User::find($candidate->id);
        Candidature::factory()->count(3)->create([
            'candidate_id' => $candidate->id,
        ]);
        
        // Create some other job applications
        Candidature::factory()->count(2)->create();
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Make authenticated request to get job applications by candidate
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/candidatures/candidat/' . $candidate->id);

        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test getting job applications by status.
     */
    public function test_can_get_candidatures_by_status(): void
    {
        // Create a user and generate token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Create job applications with different statuses
        Candidature::factory()->count(2)->pending()->create();
        Candidature::factory()->count(3)->accepted()->create();
        Candidature::factory()->count(1)->rejected()->create();

        // Make authenticated request to get job applications by status
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/candidatures/miennes/status?status=accepted');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test getting job applications by candidate and status.
     */
    public function test_can_get_candidatures_by_candidate_and_status(): void
    {
        // Create a candidate and job applications with different statuses
        $candidate = Candidate::factory()->create();
        $user = User::find($candidate->id);
        
        Candidature::factory()->count(2)->pending()->create([
            'candidate_id' => $candidate->id,
        ]);
        
        Candidature::factory()->count(3)->accepted()->create([
            'candidate_id' => $candidate->id,
        ]);
        
        // Create some other job applications
        Candidature::factory()->count(2)->create();
        
        // Generate token
        $token = JWTAuth::fromUser($user);

        // Make authenticated request to get job applications by candidate and status
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/candidatures/' . $candidate->id . '/accepted');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}