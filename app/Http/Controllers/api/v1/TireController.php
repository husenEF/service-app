<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Tire;
use App\Http\Controllers\Controller;

class TireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tire = Tire::paginate(5);

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

    public function delete(Request $re,$id){
       $delete = Tire::find($id);
       if($delete){
           if($delete->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Delete Tire Success',
                'data' => ''
            ], 204);
           }else{
            return response()->json([
                'success' => false,
                'message' => 'Delete Error',
                'data' => ""
            ], 404);
           }
       }else{
        return response()->json([
            'success' => false,
            'message' => 'Get Data failed',
            'data' => ""
        ], 404);
       }
    }
}
