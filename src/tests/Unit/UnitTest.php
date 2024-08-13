<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;

class UnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}
