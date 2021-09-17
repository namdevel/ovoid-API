<?php
require('src/Ovo.php');

use Namdevel\Ovo;

$token = "eyJhbGciOiJSUzI1NiJ9.eyJleHBpc...."; // your ovo auth token
$app = new Ovo($token);

echo $app->getLastTransactions(); // get 5 last transaction

echo $app->getFavoriteTransfer(); // get favorite transfer

echo $app->getEmail(); // get account email

echo $app->transactionHistory(); // get transaction history

echo $app->walletInquiry(); // wallet inquiry

echo $app->getOvoCash(); // get ovo balance

echo $app->getOvoCashCardNumber(); // get ovo cash card number

echo $app->getOvoPointsCardNumber(); // get ovo point card number

echo $app->getOvoPoints(); // get ovo point balance

echo $app->getPointDetails(); // get ovo point history

echo $app->getBillerList(); // get biller list

echo $app->getBillerCategory('<category_id>'); // get biller category

echo $app->getDenominations('<product_id>'); // get denomination

echo $app->getBankList(); // get bank list

echo $app->getUnreadNotifications(); // get total unread notification

echo $app->getAllNotifications(); // get all notifications

echo $app->getInvestment(); // get investment data

echo $app->isOVO('<amount>', '<phone_number>'); // ovo account validate

echo $app->getTransactionDetails('<merchant_id>', '<merchant_invoice>'); // get transaction details