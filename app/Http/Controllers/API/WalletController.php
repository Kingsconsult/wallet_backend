<?php

namespace App\Http\Controllers\API;

use App\Collections\StatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWalletRequest;
use App\Http\Requests\FundWalletRequest;
use App\Http\Requests\TransferFundRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // "data" => $user->load(['wallets', 'transactions'])

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
        // user must login before creating wallet
        $authUserId = Auth::id();

        $validatedData = $request->validated();

        if (!$authUserId) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Please Login before creating your wallet",
            ], StatusCodes::BAD_REQUEST);
        }

        $walletTypeId = $validatedData['wallet_type_id'];

        $walletType = WalletType::find($walletTypeId);

        if ($validatedData['balance'] < $walletType->minimum_balance) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Fund the wallet with at least " . $walletType->minimum_balance,
            ], StatusCodes::BAD_REQUEST);
        }

        $walletTransactions = null;

        $walletTransactions = DB::transaction(function ()  use ($validatedData, $authUserId) {

            $wallet = Wallet::create([
                "user_id" => $authUserId,
                "balance" => $validatedData['balance'],
                "wallet_type_id" => $validatedData['wallet_type_id'],
            ]);

            Transaction::create(
                [
                    "amount" => $validatedData['balance'],
                    "user_id" => $authUserId,
                    "transaction_type" => "CR",
                    "credit_wallet_id " => $wallet->id,
                ]
            );

            return $wallet;
        });

        if ($walletTransactions != null) {

            return response()->json([
                "status" => "success",
                "status_code" => StatusCodes::CREATED,
                "message" => "Wallet created successful",
                "wallet" => $walletTransactions->load(['user', 'walletType'])
            ], StatusCodes::CREATED);
        }
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

        $authUserId = Auth::id();

        if (!$authUserId) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Please Login before creating your wallet",
            ], StatusCodes::BAD_REQUEST);
        }

        if (!isset($wallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ], StatusCodes::NOT_FOUND);
        }

        $transactions = Transaction::where([['debit_wallet_id', $wallet->id], ['credit_wallet_id', $wallet->id]])->get();

        $walletType = WalletType::find($wallet->wallet_type_id);

        $owner = User::find($wallet->user_id);

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet fetched successfully.",
            "data" => [
                "owner" => $owner->first_name . ' ' . $owner->last_name,
                "wallet-type" => $walletType->wallet_type,
                "detail" => $wallet->load(['walletType'])
            ],
            "transactions" => $transactions
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
        //
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

        if (!isset($wallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ], StatusCodes::NOT_FOUND);
        }

        $wallet->delete();

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet deleted successful",
            "wallet" =>  $wallet->load(['user', 'walletType'])
        ], StatusCodes::SUCCESS);
    }

    public function counts()
    {
        $walletsCount = Wallet::count();

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Total no of wallets gotten successfully.",
            "data" => [
                "users count" => $walletsCount
            ]
        ], StatusCodes::SUCCESS);
    }

    public function walletBalance($id)
    {
        $wallet = Wallet::find($id);

        if (!isset($wallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ], StatusCodes::NOT_FOUND);
        }


        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Get Wallet balance successfully.",
            "data" => [
                "balance" => $wallet->balance
            ]
        ], StatusCodes::SUCCESS);
    }

    public function fundWallet(FundWalletRequest $request)
    {

        $authUserId = Auth::id();

        $validatedData = $request->validated();

        $wallet = Wallet::find($validatedData["walletId"]);

        if (!isset($wallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet not found",
            ], StatusCodes::NOT_FOUND);
        }

        $walletTransactions = DB::transaction(function ()  use ($validatedData, $authUserId, $wallet) {

            $wallet->update([
                "balance" => $wallet->balance + $validatedData['amount'],
            ]);

            Transaction::create(
                [
                    "amount" => $validatedData['amount'],
                    "user_id" => $authUserId,
                    "transaction_type" => "CR",
                    "credit_wallet_id " => $wallet->id,
                ]
            );

            return $wallet;
        });

        if ($walletTransactions != null) {
            return response()->json([
                "status" => "success",
                "status_code" => StatusCodes::CREATED,
                "message" => "Wallet Funded successful",
                "wallet" => $walletTransactions->load(['user', 'walletType'])
            ], StatusCodes::CREATED);
        } else {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Transactio not successful",
            ], StatusCodes::BAD_REQUEST);
        }
    }


    public function walletTransfer(TransferFundRequest $request)
    {

        $validatedData = $request->validated();

        $Debitwallet = Wallet::find($validatedData["debit_wallet_id"]);

        $creditWallet = Wallet::find($validatedData["credit_wallet_id"]);

        $authUserId = Auth::id();

        if (!isset($Debitwallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet to debit not found",
            ], StatusCodes::NOT_FOUND);
        }

        if ($Debitwallet->balance < $validatedData["amount"]) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "No enough fund in the wallet to transfer",
                "data" => [
                    "wallet remaining balance" => $Debitwallet->balance,
                    "Amount to transfer" => $validatedData["amount"]
                ]
            ], StatusCodes::BAD_REQUEST);
        }

        if (!isset($creditWallet)) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "Wallet to credit not found",
            ], StatusCodes::NOT_FOUND);
        }

        $walletType = WalletType::find($Debitwallet->wallet_type_id);

        $walletRemainingBalance = $Debitwallet->balance - $validatedData["amount"];

        if ($walletType->minimum_balance > $walletRemainingBalance) {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "The minimum balance for your wallet will be " . $walletType->minimum_balance,
                "data" => [
                    "wallet type minimum balance" => $walletType->minimum_balance,
                    "wallet balance after transfer" => $walletRemainingBalance
                ]
            ], StatusCodes::BAD_REQUEST);
        }

        $walletTransactions = null;

        $walletTransactions = DB::transaction(function ()  use ($validatedData, $authUserId, $Debitwallet, $creditWallet) {

            $Debitwallet->update([
                "balance" => $Debitwallet->balance - $validatedData['amount'],
            ]);

            $creditWallet->update([
                "balance" => $creditWallet->balance + $validatedData['amount'],
            ]);

            $transaction = Transaction::create(
                [
                    "amount" => $validatedData['amount'],
                    "user_id" => $authUserId,
                    "transaction_type" => "CR",
                    "credit_wallet_id" => $validatedData["credit_wallet_id"],
                    "debit_wallet_id" => $validatedData["debit_wallet_id"],
                ]
            );

            return $transaction;
        });

        if ($walletTransactions != null) {

            return response()->json([
                "status" => "success",
                "status_code" => StatusCodes::CREATED,
                "message" => "Wallet Funded successful",
                "transaction" => [
                    "Debit Wallet" => $Debitwallet->load('walletType'),
                    "Credit Wallet" => $creditWallet->load('walletType'),
                    "details" => $walletTransactions
                ]
            ], StatusCodes::CREATED);
        } else {
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::BAD_REQUEST,
                "message" => "Transactio not successful",
            ], StatusCodes::BAD_REQUEST);
        }
    }
}
