<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Account;

class AccountController extends Controller
{
    public function get_all_accounts(Request $request) {
        return response()->json(Account::all());
    }

    public function store(Request $request) {
        $validator = $this->validate_store_request($request);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();
        $account = Account::create([
            'user_id' => $validated['user_id'],
            'balance' => $validated['balance'],
        ]);
        return response()->json($account, 201);
    }

    public function validate_store_request(Request $request) {
        $validation_rulse = [
            'user_id' => 'required|integer|exists:users,id',
            'balance' => 'required|numeric|min:0',
        ];
        $validation_err_msgs = [
            'user_id' => 'The :attribute must exists',
            'balance' => 'The :attribute muse bet at least :min',
        ];
        return Validator::make($request->all(), $validation_rulse, $validation_err_msgs);
    }
}
