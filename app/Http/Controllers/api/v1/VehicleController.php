<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Vehicle;
use App\Tire;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\v1\HistoryController;
use App\Http\Transformers\VehicleTransformer;
use App\Http\Transformers\VehicleItemTransformer;
use App\Http\Controllers\api\v1\AuthController;

class VehicleController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $history = null;

    public function __construct()
    {
        $this->middleware('auth');
        $this->history = new HistoryController();
    }

    public function index()
    {
        $vehicle = Vehicle::with(['user', 'tires'])->paginate();

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
        $vehicle = Vehicle::with(['user', 'tires'])->where("id", $id)->get()->first();
        //        $vehicle = Vehicle::findOrFail($id);
        $vehicle = app('fractal')->item($vehicle, new VehicleItemTransformer())->getArray();
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
        // dd($re->all());
        $vehicle = Vehicle::with('tires')->find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'data' => ""
            ], 404);
        }

        $return = ['token' => $re->header('Authorization')];
        //update ban                   // dd($object);
        $vehicle->merek = $re->merek;
        $vehicle->platnumber = $re->platnumber;
        $vehicle->update_by = $re->session_user;
        // if ($vehicle->save()) {
        //     $return['data']['vehicle'][] = [
        //         'status' => 'update',
        //         'data' => $vehicle,
        //         'update_by' => $re->session_user
        //     ];
        // }

        //update ban
        // dd($re->tires);
        foreach ($re->tire as $k => $tire) {
            $tire = (object)$tire;
            if ($tire->id > 0) {
                $update = false;
                $tireObj = Tire::find($tire->id);
                if ($tireObj->merek != $tire->merek) {
                    $tireObj->merek = $tire->merek;
                    $update = true;
                }
                if (date("Y-m-d H:i:s", strtotime($tireObj->buy_date)) !== date("Y-m-d H:i:s", strtotime($tire->buy_date))) {
                    $tireObj->buy_date = date("Y-m-d H:i:s", strtotime($tire->buy_date));
                    $update = true;
                }
                if ($tireObj->posistion !== $tire->posistion) {
                    $update = true;
                    $tireObj->posistion = $tire->position;
                }
                if ($update) {
                    if ($tireObj->save()) {
                        $return['data']['tires'][] = [
                            'status' => 'update',
                            'data' => json_encode($tireObj),
                            'update_by' => $re->session_user
                        ];
                    }
                }
            } else {
                $tireObj = new Tire();
                $tireObj->merek = $tire->merek;
                $tireObj->buy_date = date("Y-m-d H:i:s", strtotime($tire->buy_date));
                $tireObj->posistion = $k + 1;
                $tireObj->id_vehicle = $tire->id_vehicle;
                $tireObj->created_by = $tire->created_by;
                if ($tireObj->save()) {
                    $return['data']['tires'][] = [
                        'status' => 'new',
                        'data' => json_encode($tireObj),
                        'update_by' => $re->session_user
                    ];
                }
            }
        }

        $this->history->insertHistory($return);
        return response()->json([
            'success' => true,
            'message' => 'Update Done',
            'data' => $return
        ], 200);
    }

    public function add(Request $re)
    {
        $this->validate($re, [
            'merek' => 'required',
            'plat_number' => 'required'
        ]);

        $user = AuthController::getUser($re->header('authorization'));

        $vehicle = new Vehicle();
        $vehicle->user_id = $user->id;
        $vehicle->merek = $re->merek;
        $vehicle->platnumber = $re->plat_number;
        $vehicle->update_by = $user->id;
        $vehicle->created_by = $user->id;
        if ($vehicle->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Add Vehicle Success',
                'data' => ''
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Save Error',
                'data' => ""
            ], 404);
        }
    }
}
