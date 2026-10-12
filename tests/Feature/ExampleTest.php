<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the products page returns a successful response.
     */
    public function test_the_products_page_returns_a_successful_response(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }
}