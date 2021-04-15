<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Collections\StatusCodes; 

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function allUsers()
    {
        $allUsers = User::all();

        
        return response()->json([
            "status" => "success",
            "message" => "All Wallets fetched successfully.",
            "data" => $allUsers->load(['wallets', 'transactions'])
        ], StatusCodes::SUCCESS);
    }

    public function user($id)
    {
        $user = User::find($id);

        if(!isset($user)){
            return response()->json([
                "status" => "failure",
                "status_code" => StatusCodes::NOT_FOUND,
                "message" => "User not found",
            ],StatusCodes::NOT_FOUND);
        }


        // $wallets = Wallet::find($user->id);

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::SUCCESS,
            "message" => "Wallet fetched successfully.",
            "data" => $user->load(['wallets', 'transactions'])
        ], StatusCodes::SUCCESS);

    }

    public function countUsers ()
    {
        $usersCount = User::count();

        return response()->json([
            "status" => "success",
            "message" => "Total users count gotten successfully.",
            "data" => [
                "users count" => $usersCount
            ]
        ], StatusCodes::SUCCESS);
    }
}
