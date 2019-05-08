<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Tire;
use App\Service;
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

    public function update(Request $re)
    {
        // dd($re->all());
        $filename = "";
        if ($re->has('image')) {
            $image = $re->file("image");
            $filename = time() . "." . $image->getClientOriginalExtension();
            $image->storeAs('public/tires', $filename);
        }


        $tire = Tire::find($re->input('id'));
        $tire->merek = $re->input('merek');
        $tire->ukuran = $re->input("ukuran_ban");
        $tire->nomor = $re->input("nomor_ban");
        $tire->stempel = $re->input("stempel_ban");
        $tire->buy_date = date("Y-m-d H:m:s", strtotime($re->buy_date));
        $tire->images = $filename;
        if ($tire->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Update Data Success',
                'data' => $tire
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function filter(Request $re)
    {
        $name = $re->key;
        $value = $re->value;

        if ($name !== 'datetime') {
            $tire = Tire::where($name, 'LIKE', $value)->get();
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

    public function getAssignTire(Request $re,  $id)
    {
        $id = (int)$id;
        if (!is_int($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
        $tire = Tire::where("id_vehicle", 'LIKE', $id)->orderBy('posistion')->get();

        if ($tire->count() > 0) {

            $tire = app('fractal')->collection($tire, new TireTransformer())->getArray();
            // dd($tire);
            $tires = [];
            $raw = [];
            for ($n = 0; $n < 11; $n++) {
                $data = array_filter($tire, function ($val) use ($n) {
                    return ($val['posistion'] == ($n + 1));
                });
                $raw[$n] = $data;
                if (count($data) > 0) {
                    $tires[$n] = array_values($data)[0];
                } else {
                    $tires[$n] = (Object)[];
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $tires,
                'ori' => $tire,
                'raw' => $raw
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => []
            ], 200);
        }
    }

    public function assignTire(Request $re)
    {
        // dd($re->all());
        $this->validate($re, [
            "vehicle_id" => 'required|int',
            "uid" => 'required|int'
        ]);
        $vehicleId = $re->input("vehicle_id");
        $uid = $re->input('uid');

        if (count($re->tires) > 0) {
            foreach ($re->tires as $posisi => $v) {
                if (!empty($v)) {
                    $theTire = Tire::findOrfail($v['id']);
                    if ($theTire->posistion == 0) {
                        $tire = Tire::find($v['id']);
                        $tire->posistion = $posisi + 1;
                        $tire->id_vehicle = $vehicleId;
                        $tire->save();
                        $tire->uid = $uid;
                        $dbug[] = $tire;
                        $this->insertService($tire);
                    }
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Update Success',
            'data' => $dbug,
        ], 200);
    }

    private function insertService($tire)
    {
        // dd($tire);
        $service = new Service();
        $service->tire_id = $tire->id;
        $service->user = $tire->uid;
        $service->kendaraan = $tire->id_vehicle;
        $service->tekanan_angin = 0;
        $service->tebal_tapak = 0;
        $service->posisi = $tire->posistion;
        $service->jarakkm =  0;
        $service->catatan = "";
        $service->kelainan = "";
        $service->lepasban = 0;
        $service->alasanlepas = "";
        $service->image = "";
        return $service->save();
    }
}
