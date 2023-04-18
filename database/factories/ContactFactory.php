<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contactable = $this->contactable();

        return [
            'contactable_id' => $contactable::factory(),
            'contactable_type' => $contactable,
            'nick_name' => $this->faker->firstName(),
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'created_by' => 'system'
        ];
    }

    public function contactable()
    {
        return $this->faker->randomElement([
            User::class
        ]);
    }
}
