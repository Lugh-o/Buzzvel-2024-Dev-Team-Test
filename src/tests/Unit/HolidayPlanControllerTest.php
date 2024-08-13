<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\V1\HolidayPlanController;
use App\Http\Requests\V1\StoreHolidayPlanRequest;

class HolidayPlanControllerTest extends UnitTest
{
    public function test_index_returns_expected_response()
    {
        $controller = new HolidayPlanController();
        $response = $controller->index();
    
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $response->getData(true));
    }

    // public function test_show_returns_expected_response()
    // {

    // }

    public function test_store_creates_holiday_plan()
    {
        $storeRequest = new StoreHolidayPlanRequest(
            ["title" => "string",
                "description" => "string",
                "date" => "2024-08-13",
                "location" => "string",
                "participants" => [
                    ["name" => "string1"],
                    ["name" => "string2"]
                ]
            ]);

        $controller = new HolidayPlanController();
        $response = $controller->store($storeRequest);
        $this->assertEquals(201, $response->status());
    }

    // public function test_update_returns_expected_response()
    // {

    // }

    // public function test_destroy_returns_expected_response()
    // {

    // }
    
}
