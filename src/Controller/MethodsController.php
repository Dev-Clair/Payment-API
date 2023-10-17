<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Model\MethodsModel;
use Payment_API\Contracts\ControllerContract;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\MethodsResponseTitle;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API for managing customer payments",
 * )
 */
class MethodsController implements ControllerContract
{
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
        $resource = "";

        return JSONResponse::response_200(MethodsResponseTitle::GET, "SUCCESS: Retrieved", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::GET, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_201(MethodsResponseTitle::POST, "SUCCESS: Created", $resource);

        return JSONResponse::response_422(MethodsResponseTitle::POST, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::POST, "ERROR: Internal Server Error", $resource);
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
     *         description="Update method data",
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
        $resource = "";

        return JSONResponse::response_200(MethodsResponseTitle::PUT, "SUCCESS: Modified", $resource);

        return JSONResponse::response_404(MethodsResponseTitle::PUT, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_422(MethodsResponseTitle::PUT, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::PUT, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_200(MethodsResponseTitle::DELETE, "SUCCESS: Deleted", $resource);

        return JSONResponse::response_404(MethodsResponseTitle::DELETE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::DELETE, "ERROR: Internal Server Error", $resource);
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
    public function deactivate(Request $request, Response $response, array $args): Response
    {
        $resource = "";

        return JSONResponse::response_200(MethodsResponseTitle::DEACTIVATE, "SUCCESS: Deactivated", $resource);

        return JSONResponse::response_404(MethodsResponseTitle::DEACTIVATE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::DEACTIVATE, "ERROR: Internal Server Error", $resource);
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
    public function reactivate(Request $request, Response $response, array $args): Response
    {
        $resource = "";

        return JSONResponse::response_200(MethodsResponseTitle::REACTIVATE, "SUCCESS: Reactivated", $resource);

        return JSONResponse::response_404(MethodsResponseTitle::REACTIVATE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(MethodsResponseTitle::REACTIVATE, "ERROR: Internal Server Error", $resource);
    }
}
