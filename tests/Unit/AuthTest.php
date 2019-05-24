<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Citizen;
use App\LoginHistory;

class AuthTest extends TestCase
{
    public function testRegistrationAndVerification()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/auth/signup', [
            'name' => 'Jeric Cordova',
            'email' => 'unexisting-email@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Please verify your account by email to continue'
            ]);

        $citizen = Citizen::where('email', 'unexisting-email@gmail.com')->first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', '/auth/verify/'.$citizen->activation_token);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Account verified successfully']);

        $citizen->forceDelete();
    }

    public function testAdminLogin()
    {
        $user = factory(\App\Admin::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'type' => 'admin'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);

        $user->delete();
    }

    public function testCitizenLogin()
    {
        $user = factory(\App\Citizen::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'type' => 'citizen'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);

        LoginHistory::where('citizen_id', $user->id)->delete();
        $user->forceDelete();
    }

    public function testFirePersonnelLogin()
    {
        $user = factory(\App\FireStation::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/auth/login', [
            'username' => $user->username,
            'password' => 'password',
            'type' => 'fire-personnel'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);

        $user->delete();
    }
}
