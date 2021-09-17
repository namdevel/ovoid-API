<?php
require('../src/Ovo.php');

use Namdevel\Ovo;

$app = new Ovo();
/*
@ Step 1
*/
echo $app->sendOtp('+628XXXXXXXXXX');
/*
@ Step 2
*/
echo $app->OTPVerify('+628XXXXXXXXXX', '<otp_ref_id>', '<otp_code / otp_link_code>');
/*
@ Step 3
*/
echo $app->getAuthToken('+628XXXXXXXXXX', '<otp_ref_id>', '<otp_token>', '<security_code>');
