<?php

namespace Tests\Unit;

use Tests\TestCase;

class UnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
    }
}
