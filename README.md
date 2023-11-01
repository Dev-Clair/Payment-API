# PAYMENT-API

The PAYMENT API is a RESTful web service that allows you to manage payment methods, customers and payments using reliable product endpoints. This API is built using the Slim PHP framework, adheres to RESTful principles, and provides features for CRUD operations.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Technologies Used](#technologies-used)
- [Contributing](#contributing)
- [License](#license)

## Features

- API Authentication: JWT.
- API Logging: Monolog.
- API Caching: None (No third Party Libraries/Extensions).
- API Pagination: None.

  `API Info`

- `GET /v1`: Get API info.

  `Method Endpoints`

- `GET /v1/methods`: Get a list of all available payment methods.
- `POST /v1/methods`: create a new payment method.
- `PUT /v1/methods/{id:[0-9]+}`: Update a payment method by it's ID (Identifier).
- `DELETE /v1/methods/{id:[0-9]+}`: Delete a payment method by it's ID (Identifier).
- `GET /v1/methods/deactivate/{id:[0-9]+}`: Deactivate a method by it's ID (Identifier).
- `GET /v1/methods/reactivate/{id:[0-9]+}`: Reactivate a method by it's ID (Identifier).

  `Customer Endpoints`

- `GET /v1/customers`: Get a list of all customer accounts.
- `POST /v1/customers`: create a new customer account.
- `PUT /v1/customers/{id:[0-9]+}`: Update a customer account by it's ID (Identifier).
- `DELETE /v1/customers/{id:[0-9]+}`: Delete a customer account by it's ID (Identifier).
- `GET /v1/customers/deactivate/{id:[0-9]+}`: Deactivate a customer account by it's ID (Identifier).
- `GET /v1customers/reactivate/{id:[0-9]+}`: Reactivate a customer account by it's ID (Identifier).

  `Payment Endpoints`

- `GET /v1/payments`: Get a list of all payment records.
- `POST /v1/payments`: create a new paymet record.
- `PUT /v1/payments/{id:[0-9]+}`: Update a payment record by it's ID (Identifier).
- `DELETE /v1/payments/{id:[0-9]+}`: Delete a payment record by it's ID (Identifier).

## Installation

1. Clone the repository to your desktop or projects directory:
   ```bash
   git clone https://github.com/Dev-Clair/Payment_API.git
   ```
2. Ensure you have docker desktop client installed on your desktop.

3. Navigate to the project directory and open git bash within the project root:
   ```bash
   cd Payment_API
   ```
4. Launch the docker desktop client.
5. Launch the following docker bash command to pull your container images from docker hub:

   ```bash
   docker-compose up --build
   ```

   The command will pull, configure and start the containers via the configuration settings defined in the docker-compose.yml file

6. If the container doesn't automatically start after pulling and installation; via the docker desktop application start the containers or better still run the following bash command to start up your container in terminal:
   ```bash
   docker-compose up
   ```
7. Install the required dependencies using composer via docker:
   ```bash
   docker-compose composer install
   docker-compose composer dump-autoload
   ```
8. Login to your database by navigating into your mariadb container and running the following commands successively:

   ```bash
   winpty docker exec -it payment_api_mariadb bash

   mariadb -u root -p
   ```

   type in the password of your choice (ensure it is the same value set for the MARIADB_DB_USER_PASSWORD in the .env config file).

9. Create database and generate entities by navigating into your nginx container and running the following commands successively:

   ```bash
   winpty docker exec -it payment_api_php bash

   php vendor/bin/doctrine orm:schema-tool:create
   ```

10. Set up your preferred http client to make http requests as defined in the _Usage_ section below.

## Usage

1. Access the application through your preferred http API Client and make a request to any of the above endpoints.
2. This API requires authentication via bearer tokens, so a signed bearer token or public api key should be added to your authorization payload header to authenticate every request to resource endpoints.
3. Make defined requests to each of the endpoints to see what the web service has to offer.
4. All endpoints are tested and return valid `application/json` response.
5. Navigate to the tests/ directory and run the following commands with or without any of the following options ` --colors` and `--testdox`

- `./vendor/bin/phpunit tests/CustomersControllerTest.php`
- `./vendor/bin/phpunit tests/MethodsControllerTest.php`
- `./vendor/bin/phpunit tests/PaymentsControllerTest.php`

## API Documentation

The API documentation is generated using Swagger (OpenAPI). You can access the documentation by visiting the following URL in your browser: `http://localhost:26000/docs/`

Swagger UI

This documentation provides detailed information about each endpoint, input parameters, response formats, and example requests/responses

## Technologies Used

- PHP (>= 8.0)
- PostMan as http client for making request to endpoints.
- Guzzle as http client for testing.
- MySQL database for data storage.
- Composer for dependency management.
- MRC (Model-Request-Controller) design architecture.

## Contributing

Contributions are welcome! If you find a bug or want to make a helpful recommendation, please follow these steps:

1. Fork the repository.
2. Create a new branch.
3. Make your changes and test them thoroughly.
4. Create a pull request describing the changes you've made.

## License

This project was developed for learning purposes (Courtesy: Jagaad Academy).
This project is licensed under the [MIT License](LICENSE).
