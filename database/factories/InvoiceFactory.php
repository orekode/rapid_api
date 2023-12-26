<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['billed', 'payed', 'void']);

        return [
            'user_id'      => User::factory(),
            'amount'       => $this->faker->numberBetween(100, 12000),
            'status'       => $status,
            'paid_date'   => $status === 'payed' ? $this->faker->dateTimeThisDecade() : null,
            'billed_date'  => $this->faker->dateTimeThisDecade(),
        ];
    }
}
