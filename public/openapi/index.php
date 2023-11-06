<?php

error_reporting(1);

use \OpenApi\Generator;

require __DIR__ . '/../../vendor/autoload.php';

$openapi = Generator::scan(['../../src']);

header('Content-Type: application/json; charset=UTF-8');
echo $openapi->toJson();
