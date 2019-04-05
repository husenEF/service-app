<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Vehicle;
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
        $vehicle = Vehicle::with('tires')->paginate(5);

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
}
