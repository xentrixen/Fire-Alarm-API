<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Citizen;
use App\Admin;
use App\Notifications\Verify;
use Illuminate\Support\Facades\Hash;
use App\LoginHistory;
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
                    if(Citizen::where('email', $value)->where('active', 1)->count() > 0) {
                        $fail('The email has already been taken.');
                    }
                }
            ],
            'password' => 'required|string|min:8|confirmed'
        ]);

        $citizen = Citizen::where('email', $request->email)->first();
        if(!$citizen) {
            $citizen = new Citizen([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activation_token' => str_random(60)
            ]);
        } else {
            $citizen->name = $request->name;
            $citizen->email = $request->email;
            $citizen->password = Hash::make($request->password);
            $citizen->activation_token = str_random(60);
        }
        
        DB::beginTransaction();
        try {
            $citizen->save();
            $citizen->notify(new Verify($citizen));

            DB::commit();
            return response()->json(['message' => 'Please verify your account by email to continue']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function verify($token)
    {
        $citizen = Citizen::where('activation_token', $token)->first();
        if (!$citizen) {
            if(!request()->expectsJson()) {
                return view('verify', [
                    'status' => 'Error',
                    'message' => 'This activation token is invalid or has expired'
                ]);
            }
            return response()->json(['message' => 'This activation token is invalid.'], 404);
        }

        $citizen->active = true;
        $citizen->activation_token = '';

        if($citizen->save()) {
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
            'type' => 'required|in:admin,citizen'
        ]);

        if(!$validator->passes()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(env('PASSPORT_LOGIN_ENDPOINT'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'provider' => $request->type
                ]
            ]);

            $response =  $response->getBody();

            if($request->type == 'citizen') {
                $access_token = json_decode($response, true)['access_token'];
                $request = $http->get(env("APP_URL")."/auth/user", [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '.$access_token,
                    ]
                ]);
                
                $id = json_decode($request->getBody(), true)['id'];
                LoginHistory::create([
                    'citizen_id' => $id
                ]);
            }

            return $response;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json(['message' => 'Invalid Request. Please enter a username or a password.'], $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json(['message' => 'Your credentials are incorrect. Please try again'], $e->getCode());
            }
            
            return response()->json(['message' => 'Something went wrong on the server.'], $e->getCode());
        }
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