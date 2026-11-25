<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_up_endpoint_works(): void
    {
        $this->get('/up')->assertOk();
    }
}