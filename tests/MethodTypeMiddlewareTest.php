<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Test class for MethodTypeMiddlewareTest
 * 
 * Extends PHPUnit\Framework\TestCase
 */
class MethodTypeMiddlewareTest extends TestCase
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
     * Data provider for different endpoints $id
     */
    public function idDataProvider(): array
    {
        return ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
    }

    // ************************************* Methods Endpoint ***************************
    /**
     * @test
     */
    public function get_method_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_method_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('PUT', 'v1/methods');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_method_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('GET', 'v1/methods/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function deactivate_method_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/deactivate/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function reactivate_method_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/reactivate/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_method_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('POST', 'v1/methods/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    // ************************************* Customers Endpoint ***************************
    /**
     * @test
     */
    public function get_customer_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/customers');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_customer_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('PUT', 'v1/customers');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_customer_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function deactivate_customer_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/deactivate/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function reactivate_customer_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/reactivate/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_customer_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('POST', 'v1/customers/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    // ************************************* Payments Endpoint ***************************
    /**
     * @test
     */
    public function get_payment_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('DELETE', 'v1/payments');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_payment_endpoint_middleware_returns_status_405_response(): void
    {
        $response = $this->http->request('PUT', 'v1/payments');

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_payment_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('GET', 'v1/payments/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_payment_endpoint_middleware_returns_status_405_response($id): void
    {
        $response = $this->http->request('POST', 'v1/payments/' . $id);

        $this->assertEquals(405, $response->getStatusCode());
    }
}
