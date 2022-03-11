<?php
require('../src/Ovo.php');

use Namdevel\Ovo;

$token = "eyJhbGciOiJSUzI1NiJ9.eyJleHBpc...."; // your ovo auth token

$app = new Ovo($token);

$qrid = '00020101021126710024ID.CO.MANDIRISYARIAH.WWW0118936004510000003993021000000039930303URE51440014ID.CO.QRIS.WWW0215ID20200312314480303URE5204866153033605802ID5923yys Dompet Umat (Infaq)6009PONTIANAK61057812162070703A016304DD8E'; # qrid
$amount_to_pay = "5000"; # amount to pay (string)
$ovo_pin = "XXXXXX"; # your ovo pin / security code

$trx_id = json_decode($app->generateTrxId($amount_to_pay, 'PAY_TRX_ID'))->trxId; // generate transaction id , PAY_TRX_ID = action mark for billpay and qris pay
$unlock = json_decode($app->unlockAndValidateTrxId($amount_to_pay, $trx_id, $ovo_pin)); // unlock and validate transaction id

if($unlock->isAuthorized){ // is unlock authorized
	echo $app->QrisPay($amount_to_pay, $trx_id, $qrid);
}