<?php

namespace Database\Factories;

use App\Models\Recruteur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recruteur>
 */
class RecruteurFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recruteur::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a user with role 'recruteur'
        $user = User::factory()->create([
            'role' => 'recruteur',
        ]);

        return [
            'id' => $user->id,
        ];
    }
}