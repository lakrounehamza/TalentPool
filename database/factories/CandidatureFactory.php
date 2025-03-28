<?php

namespace Database\Factories;

use App\Models\Annonce;
use App\Models\Candidate;
use App\Models\Candidature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidature>
 */
class CandidatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidature::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'annonce_id' => Annonce::factory(),
            'candidate_id' => Candidate::factory(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected', 'in_review']),
            'cv' => 'cv_' . $this->faker->uuid . '.pdf',
        ];
    }

    /**
     * Indicate that the candidature is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the candidature is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the candidature is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the candidature is in review.
     */
    public function inReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_review',
        ]);
    }
}