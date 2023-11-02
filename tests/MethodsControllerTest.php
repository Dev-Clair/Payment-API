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
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.CfNad_NDnSotdIVIvZO-YEf58qe88_Na1ryAmRaCdKg';

        $this->http = new Client([
            'base_uri' => 'http://localhost:26000/',
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Cleans up the HTTP client after each test case
     */
    // protected function tearDown(): void
    // {
    //     $this->http = null;
    // }

    /**
     * data provider: provides valid id values for various test cases
     */
    public static function validIdDataProvider(): array
    {
        return [[1]];
    }

    /**
     * helper method: validates the content type and format of the HTTP response
     */
    private function assertJsonContent($response): void
    {
        $contentType = $response->getHeaders()["Content-Type"][0];

        // asserts response header is Json
        $this->assertSame("application/json; charset=UTF-8", $contentType);

        // asserts response body is Json
        $this->assertJson($response->getBody()->getContents());
    }

    /**
     * @test
     */
    public function get_endpoint_returns_status_200_response(): void
    {
        $response = $this->http->request('GET', 'v1/methods');

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function post_endpoint_returns_status_201_response(): void
    {
        $response = $this->http->request('POST', 'v1/methods', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'method_name' => 'paypal',
                'method_type' => 'card'
            ]),
        ]);

        $this->assertSame(201, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function put_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('PUT', 'v1/methods/' . $id, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'method_name' => 'stripepay',
                'method_type' => 'card'
            ]),
        ]);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function deactivate_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('GET', 'v1/methods/deactivate/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function reactivate_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('GET', 'v1/methods/reactivate/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function delete_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }
}
