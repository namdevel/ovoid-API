<?php 
require_once __DIR__ . '/vendor/autoload.php';

use NAMDEVEL\OvoId;

$ovo = new OvoId();
echo $ovo->login2FA('<phone number>');