<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Interface\EmailValidationServiceInterface;
use Payment_API\Interface\SmsServiceInterface;
use Payment_API\Services\EmailValidationService;
use Payment_API\Services\SmsService;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Entity\CustomersEntity;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\CustomersResponseTitle;
use Monolog\Logger;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API for managing customer payments",
 * )
 */
class CustomersController implements ControllerInterface
{
    protected EmailValidationService $emailValidationService;

    protected SmsService $smsService;

    public function __construct(

        protected CustomersEntity $customersEntity,

        protected CustomersRepository $customersRepository,

        protected Logger $logger,

        EmailValidationServiceInterface $emailValidationService,

        SmsServiceInterface $smsService
    ) {
        $this->emailValidationService = $emailValidationService;
        $this->smsService = $smsService;
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
        $resource = $this->customersRepository->findAll();

        if (is_array($resource)) {
            return JSONResponse::response_200(CustomersResponseTitle::GET, "SUCCESS: Retrieved", $resource);
        }

        $this->logger->emergency('No Resource Found for Request', ['Internal Server Error' => $resource]);
        return JSONResponse::response_500(CustomersResponseTitle::GET, "ERROR: Internal Server Error", $resource);
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
    public function post(Request $request, Response $response, array $args): Response
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            $this->logger->alert('Bad Request', ['Request Body' => 'Empty']);
            return JSONResponse::response_400(CustomersResponseTitle::POST, "ERROR: Bad Request", $requestBody);
        }

        $resource = "";

        return JSONResponse::response_201(CustomersResponseTitle::POST, "SUCCESS: Created", $resource);

        return JSONResponse::response_422(CustomersResponseTitle::POST, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(CustomersResponseTitle::POST, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_200(CustomersResponseTitle::PUT, "SUCCESS: Modified", $resource);

        return JSONResponse::response_404(CustomersResponseTitle::PUT, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_422(CustomersResponseTitle::PUT, "ERROR: Unprocessable Entity", $resource);

        return JSONResponse::response_500(CustomersResponseTitle::PUT, "ERROR: Internal Server Error", $resource);
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
        $resource = "";

        return JSONResponse::response_200(CustomersResponseTitle::DELETE, "SUCCESS: Deleted", $resource);

        return JSONResponse::response_404(CustomersResponseTitle::DELETE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(CustomersResponseTitle::DELETE, "ERROR: Internal Server Error", $resource);
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

        return JSONResponse::response_200(CustomersResponseTitle::DEACTIVATE, "SUCCESS: Deactivated", $resource);

        return JSONResponse::response_404(CustomersResponseTitle::DEACTIVATE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(CustomersResponseTitle::DEACTIVATE, "ERROR: Internal Server Error", $resource);
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

        return JSONResponse::response_200(CustomersResponseTitle::REACTIVATE, "SUCCESS: Reactivated", $resource);

        return JSONResponse::response_404(CustomersResponseTitle::REACTIVATE, "ERROR: Resource Not Found", $resource);

        return JSONResponse::response_500(CustomersResponseTitle::REACTIVATE, "ERROR: Internal Server Error", $resource);
    }
}
