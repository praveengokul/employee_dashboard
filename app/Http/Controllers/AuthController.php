<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterUserPostRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use HttpResponses;
    public function register(StoreRegisterUserPostRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return $this->success(['access_token' => $token, 'token_type' => 'Bearer', 'role_type' => $user->role], 'User register successfully', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('Personal Access Token')->plainTextToken;

            DB::commit();
            return $this->success(['access_token' => $token, 'token_type' => 'Bearer', 'role_type' => $user->role], 'User login successfully', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success([], 'Logged out successfully', 200);
    }
}
