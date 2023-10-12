<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Test class for MethodsController.
 * 
 * Extends PHPUnit\Framework\TestCase
 */
class MethodsControllerTest extends TestCase
{
    private Client $http;

    /**
     * Set up the HTTP client before each test.
     */
    protected function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost:8888/']);
    }

    // protected function tearDown(): void
    // {
    //     $this->http = null;
    // }

    /**
     * Test the "get" endpoint.
     */
    public function testGet(): void
    {
        $response = $this->http->request('GET', 'v1/methods');

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * Test the "post" endpoint.
     */
    public function testPost(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * Test the "put" endpoint.
     */
    public function testPut(): void
    {
        $response = $this->http->request('PUT', 'v1/methods/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * Test the "delete" endpoint.
     */
    public function testDelete(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/{id:[0-9]+}');

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * Test the "getDeactivate" endpoint.
     */
    public function testGetDeactivate(): void
    {
        $response = $this->http->request('GET', 'v1/methods/deactivate/{id:[0-9]+}' . rand(1, 5));

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * Test the "getReactivate" endpoint.
     */
    public function testGetReactivate(): void
    {
        $response = $this->http->request('GET', 'v1/methods/reactivate/{id:[0-9]+}');

        $contentType = $response->getHeaders()["Content-Type"][0];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        $this->assertJson($response->getBody()->getContents());
    }
}
