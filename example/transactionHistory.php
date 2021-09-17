<?php
require('../src/Ovo.php');

use Namdevel\Ovo;

$token = "eyJhbGciOiJSUzI1NiJ9.eyJleHBpc...."; // your ovo auth token

$app = new Ovo($token);

echo $app->transactionHistory();