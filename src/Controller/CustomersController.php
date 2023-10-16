<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Model\CustomersModel;
use OpenApi\Annotations as OA;
use Payment_API\Contracts\ControllerContract;
use Payment_API\Trait\Response_200_Trait as Response_200;
use Payment_API\Trait\Response_201_Trait as Response_201;
use Payment_API\Trait\Response_400_Trait as Response_400;
use Payment_API\Trait\Response_404_Trait as Response_404;
use Payment_API\Trait\Response_422_Trait as Response_422;
use Payment_API\Trait\Response_500_Trait as Response_500;


/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API for managing customer payments",
 * )
 */
class CustomersController implements ControllerContract
{
    use Response_200;
    use Response_201;
    use Response_400;
    use Response_404;
    use Response_422;
    use Response_500;

    public function __construct()
    {
    }


    /**
     * @OA\Get(
     *     path="/v1/customers",
     *     tags={"customers"},
     *     summary="Get a list of available customers",
     *     description="Returns a list of customers.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerListResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function get(Request $request, Response $response, array $args): Response
    {
    }


    /**
     * @OA\Post(
     *     path="/v1/customers",
     *     tags={"customers"},
     *     summary="Create a new customer",
     *     description="Creates a new customer record with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Customer data",
     *         @OA\JsonContent(ref="#/components/schemas/NewCustomerData")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function post(Request $request, Response $response, array $args): Response
    {
    }


    /**
     * @OA\Put(
     *     path="/v1/customers/{id:[0-9]+}",
     *     tags={"customers"},
     *     summary="Update a customer",
     *     description="Update an entire customer record based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the customer to update.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Update customer data",
     *         @OA\JsonContent(ref="#/components/schemas/UpdatedCustomerData")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function put(Request $request, Response $response, array $args): Response
    {
    }


    /**
     * @OA\Delete(
     *     path="/v1/customers/{id:[0-9]+}",
     *     tags={"customers"},
     *     summary="Delete a customer",
     *     description="Deletes a customer record based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the customer to delete.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
    }


    /**
     * @OA\Get(
     *     path="/v1/customers/deactivate/{id:[0-9]+}",
     *     tags={"customers"},
     *     summary="Retrieves a single customer record",
     *     description="Retrieves record of deactivated customer account based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of customer record.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function deactivate(Request $request, Response $response, array $args): Response
    {
    }


    /**
     * @OA\Get(
     *     path="/v1/customers/reactivate/{id:[0-9]+}",
     *     tags={"customers"},
     *     summary="Retrieves a single customer record",
     *     description="Retrieves a single record of reactivated customer account based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of customer record.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
     */
    public function reactivate(Request $request, Response $response, array $args): Response
    {
    }
}
