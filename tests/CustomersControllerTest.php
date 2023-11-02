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
        $response = $this->http->request('GET', 'v1/customers');

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     */
    public function post_endpoint_returns_status_201_response(): void
    {
        $response = $this->http->request('POST', 'v1/customers', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'customer_name' => 'wendy uche',
                'customer_email' => 'wendy.uche@gmail.com',
                'customer_phone' => "09150515435",
                'customer_password' => 'wendyuche99',
                'confirm_customer_password' => 'wendyuche99',
                'customer_address' => '2 Aduragbemi Street, Ontario, Canada',
                'customer_type' => 'individual'
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
        $response = $this->http->request('PUT', 'v1/customers/' . $id, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'customer_name' => 'aniogbu samuel',
                'customer_email' => 'aniogbu.samuel@yahoo.com',
                'customer_phone' => "08133893441",
                'customer_password' => 'claircorp99',
                'confirm_customer_password' => 'claircorp99',
                'customer_address' => '5b Mumuni Street, Lagos, Nigeria',
                'customer_type' => 'individual'
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
        $response = $this->http->request('GET', 'v1/customers/deactivate/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function reactivate_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/reactivate/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }

    /**
     * @test
     * @dataProvider validIdDataProvider
     */
    public function delete_endpoint_returns_status_200_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/' . $id);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonContent($response);
    }
}
