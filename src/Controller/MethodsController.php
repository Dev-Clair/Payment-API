<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Repositories\MethodsRepository;
use Payment_API\Entity\MethodsEntity;
use Payment_API\Enums\MethodsResponseTitle as ResponseTitle;
use Payment_API\Enums\MethodStatus;
use Payment_API\Utils\Validation\MethodsValidation;
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
 *   description="API endpoint for managing payment methods",
 * )
 */
class MethodsController implements ControllerInterface
{
    use Status_200;
    use Status_201;
    use Status_400;
    use Status_401;
    use Status_404;
    use Status_405;
    use Status_422;
    use Status_500;

    public function __construct(
        private MethodsRepository $methodsRepository,
        private Logger $logger
    ) {
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
        try {
            $methods = $this->methodsRepository->findAll();

            if (is_array($methods)) {
                return $this->status_200(ResponseTitle::GET, "Retrieved", $methods);
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::GET, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::GET, "Internal Server Error", "");
        }
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
        try {
            $requestContent = json_decode($request->getBody()->getContents(), true);

            $requestMethod = $request->getMethod();

            if (empty($requestContent)) {
                $this->logger->error("Bad request", [ResponseTitle::POST]);

                return $this->status_400(ResponseTitle::POST, "Bad Request", ["message" => "empty request body"]);
            }

            $validateRequestBody = new MethodsValidation($requestContent, $requestMethod);

            $methodEntity = new MethodsEntity;

            if (empty($validateRequestBody->validationError)) {
                $this->methodsRepository->store($validateRequestBody->createMethodEntity($methodEntity));

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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->methodsRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::PUT, "Method not found for ID " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            $requestContent = json_decode($request->getBody()->getContents(), true);

            $requestMethod = $request->getMethod();

            if (empty($requestContent)) {
                $this->logger->error("Bad request", [ResponseTitle::POST]);

                return $this->status_400(ResponseTitle::PUT, "Bad Request", ["message" => "empty request body"]);
            }

            $methodEntity = $this->methodsRepository->findById($requestAttribute);

            $validateRequestBody = new MethodsValidation($requestContent, $requestMethod);

            if (empty($validateRequestBody->validationError)) {
                $this->methodsRepository->update($validateRequestBody->updateMethodEntity($methodEntity));

                return $this->status_200(ResponseTitle::PUT, "Modified method with ID " . htmlspecialchars((string) $requestAttribute), "");
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->methodsRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::DELETE, "Method not found for ID " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $methodsEntity = $this->methodsRepository->findById($requestAttribute);

                $this->methodsRepository->remove($methodsEntity);

                return $this->status_200(ResponseTitle::DELETE, "Deleted method with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::DELETE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::DELETE, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->methodsRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::DEACTIVATE, "Method not found for ID " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $methodsEntity = $this->methodsRepository->findById($requestAttribute);

                $methodsEntity->setMethodStatus(MethodStatus::INACTIVE->value);
                $this->methodsRepository->update($methodsEntity);

                return $this->status_200(ResponseTitle::DEACTIVATE, "Deactivated method with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::DEACTIVATE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::DEACTIVATE, "Internal Server Error", "");
        }
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
        $requestAttribute = (int) $args['id'];

        try {
            $validateResource = $this->methodsRepository->validateId($requestAttribute);

            if ($validateResource === false) {
                return $this->status_404(ResponseTitle::REACTIVATE, "Method not found for ID " . htmlspecialchars((string) $requestAttribute), ['Invalid Resource ID' => htmlspecialchars((string) $requestAttribute)]);
            }

            if ($validateResource === true) {
                $methodsEntity = $this->methodsRepository->findById($requestAttribute);

                $methodsEntity->setMethodStatus(MethodStatus::ACTIVE->value);
                $this->methodsRepository->update($methodsEntity);

                return $this->status_200(ResponseTitle::REACTIVATE, "Reactivated method with ID " . htmlspecialchars((string) $requestAttribute), "");
            }
        } catch (\Exception $e) {
            $this->logger->critical("Internal Server Error", ['title' => ResponseTitle::REACTIVATE, 'status' => 500, 'message' => $e->getMessage()]);

            return $this->status_500(ResponseTitle::REACTIVATE, "Internal Server Error", "");
        }
    }
}
