<?php
require('../src/Ovo.php');

use Namdevel\Ovo;

$token = "eyJhbGciOiJSUzI1NiJ9.eyJleHBpc...."; // your ovo auth token

$app = new Ovo($token);
$amount_to_pay = 10000; // amount to pay (integer)
$ovo_pin = "XXXXXX"; # your ovo pin / security code

$inquiry = $app->transferBankInquiry('<bank_code>', '<bank_number>', $amount_to_pay, $message = ""); // inquiry request (get bank account details)

$detail = json_decode($inquiry);

$trx_id = json_decode($app->generateTrxId($amount_to_pay, 'OVO Cash'))->trxId; // generate transaction id , OVO Cash = action mark for tf OVO & Tf Bank
$unlock = json_decode($app->unlockAndValidateTrxId($amount_to_pay, $trx_id, $ovo_pin)); // unlock and validate transaction id

if($unlock->isAuthorized){ // is unlock authorized
	echo $app->transferBankDirect($detail->bankCode, $detail->accountNo, $detail->bankName, $detail->accountName, $trx_id, $amount_to_pay, $notes = "");
}
