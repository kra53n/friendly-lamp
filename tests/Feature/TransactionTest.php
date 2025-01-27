<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Transaction;
use App\Models\Account;

class TransactionTest extends TestCase
{
    public function test_store(): void
    {
        $from_account = Account::factory()->create();
        $to_account = Account::factory()->create();
        $data = [
            'from_account_id' => $from_account->id,
            'to_account_id' => $to_account->id,
            'amount' => 30,
            'status' => 'pending',
        ];
        $response = $this->postJson('/api/transactions', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', $data);
    }

    public function test_update_by_id(): void
    {
        $transaction = Transaction::factory()->create();
        $transaction->save();
        $balance_before = $transaction['amount'];
        $data = [
            'balance' => 3333,
        ];
        $response = $this->putJson(sprintf('/api/transactions/%d', $transaction->id), $data);
        $response->assertStatus(200);
        $transaction->refresh();
        $this->assertNotEquals($balance_before, $transaction['amount']);
    }
}
