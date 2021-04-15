<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WalletType;
use Illuminate\Http\Request;
use App\Collections\StatusCodes;
use App\Http\Requests\CreateWalletTypeRequest;

class WalletTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $walletTypes = WalletType::all();

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "All Wallet types fetched successfully.",
            "data" => $walletTypes
        ], StatusCodes::SUCCESS);
    }

    // $table->string('wallet_type', 255);
    // $table->double('interest_rate', 10, 2);
    // $table->double('minimum_balance', 10, 2);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateWalletTypeRequest $request)
    {
        $validatedData = $request->validated();

        $walletType = WalletType::create($validatedData);

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::CREATED,
            "message" => "Wallet Type added successful",
            "data" => $walletType
        ],StatusCodes::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function show(WalletType $walletType)
    {
        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet types fetched successfully.",
            "data" => $walletType
        ], StatusCodes::SUCCESS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletType $walletType)
    {
        $walletType->update($request->all());

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::CREATED,
            "message" => "Wallet Type added successful",
            "data" => $walletType
        ],StatusCodes::CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletType $walletType)
    {
        //
    }
}
