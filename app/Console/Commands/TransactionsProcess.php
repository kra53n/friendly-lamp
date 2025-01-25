<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Transaction;

# use `include` instead of `use App\Services\AccountService;` beacuse
# error raised that said there is no this class
include_once __DIR__.'\..\..\Services\AccountService.php';
// use App\Services\AccountService;

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
        $accounts = AccountService::get_accounts_by_transactions($transactions);
        print($accounts);
    }
}
