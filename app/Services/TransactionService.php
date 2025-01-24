<?

namespace App\Services;

use App\Models\Account;

class TransactionService {
    public static function changeStatus(array $properties): array {
        $status = 'pending';
        if (array_key_exists('status', $properties)) {
            $status = $properties['status'];
        }
        $from_account_balance = Account::find($properties['from_account_id']);
        $amount = $properties['amount'];
        if ($amount > $from_account_balance) {
            $status = 'failed';
        }
        $properties['status'] = $status;
        return $properties;
    }
}
