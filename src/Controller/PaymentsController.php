<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Repositories\PaymentsRepository;
use Payment_API\Entity\PaymentsEntity;
use Payment_API\Enums\PaymentsResponseTitle as ResponseTitle;
use Payment_API\Utils\Validation\PaymentsValidation;
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
 *   description="API endpoints for managing payments",
 * )
 */
class PaymentsController implements ControllerInterface
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
        private PaymentsRepository $paymentsRepository,
        private Logger $logger
    ) {
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
        $payments = $this->paymentsRepository->findAll();

        if (is_array($payments)) {
            return $this->status_200(ResponseTitle::GET, "Retrieved", $payments);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::GET]);

        return $this->status_500(ResponseTitle::GET, "Internal Server Error", $payments);
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
        $requestContent = json_decode($request->getBody()->getContents(), true);

        $requestMethod = $request->getMethod();

        if (empty($requestContent)) {
            return $this->status_400(ResponseTitle::POST, "Bad Request", ["request body" => "Empty"]);
        }

        $paymentEntity = new PaymentsEntity;

        $validateRequestBody = new PaymentsValidation($requestContent, $requestMethod);

        if (empty($validateRequestBody->validationError)) {
            $this->paymentsRepository->store($validateRequestBody->createPaymentEntity($paymentEntity));

            return $this->status_201(ResponseTitle::POST, "Created", "");
        } else {
            return $this->status_422(ResponseTitle::POST, "Unprocessable Entity", $validateRequestBody->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::POST]);

        return $this->status_500(ResponseTitle::POST, "Internal Server Error", "");
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

        $validateResource = $this->paymentsRepository->validateId($requestAttribute);

        if ($validateResource === false) {
            return $this->status_404(ResponseTitle::PUT, "Resource not found for " . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        $requestContent = json_decode($request->getBody()->getContents(), true);

        $requestMethod = $request->getMethod();

        if (empty($requestContent)) {
            return $this->status_400(ResponseTitle::PUT, "Bad Request", ["request body" => "Empty"]);
        }

        $paymentEntity = $this->paymentsRepository->findById($requestAttribute);

        $validateRequestContent = new PaymentsValidation($requestContent, $requestMethod);

        if (empty($validateRequestContent->validationError)) {
            $this->paymentsRepository->update($validateRequestContent->updatePaymentEntity($paymentEntity));

            return $this->status_200(ResponseTitle::PUT, "Modified Payment with ID " . $requestAttribute, "");
        } else {
            return $this->status_422(ResponseTitle::PUT, "Unprocessable Entity", $validateRequestContent->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::PUT]);

        return $this->status_500(ResponseTitle::PUT, "Internal Server Error", "");
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

        $validateResource = $this->paymentsRepository->validateId($requestAttribute);

        if ($validateResource === false) {
            return $this->status_404(ResponseTitle::DELETE, "Resource not found for ID" . $requestAttribute, ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $paymentEntity = $this->paymentsRepository->findById($requestAttribute);

            $this->paymentsRepository->remove($paymentEntity);

            return $this->status_200(ResponseTitle::DELETE, "Deleted Payment with ID " . $requestAttribute, "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DELETE]);

        return $this->status_500(ResponseTitle::DELETE, "Internal Server Error", "");
    }
}
