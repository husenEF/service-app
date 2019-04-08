<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Vehicle;
use App\Tire;
use App\Http\Controllers\Controller;

use App\Http\Transformers\VehicleTransformer;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $vehicle = Vehicle::with(['user', 'tires'])->paginate(5);

        $vehicle = app('fractal')->collection($vehicle, new VehicleTransformer())->getArray();
        if ($vehicle) {
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $vehicle,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function show($id)
    {

        $vehicle = Vehicle::with(['user', 'tires'])->find($id);
        if ($vehicle) {
            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => "Success get Data"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function update(Request $re, $id)
    {

        $vehicle = Vehicle::with('tires')->find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'data' => ""
            ], 404);
        }

        $return = [];
        //update ban
        $vehicle->merek = $re->merek;
        $vehicle->platnumber = $re->platnumber;
        if ($vehicle->save()) {
            $return['vehicle'] = [
                'status' => 'update',
                'data' => $vehicle
            ];
        }

        //update ban
        // dd($re->tires);
        foreach ($re->tires as $k => $tire) {
            $tire = (object)$tire;
            if ($tire->id > 0) {
                $tireObj = Tire::find($tire->id);
                $tireObj->merek = $tire->merek;
                $tireObj->buy_date = date("Y-m-d H:i:s", strtotime($tire->buy_date));
                $tireObj->posistion = $k + 1;
                if ($tireObj->save()) {
                    $return['tires'][] = [
                        'status' => 'update',
                        'data' => $tireObj
                    ];
                }
            } else {
                $tireObj = new Tire();
                $tireObj->merek = $tire->merek;
                $tireObj->buy_date = date("Y-m-d H:i:s", strtotime($tire->buy_date));
                $tireObj->posistion = $k + 1;
                $tireObj->id_vehicle = $tire->id_vehicle;
                $tireObj->created_by = $tire->created_by;
                if ($tireObj->save()) {
                    $return['tires'][] = [
                        'status' => 'new',
                        'data' => $tireObj
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Update Done',
            'data' => $return
        ], 200);
    }
}
