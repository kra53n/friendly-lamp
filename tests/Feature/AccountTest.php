<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Account;
use App\Models\User;

class AccountTest extends TestCase
{
    public function test_get_by_id(): void
    {
        $account = Account::factory()->create();
        $account->save();
        $response = $this->get(sprintf('/api/accounts/%d', $account->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $account->id,
            'user_id' => $account->user_id,
            'balance' => $account->balance,
        ]);
    }

    public function test_store_with_good_data(): void
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'balance' => 100,
        ];
        $response = $this->postJson('/api/accounts', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('accounts', $data);
    }

    public function test_store_with_bad_balance(): void
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'balance' => -1,
        ];
        $response = $this->postJson('/api/accounts', $data);
        $response->assertStatus(400);
    }

    public function test_update_by_id(): void
    {
        $account = Account::factory()->create();
        $account->save();
        $balance_before = $account['balance'];
        $data = [
            'balance' => 3333,
        ];
        $response = $this->putJson(sprintf('/api/accounts/%d', $account->id), $data);
        $response->assertStatus(200);
        $account->refresh();
        $this->assertNotEquals($balance_before, $account['balance']);
    }
}
