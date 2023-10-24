<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Interface\SmsServiceInterface;
use Payment_API\Services\SmsService;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Entity\CustomersEntity;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\CustomersResponseTitle as ResponseTitle;
use Payment_API\Enums\CustomerStatus;
use Payment_API\Utils\Validation\CustomersValidation;
use Monolog\Logger;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API endpoint for managing customer accounts",
 * )
 */
class CustomersController implements ControllerInterface
{
    protected SmsServiceInterface $smsService;
    protected CustomersRepository $customersRepository;
    protected Logger $logger;

    public function __construct(
        SmsServiceInterface $smsService,
        CustomersRepository $customersRepository,
        Logger $logger
    ) {
        $this->smsService = $smsService;
        $this->customersRepository = $customersRepository;
        $this->logger = $logger;
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
        $customers = $this->customersRepository->findAll();

        if (is_array($customers)) {
            return JSONResponse::response_200(ResponseTitle::GET, "Retrieved", $customers);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::GET]);

        return JSONResponse::response_500(ResponseTitle::GET, "Internal Server Error", "");
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

        $customersEntity = new CustomersValidation($requestBody);

        if (empty($customersEntity->validationError)) {
            $this->customersRepository->store($customersEntity->getEntities());

            return JSONResponse::response_201(ResponseTitle::POST, "Created", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::POST, "Unprocessable Entity", $customersEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::POST]);

        return JSONResponse::response_500(ResponseTitle::POST, "Internal Server Error", "");
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
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
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
    public function put(Request $request, Response $response, array $args): Response
    {
        $requestAttribute = $args['id'];

        $validateResource = $this->customersRepository->validate($requestAttribute);
        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::PUT, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            return JSONResponse::response_400(ResponseTitle::PUT, "Bad Request", ["request body" => "Empty"]);
        }

        $customersEntity = $this->customersRepository->findById($requestAttribute);

        $validateCustomerEntity = new CustomersValidation($requestBody);

        if (empty($validateCustomerEntity->validationError)) {
            $customersEntity->setName($validateCustomerEntity->validationResult['name']);

            $customersEntity->setEmail($validateCustomerEntity->validationResult['email']);

            $customersEntity->setPassword($validateCustomerEntity->validationResult['password']);

            $customersEntity->setAddress($validateCustomerEntity->validationResult['address']);

            $this->customersRepository->update($customersEntity);

            return JSONResponse::response_200(ResponseTitle::PUT, $requestAttribute . " Modified", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::PUT, "Unprocessable Entity", $validateCustomerEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::PUT]);

        return JSONResponse::response_500(ResponseTitle::PUT, "Internal Server Error", "");
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

        $validateResource = $this->customersRepository->validate($requestAttribute);

        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::DELETE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $customersEntity = $this->customersRepository->findById($requestAttribute);

            $this->customersRepository->remove($customersEntity);

            return JSONResponse::response_200(ResponseTitle::DELETE, $requestAttribute . " Deleted", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DELETE]);

        return JSONResponse::response_500(ResponseTitle::DELETE, "Internal Server Error", "");
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

        $validateResource = $this->customersRepository->validate($requestAttribute);
        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::DEACTIVATE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $customersEntity = $this->customersRepository->findById($requestAttribute);

            $customersEntity->setStatus(CustomerStatus::INACTIVE);
            $this->customersRepository->update($customersEntity);

            return JSONResponse::response_200(ResponseTitle::DEACTIVATE, $requestAttribute . " Deactivated", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DEACTIVATE]);

        return JSONResponse::response_500(ResponseTitle::DEACTIVATE, "Internal Server Error", "");
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
    public function reactivate(Request $request, Response $response, array $args): Response
    {
        $requestAttribute = $args['id'];

        $validateResource = $this->customersRepository->validate($requestAttribute);
        if ($validateResource) {
            return JSONResponse::response_404(ResponseTitle::REACTIVATE, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $customersEntity = $this->customersRepository->findById($requestAttribute);

            $customersEntity->setStatus(CustomerStatus::ACTIVE);
            $this->customersRepository->update($customersEntity);

            return JSONResponse::response_200(ResponseTitle::REACTIVATE, $requestAttribute . " Reactivated", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::REACTIVATE]);

        return JSONResponse::response_500(ResponseTitle::REACTIVATE, "Internal Server Error", "");
    }
}
