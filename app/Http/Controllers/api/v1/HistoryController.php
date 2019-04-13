<?php

namespace App\Http\Controllers\api\v1;

use App\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\HistoryTransformer;
use App\Http\Controllers\api\v1\AuthController;

class HistoryController extends Controller
{
    private $auth = null;

    function __construct()
    {
        $this->auth = new AuthController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $history = History::paginate(2);
        $history = app('fractal')->collection($history, new HistoryTransformer())->getArray();
        if ($history) {
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $history
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function positionHistory(Request $re, $vehicle, $id)
    {
        $history = History::where([
            "posistion" => $id,
            "id_vehicle" => $vehicle
        ])->with('user')->paginate();
        // dd($history);
        if ($history) {
            $history = app('fractal')->collection($history, new HistoryTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $history
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    public function tireHistory(Request $re, $id)
    {
        $history = History::where('id_tires', $id)->paginate(15);
        if ($history) {
            $history = app('fractal')->collection($history, new HistoryTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $history
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Get Data failed',
                'data' => ""
            ], 404);
        }
    }

    static function insertHistory(array $data)
    {
        $user = AuthController::getUser($data['token']);
        // dd($data);
        if (is_array($data['data'])) {
            $insertBulk = [];

            foreach ($data['data'] as $kd => $vd) {
                if ($kd == 'tires') {
                    foreach ($vd as $vv) {
                        $object = json_decode($vv['data']);
                        $insertBulk[] = [
                            "dataname" => $kd,
                            "status" => $vv['status'],
                            'id_tires' => $object->id,
                            "id_vehicle" => $object->id_vehicle,
                            "created_by" => $object->created_by,
                            "posistion" => $object->posistion,
                            "merek" => $object->merek,
                            "buy_date" => $object->buy_date,
                            "images" => (isset($object->images)) ? $object->images : '',
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            // dd($insertBulk);
            History::insert($insertBulk);
        }
    }
}
