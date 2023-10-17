<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Model\PaymentsModel;
use Payment_API\Contracts\ControllerContract;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\PaymentsResponseTitle;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API for managing customer payments",
 * )
 */
class PaymentsController implements ControllerContract
{
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
        $resource = "";

        return JSONResponse::response_200(PaymentsResponseTitle::GET, "SUCCESS: Retrieved", $resource);

        return JSONResponse::response_500(PaymentsResponseTitle::GET, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_201(PaymentsResponseTitle::POST, "SUCCESS: Created", $resource);

        return JSONResponse::response_422(PaymentsResponseTitle::POST, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(PaymentsResponseTitle::POST, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_200(PaymentsResponseTitle::PUT, "SUCCESS: Modified", $resource);

        return JSONResponse::response_404(PaymentsResponseTitle::PUT, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_422(PaymentsResponseTitle::PUT, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(PaymentsResponseTitle::PUT, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_200(PaymentsResponseTitle::DELETE, "SUCCESS: Deleted", $resource);

        return JSONResponse::response_404(PaymentsResponseTitle::DELETE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(PaymentsResponseTitle::DELETE, "ERROR: Internal Server Error", $resource);
    }
}
