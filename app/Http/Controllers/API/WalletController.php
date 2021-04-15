<?php

namespace App\Http\Controllers\API;

use App\Collections\StatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWalletRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::all();
        

        return response()->json([
            "status" => "success",
            "message" => "All Wallets fetched successfully.",
            "data" => $wallets
        ], StatusCodes::SUCCESS);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateWalletRequest $request)
    {
        $validatedData = $request->validated();

        $walletTypeId = $validatedData['wallet_type_id'];

        $walletType = WalletType::find($walletTypeId);

        if($validatedData['balance'] < $walletType->minimum_balance ){
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Fund the wallet with at least " . $walletType->minimum_balance,
            ],StatusCodes::BAD_REQUEST);
        }
        $wallet = Wallet::create($validatedData);

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::CREATED,
            "message" => "Wallet created successful",
            "wallet" => $wallet->load(['user', 'walletType']) 
        ],StatusCodes::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $wallet = Wallet::find($id);

        if(!isset($wallet)){
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ],StatusCodes::NOT_FOUND);
        }

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet fetched successfully.",
            "data" => $wallet->load(['user', 'walletType'])
        ], StatusCodes::SUCCESS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        // $walletType->update($request->all());

        // return response()->json([
        //     "status" => "success",
        //     "status_code" => StatusCodes::CREATED,
        //     "message" => "Wallet Type updated successful",
        //     "data" => $walletType
        // ],StatusCodes::CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $wallet = Wallet::find($id);

        if(!isset($wallet)){
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ],StatusCodes::NOT_FOUND);
        }

        $wallet->delete();

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet deleted successful",
            "wallet" =>  $wallet->load(['user', 'walletType']) 
        ],StatusCodes::SUCCESS);
    }
}
