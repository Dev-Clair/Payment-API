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

    /**
     * Cleans up the HTTP client after each test case
     */
    protected function tearDown(): void
    {
        $this->http = null;
    }

    /**
     * provides valid id values for various test cases
     */
    public function validIdDataProvider(): array
    {
        return ['1', '2', '3', '4', '5'];
    }

    /**
     * provides invalid id values for various test cases
     */
    public function invalidIdDataProvider(): array
    {
        return ['a', 'b', 'c', 'd', 'e'];
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
    public function get_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/customers');

        $this->assertJsonContent($response);
    }

    /**
     * @test
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
     * @test
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
     * @test
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
     * @test
     * @dataProvider validIdDataProvider
     */
    public function put_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('PUT', 'v1/customers/' . $id, [
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
     * @dataProvider validIdDataProvider
     */
    public function put_endpoint_returns_status_400_response($id): void
    {
        $response = $this->http->request('PUT', 'v1/customers/' . $id, [
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
     * @dataProvider invalidIdDataProvider
     */
    public function put_endpoint_returns_status_404_response($id): void
    {
        $response = $this->http->request('PUT', 'v1/customers/' . $id, [
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
     * @dataProvider validIdDataProvider
     */
    public function put_endpoint_returns_status_422_response($id): void
    {
        $response = $this->http->request('PUT', 'v1/customers/' . $id, [
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
     * @dataProvider validIdDataProvider
     */
    public function deactivate_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/deactivate/' . $id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider invalidIdDataProvider
     */
    public function deactivate_endpoint_returns_status_404_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/deactivate/' . $id);

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function reactivate_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/reactivate/' . $id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider invalidIdDataProvider
     */
    public function reactivate_endpoint_returns_status_404_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/reactivate/' . $id);

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function delete_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/' . $id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider invalidIdDataProvider
     */
    public function delete_endpoint_returns_status_404_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/' . $id);

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJsonContent($response);
    }
}
