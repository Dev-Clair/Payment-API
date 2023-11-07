<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Interface\SmsAlertServiceInterface;
use Payment_API\Services\SmsAlertService\TwilioSmsAlertService;
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Entity\CustomersEntity;
use Payment_API\Enums\CustomersResponseTitle as ResponseTitle;
use Payment_API\Enums\CustomerStatus;
use Payment_API\Utils\Validation\CustomersValidation;
use Payment_API\Utils\Response\Status_200;
use Payment_API\Utils\Response\Status_201;
use Payment_API\Utils\Response\Status_400;
use Payment_API\Utils\Response\Status_401;
use Payment_API\Utils\Response\Status_404;
use Payment_API\Utils\Response\Status_405;
use Payment_API\Utils\Response\Status_422;
use Payment_API\Utils\Response\Status_500;
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
    use Status_200;
    use Status_201;
    use Status_400;
    use Status_401;
    use Status_404;
    use Status_405;
    use Status_422;
    use Status_500;

    private SmsAlertServiceInterface $twilioSms;

    public function __construct(
        TwilioSmsAlertService $twilioSms,
        private CustomersRepository $customersRepository,
        private Logger $logger
    ) {
        $this->twilioSms = $twilioSms;
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
        try {
            $customers = $this->customersRepository->findAll();

            if (is_array($customers)) {
                return $this->status_200(ResponseTitle::GET, "Retrieved", $customers);
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::GET, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::GET, "Internal Server Error", "");
        }
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
        try {
            $requestContent = json_decode($request->getBody()->getContents(), true);

            $requestMethod = $request->getMethod();

            if (empty($requestContent)) {
                $this->logger->error("Bad request", [ResponseTitle::POST]);

                return $this->status_400(ResponseTitle::POST, "Bad Request", ["message" => "empty request body"]);
            }

            $customerEntity = new CustomersEntity;

            $validateRequestBody = new CustomersValidation($requestContent, $requestMethod);

            if (empty($validateRequestBody->validationError)) {
                $this->customersRepository->store($validateRequestBody->createCustomerEntity($customerEntity));

                return $this->status_201(ResponseTitle::POST, "Created", "");
            } else {
                return $this->status_422(ResponseTitle::POST, "Unprocessable Entity", $validateRequestBody->validationError);
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::POST, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::POST, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->customersRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::PUT, "Customer Account ID not found for " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            $requestContent = json_decode($request->getBody()->getContents(), true);

            $requestMethod = $request->getMethod();

            if (empty($requestContent)) {
                $this->logger->error("Bad request", [ResponseTitle::POST]);

                return $this->status_400(ResponseTitle::PUT, "Bad Request", ["message" => "empty request body"]);
            }

            $customerEntity = $this->customersRepository->findById($requestAttribute);

            $validateRequestBody = new CustomersValidation($requestContent, $requestMethod);

            if (empty($validateRequestBody->validationError)) {
                $this->customersRepository->update($validateRequestBody->updateCustomerEntity($customerEntity));

                return $this->status_200(ResponseTitle::PUT, "Modified account with ID " . htmlspecialchars((string) $requestAttribute), "");
            } else {
                return $this->status_422(ResponseTitle::PUT, "Unprocessable Entity", $validateRequestBody->validationError);
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::PUT, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::PUT, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->customersRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::DELETE, "Customer Account ID not found for " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $customersEntity = $this->customersRepository->findById($requestAttribute);

                $this->customersRepository->remove($customersEntity);

                return $this->status_200(ResponseTitle::DELETE, "Deleted account with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::DELETE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::DELETE, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->customersRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::DEACTIVATE, "Customer Account ID not found for " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $customersEntity = $this->customersRepository->findById($requestAttribute);

                $customersEntity->setCustomerStatus(CustomerStatus::INACTIVE->value);

                $this->customersRepository->update($customersEntity);

                return $this->status_200(ResponseTitle::DEACTIVATE, "Deactivated account with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::DEACTIVATE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::DEACTIVATE, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->customersRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::REACTIVATE, "Customer Account ID not found for " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $customersEntity = $this->customersRepository->findById($requestAttribute);

                $customersEntity->setCustomerStatus(CustomerStatus::ACTIVE->value);

                $this->customersRepository->update($customersEntity);

                return $this->status_200(ResponseTitle::REACTIVATE, "Reactivated account with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::REACTIVATE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::REACTIVATE, "Internal Server Error", "");
        }
    }
}
