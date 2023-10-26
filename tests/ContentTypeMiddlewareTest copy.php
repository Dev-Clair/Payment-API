<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Test class for ContentTypeMiddlewareTest
 * 
 * Extends PHPUnit\Framework\TestCase
 */
class ContentTypeMiddlewareTest extends TestCase
{
    private Client $http;

    /**
     * Set up the HTTP client before each test case
     */
    protected function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost:26000/']);
    }

    /**
     * Cleans up the HTTP client after each test case
     */
    protected function tearDown(): void
    {
        $this->http = null;
    }

    /**
     * provides method and endpoint data for various test cases
     */
    public function invalidContentTypes(): array
    {
        return [
            ['POST', 'v1/methods'],
            ['PUT', 'v1/methods/1'],
            ['POST', 'v1/customers'],
            ['PUT', 'v1/customers/1'],
            ['POST', 'v1/payments'],
            ['PUT', 'v1/payments/1'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidContentTypes
     */
    public function content_type_middleware_returns_bad_request_for_invalid_content_type($method, $endpoint): void
    {
        $response = $this->http->request($method, $endpoint, [
            'headers' => ['Content-Type' => 'text/html; charset=UTF-8']
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
