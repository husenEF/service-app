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
        $history = History::where('dataname', 'tires')->paginate(2);
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


    static function insertHistory(array $data)
    {
        $user = AuthController::getUser($data['token']);
        if (is_array($data['data'])) {
            $insertBulk = [];
            // dd($data);
            foreach ($data['data'] as $kd => $vd) {
                // dd($vd['status']);
                foreach ($vd as $vv) {
                    $insertBulk[] = [
                        "dataname" => $kd,
                        "comment" => $vv['status'],
                        "raw" => serialize($vv['data']),
                        "update_by" => $user->id
                    ];
                }
            }
            // dd($insertBulk);
            History::insert($insertBulk);
        }
    }
}
