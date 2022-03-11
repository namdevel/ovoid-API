<?php
require('../src/Ovo.php');

use Namdevel\Ovo;

$token = "eyJhbGciOiJSUzI1NiJ9.eyJleHBpc...."; // your ovo auth token

$app = new Ovo($token);

$to = '08XXXXXXXXXX'; # no hp penerima
$amount_to_pay = "5000"; # amount to pay (string)
$ovo_pin = "XXXXXX"; # your ovo pin / security code

$trx_id = json_decode($app->generateTrxId($amount_to_pay, 'OVO Cash'))->trxId; // generate transaction id , OVO Cash = action mark for tf OVO & Tf Bank
$unlock = json_decode($app->unlockAndValidateTrxId($amount_to_pay, $trx_id, $ovo_pin)); // unlock and validate transaction id

if($unlock->isAuthorized){ // is unlock authorized
	echo $app->transferOVO($amount_to_pay, $to, $trx_id, $message = "");
}