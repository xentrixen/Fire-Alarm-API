<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\User;
use App\Admin;
use App\Notifications\Verify;
use DB;
use Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                function ($attribute, $value, $fail) {
                    if(User::where('email', $value)->where('active', 1)->count() > 0) {
                        $fail('The email has already been taken.');
                    }
                }
            ],
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user) {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'activation_token' => str_random(60)
            ]);
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->activation_token = str_random(60);
        }
        
        DB::beginTransaction();
        try {
            $user->save();
            $user->notify(new Verify($user));

            DB::commit();
            return response()->json(['message' => 'Please verify your account by email to continue']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function verify($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            if(!request()->expectsJson()) {
                return view('verify', [
                    'status' => 'Error',
                    'message' => 'This activation token is invalid or has expired'
                ]);
            }
            return response()->json(['message' => 'This activation token is invalid.'], 404);
        }

        $user->active = true;
        $user->activation_token = '';

        if($user->save()) {
            if(!request()->expectsJson()) {
                return view('verify', [
                    'status' => 'Success',
                    'message' => 'Account verified successfully'
                ]);
            }
            return response()->json(['message' => 'Account verified successfully']);
        }
        return response()->json(['message' => 'An Error Has Occurred'], 500);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required|in:admin,user'
        ]);

        if(!$validator->passes()) {
            return response()->json(['message' => 'Your credentials are incorrect. Please try again'], 401);
        }

        if($request->type == 'user') {
            $user = User::where('email', $request->email)
                ->where('active', 1)
                ->where('deleted_at', null)
                ->first();
        } else if($request->type == 'admin') {
            $user = Admin::where('email', $request->email)->first();
        }

        if($user) {
            if(bcrpyt($request->password) != $user->password) {
                return 'asd';
                Auth::login($user);
            } else {
                return response()->json(['message' => 'Your 2 are incorrect. Please try again'], 401);
            }
        } else {
            return response()->json(['message' => 'Your 1 are incorrect. Please try again'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        
        if($token->save()) {
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }
        return response()->json(['message' => 'An error has occurred'], 500);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logout successful']);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}