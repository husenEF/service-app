<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Vehicle;
// use App\Tire;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\v1\HistoryController;
use App\Http\Transformers\VehicleTransformer;
// use App\Http\Transformers\VehicleItemTransformer;
use App\Http\Controllers\api\v1\AuthController;
use App\Exports\VehicleExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $this->middleware('auth', ["except" => ["download"]]);
        $this->history = new HistoryController();
    }

    public function index(Request $re)
    {
        // dd($re->filled('page'));
        if ($re->filled('page') && ($re->query('page') == 'all')) {
            $vehicle = Vehicle::with(['user', 'tires'])->get();
        } else {
            $vehicle = Vehicle::with(['user', 'tires'])->paginate(5);
        }

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
        $vehicle = app('fractal')->item($vehicle, new VehicleTransformer())->getArray();
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
        $vehicle->size = $re->size;
        $vehicle->update_by = $re->session_user;
        $vehicle->save();
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
            'plat_number' => 'required',
            'size' => 'required|int'
        ]);

        $user = AuthController::getUser($re->header('authorization'));

        $vehicle = new Vehicle();
        $vehicle->user_id = $user->id;
        $vehicle->merek = $re->merek;
        $vehicle->platnumber = $re->plat_number;
        $vehicle->size = $re->size;
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

    public function filter(Request $re)
    {
        $name = $re->input("key");
        $value = $re->input("value");
        $vehicle = Vehicle::where($name, 'LIKE', "%$value%")->get();

        if ($vehicle->count()) {
            $vehicle = app('fractal')->collection($vehicle, new VehicleTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Filter Data Success',
                'data' => $vehicle,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data yang di cari Kosong',
                'data' => ""
            ], 404);
        }
    }

    public function download($code)
    {
        $code = "bearer " . base64_decode($code);
        $user = AuthController::getUser($code);
        if ($user) {
            $filename = "vehicle-{$user->id}-" . time() . ".xlsx";
            // Excel::store(new VehicleExport(), ("/public/excel/kendaraan.xlsx"));
            return Excel::download(new VehicleExport, $filename);
        } else {
            return response("File not found", 404);
        }
    }
}
