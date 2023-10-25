<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Test class for CustomersController.
 * 
 * Extends PHPUnit\Framework\TestCase
 */
class CustomersControllerTest extends TestCase
{
    private Client $http;

    /**
     * Set up the HTTP client before each test.
     */
    protected function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost:26000/']);
    }

    // protected function tearDown(): void
    // {
    //     $this->http = null;
    // }

    /**
     * Asserts response header and body content-type is json
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
     * Test "get" endpoint returns status 200 response
     */
    public function get_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers');

        $this->assertJsonContent($response);
    }

    /**
     * Test "post" endpoint returns status 201 response
     */
    public function post_endpoint_returns_status_201_response(): void
    {
        $response = $this->http->request('POST', 'v1/customers', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "post" endpoint returns status 400 response
     */
    public function post_endpoint_returns_status_400_response(): void
    {
        $response = $this->http->request('POST', 'v1/customers', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "post" endpoint returns status 422 response
     */
    public function post_endpoint_returns_status_422_response(): void
    {
        $response = $this->http->request('POST', 'v1/customers', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "put" endpoint returns status 200 response
     */
    public function put_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('PUT', 'v1/customers/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "put" endpoint returns status 400 response
     */
    public function put_endpoint_returns_status_400_response(): void
    {
        $response = $this->http->request('PUT', 'v1/customers/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "put" endpoint returns status 404 response
     */
    public function put_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('PUT', 'v1/customers/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "put" endpoint returns status 422 response
     */
    public function put_endpoint_returns_status_422_response(): void
    {
        $response = $this->http->request('PUT', 'v1/customers/{id:[0-9]+}', [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            'body' => json_encode([]),
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "delete" endpoint returns status 200 response
     */
    public function delete_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "delete" endpoint returns status 404 response
     */
    public function delete_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "deactivate" endpoint returns status 200 response
     */
    public function deactivate_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers/deactivate/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "deactivate" endpoint returns status 404 response
     */
    public function deactivate_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers/deactivate/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "reactivate" endpoint returns status 200 response
     */
    public function reactivate_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers/reactivate/{id:[0-9]+}');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * Test "reactivate" endpoint returns status 404 response
     */
    public function reactivate_endpoint_returns_status_404_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers/reactivate/{id:[0-9]+}');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }
}
