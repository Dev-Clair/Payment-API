<?php

error_reporting(1);

require __DIR__ . '/../../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan(['../../src']);

header('Content-Type: application/json; charset=UTF-8');
echo $openapi->toJson();
