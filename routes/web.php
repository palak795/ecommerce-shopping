<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OtpController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::get('/otp', [OtpController::class, 'otpForm'])->name('otp.form');

require __DIR__.'/auth.php';
