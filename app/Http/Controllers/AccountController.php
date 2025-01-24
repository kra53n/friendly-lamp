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

    public function get_by_id($id) {
        $accounts = Account::where('user_id','=', $id);
        if ($accounts->count() > 0) {
            return response()->json($accounts->get(), 200);
        }
        if (is_numeric($id)) {
            return response()->json(sprintf('there is no user with id %d', $id), 404);
        }
        return response()->json('should be given integer', 404);
    }

    public function update_by_id(Request $request, $id) {
        $validator = $this->validate_update_request($request);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();
        $account = Account::find($id);
        if ($account == null) {
            return response()->json(sprintf('there is no account with id %d', $id), 404);
        }
        $account->fill($validated);
        $account->save();
        return response()->json($account, 200);
    }

    public function delete_by_id($id) {
        $account = Account::find($id);
        $account->delete();
        return response()->json($account, 200);
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
