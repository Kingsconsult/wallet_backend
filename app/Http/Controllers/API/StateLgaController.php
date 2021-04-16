<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\StateLgaImport;
use App\Models\StateLga;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Collections\StatusCodes;
use App\Models\GroupLga;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class StateLgaController extends Controller
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
     * @param  \App\Models\StateLga  $stateLga
     * @return \Illuminate\Http\Response
     */
    public function show(StateLga $stateLga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StateLga  $stateLga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StateLga $stateLga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StateLga  $stateLga
     * @return \Illuminate\Http\Response
     */
    public function destroy(StateLga $stateLga)
    {
        //
    }

    public function importStateLga ()
    {
        $start_time = Carbon::now()->toDateTimeString();

        Excel::import(new StateLgaImport, request()->file('file'));

        $end_time = Carbon::now()->toDateTimeString();

        $getStatesLGas = StateLga::whereBetween('created_at', [$start_time, $end_time])->get();

        $StatesArray = array();

        foreach($getStatesLGas as $states)
        {
            array_push($StatesArray, $states->states);
        }

        $statesLGas = array_unique($StatesArray);

        $obj = new stdClass;

        foreach($statesLGas as $state) {

            $lgaOfStates = DB::table('state_lgas')->whereBetween('created_at', [$start_time, $end_time])->where('states', $state)->pluck('lgas');

            $obj->$state = $lgaOfStates;
        }

        return response()->json([
            "status" => "success",
            "status_code" => StatusCodes::CREATED,
            "message" => "States fetch successfully", 
            "data" =>  $obj                 
        ], StatusCodes::CREATED);   
    }
}
