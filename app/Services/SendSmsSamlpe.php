<?php
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;

public function create_receipt(MobitelInstantSmsService $smsService)
    { 
        $toNumber = preg_replace('/^0/', '94', $request->customer_phone ?? '');

        $smsMessage = "Thank you for your payment.\n";
        $smsMessage .= "Receipt No: " . $receipt->id . "\n";
        $smsMessage .= "Amount: " . number_format($receipt->amount, 2) . "\n";
        $smsMessage .= "Payment Method: " . ucfirst($receipt->payment_method) . "\n";
        $smsMessage .= "ADM No:  " . $updated_receipt->user->userDetails->adm_number . "\n";

        try {
            $numbers = [(string) $toNumber];
            $smsResponse = $smsService->sendInstantSms($numbers, $smsMessage);
            Log::info('SMS sent to ' . $toNumber, (array)$smsResponse);
        } catch (\Exception $e) {
            Log::error('SMS sending failed for Receipt ID ' . $receipt->id . ': ' . $e->getMessage());
        }

    }