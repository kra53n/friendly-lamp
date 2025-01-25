<?


use App\Models\Transaction;
use App\Models\Account;

class AccountService {
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
