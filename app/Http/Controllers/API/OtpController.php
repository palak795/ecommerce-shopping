<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    // Send OTP
    public function sendOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
       'phone' => ['required', 'regex:/^[0-9]{10}$/']
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid phone number — must be exactly 10 digits',
            'errors' => $validator->errors()
        ], 422);
    }

    $otp = rand(100000, 999999);

    // Store in cache for 5 minutes
    Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));

    // Format phone with country code (India +91 example)
    $formattedPhone = '+91' . $request->phone;

    try {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // If TWILIO_PHONE is empty, don't send 'from'
        $messageData = [
            'body' => "Your verification OTP is: {$otp}"
        ];

        if (!empty(env('TWILIO_PHONE'))) {
            $messageData['from'] = env('TWILIO_PHONE');
        }

        $twilio->messages->create(
            $formattedPhone,
            $messageData
        );

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP via SMS',
            'error' => $e->getMessage()
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'OTP sent successfully to ' . $formattedPhone
    ]);
}

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'otp'   => ['required', 'digits:6']
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }
    
        // Retrieve OTP from cache
        $storedOtp = Cache::get('otp_' . $request->phone);
    
        if (!$storedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or not found'
            ], 400);
        }
    
        if ($storedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }
    
        // ✅ OTP verified — remove it from cache so it can’t be reused
        Cache::forget('otp_' . $request->phone);
    
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    public function otpForm()
    {
        return view('otp');
    }
    
}
