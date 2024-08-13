<?php

namespace Tests\Feature;

use App\Models\HolidayPlan;
use App\Models\User;

class CrudTest extends FeatureTest
{

    public function test_get_all_holiday_plans(){

        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/holidayplans');

        $response->assertStatus(200);
    }

    public function test_get_all_holiday_plans_without_auth(){   
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1/holidayplans');
        $response->assertStatus(401);
    }

    public function test_get_single_holiday_plan(){

        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/holidayplans/1');

        $response->assertStatus(200);
    }

    public function test_get_single_holiday_plan_without_auth(){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1/holidayplans/1');

        $response->assertStatus(401);
    }

    public function test_get_single_holiday_plan_with_invalid_id(){

        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/holidayplans/1000');

        $response->assertStatus(404);
    }

    public function test_create_holiday_plan(){
        $user = User::factory()->create();

        $holidayPlan = HolidayPlan::factory()->raw();
        $holidayPlan["participants"] = [
            ["name" => "participant1"],
            ["name" => "participant2"]
        ];

        $token = $user->createToken('TestToken')->plainTextToken;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/holidayplans', $holidayPlan);
        $response->assertStatus(201);
    }

    public function test_create_holiday_plan_without_auth(){

        $holidayPlan = HolidayPlan::factory()->raw();
        $holidayPlan["participants"] = [
            ["name" => "participant1"],
            ["name" => "participant2"]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/v1/holidayplans', $holidayPlan);
        $response->assertStatus(401);
    }

    public function test_create_holiday_plan_with_missing_fields(){
        $user = User::factory()->create();

        $holidayPlan = [
            "title" => "title",
            "description" => "description",
            // "date" => "1999/12/12",
            "location" => "location",
            "participants" => []
        ];
        $token = $user->createToken('TestToken')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->post('/api/v1/holidayplans', $holidayPlan);
        $response->assertStatus(422);
    }

    public function test_create_holiday_plan_with_invalid_fields(){
        $user = User::factory()->create();

        $holidayPlan = [
            "title" => "title",
            "description" => "description",
            "date" => "1999/30/12",//invalid date
            "location" => "location",
            "participants" => [
                ["name" => "participant1"],
                ["name" => "participant2"]
            ]
        ];

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->post('/api/v1/holidayplans', $holidayPlan);
        $response->assertStatus(422);
    }

    public function test_update_holiday_plan(){
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $holidayPlan = [
            "title" => "titleUpdated",
            "description" => "descriptionUpdated",
            "date" => "1999/12/12",
            "location" => "locationUpdated",
            "participants" => [
                ["name" => "participant1"],
                ["name" => "participant2"],
                ["name" => "participant3"],
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put('/api/v1/holidayplans/1', $holidayPlan);

        $response->assertStatus(200);
    }

    public function test_update_holiday_plan_without_auth(){
        $holidayPlan = [
            "title" => "titleUpdated",
            "description" => "descriptionUpdated",
            "date" => "1999/12/12",
            "location" => "locationUpdated",
            "participants" => [
                ["name" => "participant1"],
                ["name" => "participant2"],
                ["name" => "participant3"],
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->put('/api/v1/holidayplans/1', $holidayPlan);

        $response->assertStatus(401);
    }

    public function test_update_holiday_plan_with_missing_fields(){
        $user = User::factory()->create();

        $holidayPlan = [
            "title" => "titleUpdated",
            "description" => "descriptionUpdated",
            // "date" => "1999/12/12",
            "location" => "locationUpdated",
            "participants" => []
        ];

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put('/api/v1/holidayplans/1', $holidayPlan);

        $response->assertStatus(422);
    }

    public function test_update_holiday_plan_with_invalid_values(){
        $user = User::factory()->create();

        $holidayPlan = [
            "title" => "titleUpdated",
            "description" => "descriptionUpdated",
            "date" => "1999/50/12",
            "location" => "locationUpdated",
            "participants" => [
                ["name" => "participant1"],
                ["name" => "participant2"],
                ["name" => "participant3"],
            ]
        ];

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put('/api/v1/holidayplans/1', $holidayPlan);

        $response->assertStatus(422);
    }

    public function test_delete_holiday_plan(){
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete('/api/v1/holidayplans/1');

        $response->assertStatus(200);
    }

    public function test_delete_holiday_plan_without_auth(){

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->delete('/api/v1/holidayplans/1');

        $response->assertStatus(401);
    }

    public function test_get_holiday_plan_pdf(){
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get('/api/v1/holidayplans/1/pdf');

        $response->assertStatus(200);
    }

    public function test_get_holiday_plan_pdf_without_auth(){
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('/api/v1/holidayplans/1/pdf');

        $response->assertStatus(401);
    }

    public function test_get_holiday_plan_pdf_with_invalid_id(){
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get('/api/v1/holidayplans/999/pdf');

        $response->assertStatus(404);
    }

}
