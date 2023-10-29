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
     * data provider: provides method and endpoint values for various test cases
     */
    public function valid_request_method_and_endpoints(): array
    {
        return [
            ['POST', 'v1/methods',],
            ['PUT', 'v1/methods/1'],
            ['POST', 'v1/customers'],
            ['PUT', 'v1/customers/1'],
            ['POST', 'v1/payments'],
            ['PUT', 'v1/payments/1'],
        ];
    }

    /**
     * data provider: provides content type values for various test cases
     */
    public function invalid_content_type(): array
    {
        return [
            [[
                'headers' => ['Content-Type' => 'text/html; charset=UTF-8']
            ]]
        ];
    }

    /**
     * @test
     * @dataProvider valid_request_method_and_endpoints
     * @dataProvider invalid_content_types
     */
    public function content_type_middleware_returns_bad_request_for_invalid_content_type($method, $endpoint, $content_type): void
    {
        $response = $this->http->request($method, $endpoint, $content_type);

        $this->assertSame(400, $response->getStatusCode());
    }
}
