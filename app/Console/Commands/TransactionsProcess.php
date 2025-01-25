<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Account;
use App\Models\Transaction;

class TransactionsProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process transactions with `pending` status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('status', 'pending')->get();
        $accounts = self::get_accounts_by_transactions($transactions);
        foreach ($transactions as $transaction) {
            self::process_transaction($transaction, $accounts);
        }
    }

    public static function process_transaction(Transaction $transaction, $accounts) {
        $amount = $transaction['amount'];
        $from_account = $accounts[$transaction['from_account_id']];
        $to_account = $accounts[$transaction['to_account_id']];

        if ($amount < $from_account['balance']) {
            $from_account['balance'] -= $amount;
            $to_account['balance'] += $amount;
            $transaction['status'] = 'completed';
            
            $transaction->save();
            $from_account->save();
            $to_account->save();

            return;
        }
        $transaction['status'] = 'failed';
        $transaction->save();
    }

    // PROBLEM(kra53n): commit: 5aea5c75f6f32e5aa2329e5f3d5f1ec24f76cbd3
    /**
     * @param \Illuminate\Support\Collection<int, Transaction>
     * @return "dictionary" where key is account_id and value is account model
     */
    public static function get_accounts_by_transactions($transactions): array {
        $accounts = [];
        foreach ($transactions as $transaction) {
            $accounts = self::add_account_to_accounts_by_id($transaction['from_account_id'], $accounts);
            $accounts = self::add_account_to_accounts_by_id($transaction['to_account_id'], $accounts);
        }
        return $accounts;
    }

    private static function add_account_to_accounts_by_id(int $account_id, $accounts): array {
            if (array_key_exists($account_id, $accounts)) {
                return $accounts;
            }
            $account = Account::find($account_id);
            $accounts[$account_id] = $account;
            return $accounts;
    }
}
