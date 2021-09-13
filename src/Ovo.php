<?php
namespace Namdevel;
/*
@ Unofficial Ovo API PHP Class
@ Author : namdevel
@ Created at 04-03-2020 14:26
@ Last Modified at 18-07-2021 11:44
*/

class Ovo
{
    /*
    @ Device ID (UUIDV4)
    @ Generated from self::generateUUIDV4();
    */
    const device_id = "7F341E8D-C44E-436D-AF39-CA8B5ECF348D";
    const api_url = "https://api.ovo.id";
    const heusc_api = "https://agw.heusc.id";
    const os_name = "iOS";
    const os_version = "14.4.2";
    const app_id = "P72RVSPSF61F72ELYLZI";
    const app_version = "3.37.0";
    const user_agent = "OVO/16820 CFNetwork/1220.1 Darwin/20.3.0";
    const action_mark = "OVO Cash";
    /*
    @ Push Notification ID (SHA256 Hash)
    @ Generated from self::generateRandomSHA256();
    */
    const push_notif_id = "3961627a2311328ca428dc1403d18a4d9f60b724d7a886081d88784cb928a684";

    private $auth_token;

    public function __construct($auth_token = null)
    {
        $this->auth_token = $auth_token;
    }

    /*
    @ login2FA
    @ POST("/v3/user/accounts/otp")
    */
    public function login2FA($phoneNumber)
    {
        $field = [
            "msisdn" => $phoneNumber,
            "device_id" => self::device_id,
        ];
        return self::Request(
            self::heusc_api . "/v3/user/accounts/otp",
            $field,
            self::headers()
        );
    }

    /*
    @ login2FAverify
    @ POST("/v3/user/accounts/otp/validation")
    */
    public function login2FAverify($reff_id, $otpCode, $phoneNumber)
    {
        $field = [
            "msisdn" => $phoneNumber,
            "device_id" => self::device_id,
            "otp_code" => $otpCode,
            "reff_id" => $reff_id,
        ];
        return self::Request(
            self::heusc_api . "/v3/user/accounts/otp/validation",
            $field,
            self::headers()
        );
    }

    /*
    @ loginSecurityCode
    @ POST("/v3/user/accounts/login")
    */
    public function loginSecurityCode($securityCode, $phoneNumber, $otp_token)
    {
        $field = [
            "security_code" => $securityCode,
            "msisdn" => $phoneNumber,
            "device_id" => self::device_id,
            "otp_token" => $otp_token,
        ];
        return self::Request(
            self::heusc_api . "/v3/user/accounts/login",
            $field,
            self::headers()
        );
    }

    /*
    @ getNotifications
    @ GET("/v1.0/notification/status/all?limit={limit}")
    */
    public function getNotifications($limit = 5)
    {
        return self::Request(
            self::api_url . "/v1.0/notification/status/all?limit={$limit}",
            false,
            self::headers()
        );
    }

    /*
    @ getAccountNumber
    @ parse self::walletInquiry()
    */
    public function getAccountNumber()
    {
        $json = json_decode(self::walletInquiry());
        return $json->data->{'001'}->card_no;
    }

    /*
    @ getBalance
    @ parse self::walletInquiry()
    */
    public function getBalance()
    {
        $json = json_decode(self::walletInquiry());
        return $json->data->{'001'}->card_balance;
    }

    /*
    @ getPoint
    @ parse self::walletInquiry()
    */
    public function getPoint()
    {
        $json = json_decode(self::walletInquiry());
        return $json->data->{'600'}->card_balance;
    }

    /*
    @ walletInquiry
    @ GET("/wallet/inquiry")
    */
    public function walletInquiry()
    {
        return self::Request(
            self::api_url . "/wallet/inquiry",
            false,
            self::headers()
        );
    }

    /*
    @ transactionHistory
    @ GET("/wallet/v2/transaction?page={page}&limit={limit}")
    */
    public function transactionHistory($page = 1, $limit = 10)
    {
        return self::Request(
            self::api_url .
                "/wallet/v2/transaction?page={$page}&limit={$limit}",
            false,
            self::headers()
        );
    }

    /*
    @ getBankList
    @ GET("/v1.0/reference/master/ref_bank")
    */
    public function getBankList()
    {
        return self::Request(
            self::api_url . "/v1.0/reference/master/ref_bank",
            false,
            self::headers()
        );
    }

    /*
    @ isOVO
    @ POST("/v1.1/api/auth/customer/isOVO")
    */
    public function isOVO($phoneNumber)
    {
        $field = [
            "mobile" => $phoneNumber,
            "amount" => 10000,
        ];
        return self::Request(
            self::api_url . "/v1.1/api/auth/customer/isOVO",
            $field,
            self::headers()
        );
    }

    /*
    @ generateTrxId
    @ POST("/v1.0/api/auth/customer/genTrxId")
    */
    private function generateTrxId($amount)
    {
        $field = [
            "amount" => $amount,
            "actionMark" => self::action_mark,
        ];
        return self::Request(
            self::api_url . "/v1.0/api/auth/customer/genTrxId",
            $field,
            self::headers()
        );
    }

    /*
    @ generateSignature
    @ unlockTrxId Signature
    */
    private function generateSignature($amount, $trxId)
    {
        $device = self::device_id;
        return sha1("{$trxId}||{$amount}||{$device}");
    }

    /*
    @ unlockAndValidateTrxId
    @ POST("/v1.0/api/auth/customer/unlockAndValidateTrxId")
    */
    private function unlockTrxId($amount, $trxId, $securityCode)
    {
        $field = [
            "trxId" => $trxId,
            "signature" => self::generateSignature($amount, $trxId),
            "securityCode" => $securityCode,
        ];
        return self::Request(
            self::api_url . "/v1.0/api/auth/customer/unlockAndValidateTrxId",
            $field,
            self::headers()
        );
    }

    /*
    @ generateUUIDV4
    @ generate random UUIDV4 for device ID
    */
    public function generateUUIDV4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return strtoupper(
            vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4))
        );
    }

    /*
    @ generateRandomSHA256
    @ generate random SHA256 hash for push notification ID
    */
    public function generateRandomSHA256()
    {
        return hash_hmac("sha256", microtime(), "namdevel_ovo_api");
    }

    /*
    @ tfOVO
    @ POST("/v1.0/api/customers/transfer")
    */
    public function tfOVO($amount, $phoneNumber, $securityCode, $message = "")
    {
        $verify = self::isOVO($phoneNumber);
        if (self::getRes($verify)->fullName) {
            $trxId = self::getRes(self::generateTrxId($amount))->trxId;
            $field = [
                "amount" => $amount,
                "trxId" => $trxId,
                "to" => $phoneNumber,
                "message" => $message,
            ];
            $tfOVO = self::Request(
                self::api_url . "/v1.0/api/customers/transfer",
                $field,
                self::headers()
            );
            if (preg_match("/sorry unable to handle your request/", $tfOVO)) {
                $unlock = self::unlockTrxId($amount, $trxId, $securityCode);
                if (self::getRes($unlock)->isAuthorized) {
                    return self::Request(
                        self::api_url . "/v1.0/api/customers/transfer",
                        $field,
                        self::headers()
                    );
                } else {
                    return $unlock;
                }
            } else {
                return $tfOVO;
            }
        } else {
            return $verify;
        }
    }

    /*
    @ tfBankPrepare
    @ POST("/transfer/inquiry/")
    */
    private function tfBankPrepare(
        $bankCode,
        $bankNumber,
        $amount,
        $messages = ""
    ) {
        $field = [
            "accountNo" => $bankNumber,
            "bankCode" => $bankCode,
            "messages" => $messages,
            "amount" => $amount,
        ];
        return self::Request(
            self::api_url . "/transfer/inquiry/",
            $field,
            self::headers()
        );
    }

    /*
    @ tfBankExecute
    @ POST("/transfer/direct")
    */
    private function tfBankExecute(
        $amount,
        $bankName,
        $bankCode,
        $bankAccountNumber,
        $bankAccountName,
        $trxId,
        $notes = ""
    ) {
        $field = [
            "bankName" => $bankName,
            "notes" => $notes,
            "transactionId" => $trxId,
            "accountNo" => self::getAccountNumber(),
            "accountName" => $bankAccountName,
            "accountNoDestination" => $bankAccountNumber,
            "bankCode" => $bankCode,
            "amount" => $amount,
        ];
        return self::Request(
            self::api_url . "/transfer/direct",
            $field,
            self::headers()
        );
    }

    /*
    @ tfBank
    @ call self::tfBankPrepare()
    @ call self::tfBankExecute()
    */
    public function tfBank(
        $bankCode,
        $bankNumber,
        $amount,
        $securityCode,
        $notes = ""
    ) {
        $tfBankPrepare = self::tfBankPrepare($bankCode, $bankNumber, $amount);
        $bankInfo = self::getRes($tfBankPrepare);
        if ($bankInfo->accountName) {
            $trxId = self::getRes(self::generateTrxId($amount))->trxId;
            $tfBankExecute = self::tfBankExecute(
                $bankInfo->baseAmount,
                $bankInfo->bankName,
                $bankInfo->bankCode,
                $bankInfo->accountNo,
                $bankInfo->accountName,
                $trxId,
                $notes
            );
            if (
                preg_match(
                    "/sorry unable to handle your request/",
                    $tfBankExecute
                )
            ) {
                $unlock = self::unlockTrxId($amount, $trxId, $securityCode);
                if (self::getRes($unlock)->isAuthorized) {
                    return self::tfBankExecute(
                        $bankInfo->baseAmount,
                        $bankInfo->bankName,
                        $bankInfo->bankCode,
                        $bankInfo->accountNo,
                        $bankInfo->accountName,
                        $trxId,
                        $notes
                    );
                } else {
                    return $unlock;
                }
            } else {
                return $tfBankExecute;
            }
        } else {
            return $tfBankPrepare;
        }
    }

    /*
    @ getRes
    @ Decoded JSON response
    */
    private function getRes($json)
    {
        return json_decode($json);
    }

    /*
    @ headers
    @ OVO custom headers
    */
    private function headers()
    {
        $headers = [
            "content-type: application/json",
            "app-id: " . self::app_id,
            "app-version: " . self::app_version,
            "os: " . self::os_name,
            "user-agent: " . self::user_agent,
        ];

        return $headers;
    }

    /*
    @ Request
    @ Curl http request
    */
    private function Request($url, $post = false, $headers = false)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }

        if (!empty($this->auth_token)) {
            array_push($headers, "authorization: Bearer " . $this->auth_token);
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
