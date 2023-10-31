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
    // protected function tearDown(): void
    // {
    //     $this->http = null;
    // }

    /**
     * provides id values for various test cases
     */
    public function idDataProvider(): array
    {
        return [['1'], ['2'], ['3'], ['4'], ['5']];
    }

    // ************************************* Methods Endpoint ***************************
    /**
     * @test
     */
    public function get_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('DELETE', 'v1/methods');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('PUT', 'v1/methods');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('GET', 'v1/methods/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function deactivate_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/deactivate/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function reactivate_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('DELETE', 'v1/methods/reactivate/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_methods_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('POST', 'v1/methods/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    // ************************************* Customers Endpoint ***************************
    /**
     * @test
     */
    public function get_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('DELETE', 'v1/customers');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('PUT', 'v1/customers');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('GET', 'v1/customers/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function deactivate_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/deactivate/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function reactivate_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('DELETE', 'v1/customers/reactivate/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_customers_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('POST', 'v1/customers/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    // ************************************* Payments Endpoint ***************************
    /**
     * @test
     */
    public function get_payments_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('DELETE', 'v1/payments');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function post_payments_endpoint_middleware_returns_status_405_response_for_invalid_request_method(): void
    {
        $response = $this->http->request('PUT', 'v1/payments');

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function put_payments_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('GET', 'v1/payments/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider idDataProvider
     */
    public function delete_payments_endpoint_middleware_returns_status_405_response_for_invalid_request_method($id): void
    {
        $response = $this->http->request('POST', 'v1/payments/' . $id);

        $this->assertSame(405, $response->getStatusCode());
    }
}
