<?php

namespace Tests\Feature;

use Tests\TestCase;

class FeatureTest extends TestCase
{
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

}
