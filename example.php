<?php 
require_once __DIR__ . '/vendor/autoload.php';

use Namdevel\Ovo;
$ovo = new Ovo();
echo $ovo->login2FA('<phone number>');
