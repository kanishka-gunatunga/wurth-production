<?php

namespace App\Services;

use SoapClient;
use Exception;
use Illuminate\Support\Facades\Log;

class MobitelInstantSmsService
{
    protected $client;
    protected $username;
    protected $password;
    protected $accountNo;
    protected $senderId;

    public function __construct()
    {
        $wsdl = "https://msmsent.mobitel.lk/BulkSMS_v2/SendBulk?wsdl";

        $this->username  = env('MOBITEL_USERNAME');
        $this->password  = env('MOBITEL_PASSWORD');
        $this->accountNo = env('MOBITEL_ACCOUNT');
        $this->senderId  = env('MOBITEL_SENDER_ID');

        $this->client = new SoapClient($wsdl, [
            'trace' => true,
            'exceptions' => true,
        ]);
    }

    public function sendInstantSms(array $numbers, string $message, string $campaignName = "TestCampaign")
    {
        try {
            Log::info("Sending SMS via Mobitel to: " . implode(',', $numbers));

            $startDate = date("Y-m-d H:i:s");
            $endDate   = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Build object matching WSDL SMS type
            $smsDetails = new \stdClass();
            $smsDetails->username               = $this->username;
            $smsDetails->password               = $this->password;
            $smsDetails->account_no             = $this->accountNo;
            $smsDetails->send_id                = $this->senderId;
            $smsDetails->language               = "1";
            $smsDetails->sms_content            = $message;
            $smsDetails->bulk_start_date        = $startDate;
            $smsDetails->bulk_end_date          = $endDate;
            $smsDetails->campaign_name          = $campaignName;
            $smsDetails->number_list            = $numbers;
            $smsDetails->add_block_notification = "1";
            $smsDetails->enableTax              = "2";

            // Wrap inside 'SMSDetails' key for SOAP call
            $params = ['SMSDetails' => $smsDetails];

            $response = $this->client->__soapCall("SendInstantSMS", [$params]);

            Log::info("SOAP Request: " . $this->client->__getLastRequest());
            Log::info("SOAP Response: " . $this->client->__getLastResponse());

            return $response;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
