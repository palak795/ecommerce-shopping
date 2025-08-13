<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    public function checkPhone(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'phone' => ['required', 'regex:/^[0-9]{10}$/']
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid phone number — must be exactly 10 digits',
            'errors' => $validator->errors()
        ], 422);
    }

    return response()->json([
        'success' => true,
        'message' => 'Valid phone number',
        'phone' => $request->phone
    ]);
}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
