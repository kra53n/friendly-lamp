<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Account;
use App\Models\Transaction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_account_id' => Account::factory()->create()->id,
            'to_account_id' => Account::factory()->create()->id,
            'amount' => $this->faker->randomFloat(10, 0.01, 10000),
            'status' => 'pending',
        ];
    }
}
