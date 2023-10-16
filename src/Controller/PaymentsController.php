<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Model\PaymentsModel;
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
class PaymentsController implements ControllerContract
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
     *     path="/v1/payments",
     *     tags={"payments"},
     *     summary="Get a list of all payments",
     *     description="Returns a list of payments records.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentListResponse")
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
     *     path="/v1/payments",
     *     tags={"payments"},
     *     summary="Create a new payment record",
     *     description="Creates a new payment record with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payment data",
     *         @OA\JsonContent(ref="#/components/schemas/NewMethodData")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
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
     *     path="/v1/payments/{id:[0-9]+}",
     *     tags={"payments"},
     *     summary="Update a payment record",
     *     description="Update an entire payment record based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment record to update.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Update of payment data",
     *         @OA\JsonContent(ref="#/components/schemas/UpdatedMethodData")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated successfully",
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
     *     path="/v1/payments/{id:[0-9]+}",
     *     tags={"payments"},
     *     summary="Delete a payment record",
     *     description="Deletes a payment record based on supplied ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment record to delete.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment deleted successfully",
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
}
