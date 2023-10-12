<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Model\MethodsModel;
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
class MethodsController implements ControllerContract
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
     *     path="/v1/methods",
     *     tags={"methods"},
     *     summary="Get a list of available payment methods",
     *     description="Returns a list of payment methods.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/MethodListResponse")
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
     *     path="/v1/methods",
     *     tags={"methods"},
     *     summary="Create a new payment method",
     *     description="Creates a new payment method record with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Method data",
     *         @OA\JsonContent(ref="#/components/schemas/NewMethodData")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Method created successfully",
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
     *     path="/v1/methods/{id:[0-9]+}",
     *     tags={"methods"},
     *     summary="Update a payment method",
     *     description="Update an entire payment method based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment method to update.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated method data",
     *         @OA\JsonContent(ref="#/components/schemas/UpdatedMethodData")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Method updated successfully",
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
     *     path="/v1/methods/{id:[0-9]+}",
     *     tags={"methods"},
     *     summary="Delete a payment method record",
     *     description="Deletes a payment method record based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment method to delete.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Method deleted successfully",
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
     *     path="/v1/methods/deactivate/{id:[0-9]+}",
     *     tags={"methods"},
     *     summary="Retrieves a single payment method record",
     *     description="Retrieves record of deactivated payment method based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of payment method record.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Method retrieved successfully",
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
     *     path="/v1/methods/reactivate/{id:[0-9]+}",
     *     tags={"methods"},
     *     summary="Retrieves a single payment method record",
     *     description="Retrieves record of reactivated payment method based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of payment method record.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Method retrieved successfully",
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
