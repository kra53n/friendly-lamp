<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Transaction;
use App\Services\AccountService;

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

    public function handle()
    {
        $transactions = Transaction::where('status', 'pending')->get();
        $accounts = AccountService::get_accounts_by_transactions($transactions);
        $failed_transactions = 0;
        foreach ($transactions as $transaction) {
            self::process_transaction($transaction, $accounts);
            if ($transaction->status == 'failed') {
                $failed_transactions++;
            }
        }
        $completed_transactions = count($transactions);
        printf('%d транзакций успешно обработались, %d транзакций было провалено', $completed_transactions, $failed_transactions);
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
}
