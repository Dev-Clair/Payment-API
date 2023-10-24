<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Repositories\MethodsRepository;
use Payment_API\Entity\MethodsEntity;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\MethodsResponseTitle as ResponseTitle;
use Payment_API\Enums\MethodStatus;
use Payment_API\Utils\Validation\MethodsValidation;
use Monolog\Logger;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API endpoint for managing payment methods",
 * )
 */
class MethodsController implements ControllerInterface
{
    protected MethodsRepository $methodsRepository;
    protected Logger $logger;

    public function __construct(
        MethodsRepository $methodsRepository,
        Logger $logger
    ) {
        $this->methodsRepository = $methodsRepository;
        $this->logger = $logger;
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
        $methods = $this->methodsRepository->findAll();

        if (is_array($methods)) {
            return JSONResponse::response_200(ResponseTitle::GET, "Retrieved", $methods);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::GET]);

        return JSONResponse::response_500(ResponseTitle::GET, "Internal Server Error", "");
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
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
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
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            return JSONResponse::response_400(ResponseTitle::POST, "Bad Request", ["request body" => "Empty"]);
        }

        $methodsEntity = new MethodsValidation($requestBody);

        if (empty($methodsEntity->validationError)) {
            $this->methodsRepository->store($methodsEntity->getEntities());

            return JSONResponse::response_201(ResponseTitle::POST, "Created", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::POST, "Unprocessable Entity", $methodsEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::POST]);

        return JSONResponse::response_500(ResponseTitle::POST, "Internal Server Error", "");
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
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
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
        $requestAttribute = $args['id'];

        $validateResource = $this->methodsRepository->validate($requestAttribute);
        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::PUT, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            return JSONResponse::response_400(ResponseTitle::PUT, "Bad Request", ["request body" => "Empty"]);
        }
        $methodsEntity = $this->methodsRepository->findById($requestAttribute);

        $validateMethodEntity = new MethodsValidation($requestBody);

        if (empty($validateMethodEntity->validationError)) {
            $methodsEntity->setName($validateMethodEntity->validationResult['name']);

            $methodsEntity->setType($validateMethodEntity->validationResult['type']);

            $this->methodsRepository->update($methodsEntity);

            return JSONResponse::response_200(ResponseTitle::PUT, $requestAttribute . " Modified", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::PUT, "Unprocessable Entity", $validateMethodEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::PUT]);

        return JSONResponse::response_500(ResponseTitle::PUT, "Internal Server Error", "");
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
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
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
        $requestAttribute = $args['id'];

        $validateResource = $this->methodsRepository->validate($requestAttribute);

        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::DELETE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $methodsEntity = $this->methodsRepository->findById($requestAttribute);

            $this->methodsRepository->remove($methodsEntity);

            return JSONResponse::response_200(ResponseTitle::DELETE, $requestAttribute . " Deleted", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DELETE]);

        return JSONResponse::response_500(ResponseTitle::DELETE, "Internal Server Error", "");
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
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
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
        $requestAttribute = $args['id'];

        $validateResource = $this->methodsRepository->validate($requestAttribute);
        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::DEACTIVATE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $methodsEntity = $this->methodsRepository->findById($requestAttribute);

            $methodsEntity->setStatus(MethodStatus::INACTIVE);
            $this->methodsRepository->update($methodsEntity);

            return JSONResponse::response_200(ResponseTitle::DEACTIVATE, $requestAttribute . " Deactivated", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DEACTIVATE]);

        return JSONResponse::response_500(ResponseTitle::DEACTIVATE, "Internal Server Error", "");
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
        $requestAttribute = $args['id'];

        $validateResource = $this->methodsRepository->validate($requestAttribute);
        if ($validateResource) {
            return JSONResponse::response_404(ResponseTitle::REACTIVATE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $methodsEntity = $this->methodsRepository->findById($requestAttribute);

            $methodsEntity->setStatus(MethodStatus::ACTIVE);
            $this->methodsRepository->update($methodsEntity);

            return JSONResponse::response_200(ResponseTitle::REACTIVATE, $requestAttribute . " Reactivated", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::REACTIVATE]);

        return JSONResponse::response_500(ResponseTitle::REACTIVATE, "Internal Server Error", "");
    }
}
