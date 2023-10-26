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
     * Helper method: validates the content type and format of the HTTP response
     */
    private function assertJsonContent($response): void
    {
        $contentType = $response->getHeaders()["Content-Type"][0];

        // assert response header is Json
        $this->assertEquals("application/json; charset=UTF-8", $contentType);

        // assert response body is Json
        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * @test
     */
    public function get_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods');

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function get_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods');

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function post_endpoint_middleware_returns_status_400_response(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'text/html; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_endpoint_returns_status_400_response(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function post_endpoint_returns_status_422_response(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function post_endpoint_returns_status_201_response(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function put_endpoint_returns_status_400_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function put_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function put_endpoint_returns_status_422_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function put_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function deactivate_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods/deactivate/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function deactivate_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods/deactivate/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function reactivate_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods/reactivate/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function reactivate_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods/reactivate/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function delete_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function delete_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }
}
