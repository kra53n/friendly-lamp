<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function get_all(Request $request) {
        return response()->json(Transaction::all());
    }

    public function store(Request $request) {
        $validator = $this->validate_store_request($request);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();
        $validated = TransactionService::changeStatus($validated);
        $transaction = new Transaction;
        $transaction->fill($validated);
        return response()->json($transaction, 201);
    }

    public function get_by_id($id) {
        $transaction = Transaction::find($id);
        if ($transaction == null) {
            return response()->json(sprintf('there is no transaction with id %d', $id), 404);
        }
        if (is_numeric($id)) {
            return response()->json('should be given integer', 404);
        }
        return response()->json($transaction, 200);
    }

    public function update_by_id(Request $request, $id) {
        $validator = $this->validate_update_request($request);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();
        $transaction = Transaction::find($id);
        if ($transaction == null) {
            return response()->json(sprintf('there is no transaction with id %d', $id), 404);
        }
        $transaction->fill($validated);
        $transaction->save();
        return response()->json($transaction, 200);
    }

    public function delete_by_id($id) {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json($transaction, 200);
    }

    public function validate_store_request(Request $request) {
        $validation_rules = [
            'from_account_id' => 'required|integer|exists:accounts,id|different:to_account_id',
            'to_account_id' => 'required|integer|exists:accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
        ];
        
        $validation_err_msgs = [
            'from_account_id.required' => 'The :attribute field is required.',
            'from_account_id.integer' => 'The :attribute must be an integer.',
            'from_account_id.exists' => 'The account with ID :attribute does not exist.',
            'from_account_id.different' => 'The from_account_id and to_account_id fields must be different.',
            
            'to_account_id.required' => 'The :attribute field is required.',
            'to_account_id.integer' => 'The :attribute must be an integer.',
            'to_account_id.exists' => 'The account with ID :attribute does not exist.',
            'to_account_id.different' => 'The from_account_id and to_account_id fields must be different.',
        
            'amount.required' => 'The :attribute field is required.',
            'amount.numeric' => 'The :attribute must be a number.',
            'amount.min' => 'The amount must be greater than zero.',
        ];
        
        return Validator::make($request->all(), $validation_rules, $validation_err_msgs);
    }

    public function validate_update_request(Request $request) {
        $validation_rulse = [
            'user_id' => 'integer|exists:users,id',
            'balance' => 'required|numeric|min:0',
        ];
        $validation_err_msgs = [
            'user_id' => 'The :attribute must exists',
            'balance' => 'The :attribute muse bet at least :min',
        ];
        return Validator::make($request->all(), $validation_rulse, $validation_err_msgs);
    }
}
