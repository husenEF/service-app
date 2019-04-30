<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Tire;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\v1\HistoryController;
use App\Http\Transformers\TireTransformer;

class TireController extends Controller
{
    private $history = null;
    public function __construct()
    {
        $this->history = new HistoryController();
        $this->middleware('auth');
    }

    public function index(Request $re)
    {
        if ($re->filled('page') && ($re->query('page') == 'all')) {
            $tire = Tire::with(['getUser', 'getVehicle'])->get();
        } else {
            $tire = Tire::with(['getUser', 'getVehicle'])->paginate();
        }
        $tire = app('fractal')->collection($tire, new TireTransformer())->getArray();
        if ($tire) {
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $tire
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function delete(Request $re, $id)
    {
        $return = ["token" => $re->header("Authorization")];
        $delete = Tire::find($id);
        if ($delete) {
            $return['data']['tires'][] = [
                'status' => 'delete',
                'data' => json_encode($delete),
            ];

            $this->history->insertHistory($return);
            if ($delete->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Delete Tire Success',
                    'data' => ''
                ], 204);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Delete Error',
                    'data' => ""
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function filter(Request $re)
    {
        $name = $re->key;
        $value = $re->value;

        if ($name !== 'datetime') {
            $tire = Tire::where($name, 'LIKE', "%$value%")->get();
        } else {
            $tire = Tire::whereDate('buy_date', date('Y-m-d', strtotime($value)))->get();
        }

        if ($tire->count() > 0) {
            $tire = app('fractal')->collection($tire, new TireTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $tire
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
        $tire = Tire::findOrFail($id);
        if ($tire) {
            $tire = app('fractal')->item($tire, new TireTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $tire
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
