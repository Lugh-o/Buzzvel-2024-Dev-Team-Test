<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends FeatureTest
{

    public function test_user_register_with_valid_credentials()
    { 
        $credentials = [
            "name" => "testName",
            "email" => "test@email.com",
            "password" => "testPassword0!",
        ];

        $response = $this->post('/register', $credentials);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'test@email.com'
        ]);
    }

    public function test_user_register_that_already_exists()
    { 
        $credentials = [
            "name" => "testName",
            "email" => "test@email.com",
            "password" => "testPassword0!"
        ];

        $this->post('/register', $credentials);
        $response = $this->post('/register', $credentials);

        $response->assertStatus(401);
        $this->assertDatabaseHas('users', [
            'email' => 'test@email.com'
        ]);
    }

    public function test_user_register_with_missing_fields()
    { 

        $credentials = [ //name missing
            "email" => "test@email.com",
            "password" => "testPassword"
        ];

        $response = $this->post('/register', $credentials);
        
        $response->assertStatus(401);
    }

    public function test_user_register_with_invalid_value()
    { 

        $credentials = [ 
            "name" => "testName",
            "email" => "invalidEmail",
            "password" => "testPassword"
        ];

        $response = $this->post('/register', $credentials);
        
        $response->assertStatus(401);
    }

    public function test_user_login_with_valid_credentials()
    {

        User::create([
            "name" => "testName",
            "email" => "test@email.com",
            "password" => Hash::make("testPassword")
        ]);

        $credentials = [ 
            "email" => "test@email.com",
            "password" => "testPassword"
        ];

        $response = $this->post('/login', $credentials);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'test@email.com'
        ]);

    }

    public function test_user_login_with_invalid_credentials()
    {

        User::create([
            "name" => "testName",
            "email" => "test@email.com",
            "password" => Hash::make("testPassword")
        ]);

        $credentials = [ 
            "email" => "invalidEmail",
            "password" => "testPassword"
        ];

        $response = $this->post('/login', $credentials);

        $response->assertStatus(401);
    }

    public function test_user_login_with_missing_fields()
    {

        User::create([
            "name" => "testName",
            "email" => "test@email.com",
            "password" => Hash::make("testPassword")
        ]);

        $credentials = [ 
            "email" => "invalidEmail"
        ];

        $response = $this->post('/login', $credentials);

        $response->assertStatus(401);
    }

}
