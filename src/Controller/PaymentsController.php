<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Interface\ControllerInterface;
use Payment_API\Repositories\PaymentsRepository;
use Payment_API\Entity\PaymentsEntity;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\PaymentsResponseTitle as ResponseTitle;
use Payment_API\Utils\Validation\PaymentsValidation;
use Monolog\Logger;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Payment API",
 *   version="1.0.0",
 *   description="API for managing customer payments",
 * )
 */
class PaymentsController implements ControllerInterface
{
    protected PaymentsEntity $paymentsEntity;

    protected PaymentsRepository $paymentsRepository;

    protected Logger $logger;

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
        $payments = $this->paymentsRepository->findAll();

        if (is_array($payments)) {
            return JSONResponse::response_200(ResponseTitle::GET, "Retrieved", $payments);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::GET]);

        return JSONResponse::response_500(ResponseTitle::GET, "Internal Server Error", $payments);
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
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            return JSONResponse::response_400(ResponseTitle::POST, "Bad Request", ["request body" => "Empty"]);
        }

        $paymentsEntity = new PaymentsValidation($requestBody);

        if (empty($paymentsEntity->validationError)) {
            $this->paymentsRepository->store($paymentsEntity->getEntities());

            return JSONResponse::response_201(ResponseTitle::POST, "Created", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::POST, "Unprocessable Entity", $paymentsEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::POST]);

        return JSONResponse::response_500(ResponseTitle::POST, "Internal Server Error", "");
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
        $requestAttribute = $args['id'];

        $validateResource = $this->paymentsRepository->validate($requestAttribute);
        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::PUT, "Resource Not Found", ['Invalid Resource ID' => $requestAttribute]);
        }

        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody)) {
            return JSONResponse::response_400(ResponseTitle::PUT, "Bad Request", ["request body" => "Empty"]);
        }

        $paymentsEntity = $this->paymentsRepository->findById($requestAttribute);

        $validatePaymentEntity = new PaymentsValidation($requestBody);

        if (empty($validatePaymentEntity->validationError)) {
            $paymentsEntity->setAmount($validatePaymentEntity->validationResult['amount']);

            $paymentsEntity->setStatus($validatePaymentEntity->validationResult['status']);

            $paymentsEntity->setType($validatePaymentEntity->validationResult['ype']);

            $this->paymentsRepository->update($paymentsEntity);

            return JSONResponse::response_200(ResponseTitle::PUT, "Modified", "");
        } else {
            return JSONResponse::response_422(ResponseTitle::PUT, "Unprocessable Entity", $validatePaymentEntity->validationError);
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::PUT]);

        return JSONResponse::response_500(ResponseTitle::PUT, "Internal Server Error", "");
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
        $requestAttribute = $args['id'];

        $validateResource = $this->paymentsRepository->validate($requestAttribute);

        if ($validateResource === false) {
            return JSONResponse::response_404(ResponseTitle::DELETE, "Resource Not Found", ['Invalid Resource ID' => $requestAttribute]);
        }

        if ($validateResource === true) {
            $paymentsEntity = $this->paymentsRepository->findById($requestAttribute);

            $this->paymentsRepository->remove($paymentsEntity);

            return JSONResponse::response_200(ResponseTitle::DELETE, "Deleted", "");
        }

        $this->logger->emergency("Internal Server Error", [ResponseTitle::DELETE]);

        return JSONResponse::response_500(ResponseTitle::DELETE, "Internal Server Error", "");
    }
}
