<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ServiceTransformer;
use App\Service;
use Illuminate\Support\Facades\Storage;


class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = Service::with(['getUser', 'tire', 'vehicle'])->paginate();
        if ($service) {
            $service = app('fractal')->collection($service, new ServiceTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $service
            ], 200);
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
        $this->validate(
            $re,
            [
                'vehicle' => 'required',
                'tire' => 'required'
            ],
            [
                "vehicle.required" => "Silahkan pilih kendaraan",
                "tire.required" => "Silahkan pilih Ban"
            ]
        );

        $service = Service::where([
            ['tire_id', '=', $re->tire['id']],
            ['kendaraan', '=', $re->vehicle['id']]
        ])->with(['getUser', 'tire', 'vehicle'])->get();
        if ($service->count()) {
            $service = app('fractal')->collection($service, new ServiceTransformer())->getArray();
            return response()->json([
                'success' => true,
                'message' => 'Get Data Success',
                'data' => $service
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
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
        $file_data = $request->image;
        preg_match("/^data:image\/(.*);base64/i",$file_data, $match);
        // dd($match);
        $file_name = 'image_' . time() . '.'.$match[1]; //generating unique file name; 
        @list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data) = explode(',', $file_data);
        if ($file_data != "") { // storing image in storage/app/public Folder 
            \Storage::disk('public')->put($file_name, base64_decode($file_data));
        }
        if ($request->input('lepasban')) {
            $this->validate(
                $request,
                [
                    'alasanlepas' => 'required'
                ],
                [
                    'alasanlepas.required' => "Harus Pilih salah satu alasan lepas ban"
                ]
            );
        } else {
            $this->validate($request, [
                'tekanan_angin' => 'required',
                'tebal_tapak' => 'required',
                'posisi' => 'required|int',
                'jarakkm' => 'required|int',
                'image' => 'required'
            ]);
        }

        $service = new Service();
        $service->tire_id = $request->input("tire_id");
        $service->user = $request->input("user");
        $service->kendaraan = $request->input("kendaraan");
        $service->tekanan_angin = ($request->input("tekanan_angin")) ? $request->input("tekanan_angin") : 0;
        $service->tebal_tapak = ($request->input("tebal_tapak")) ? $request->input("tebal_tapak") : 0;
        $service->posisi = $request->input("posisi");
        $service->jarakkm = ($request->input("jarakkm")) ? $request->input("jarakkm") : 0;
        $service->catatan = $request->input("catatan");
        $service->kelainan = $request->input("kelainan");
        $service->lepasban = $request->input("lepasban");
        $service->alasanlepas = $request->input("alasanlepas");
        $service->image = $request->input("image");
        if ($service->save()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Simpan data sukses!',
                    'data' => $service
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Simpan data gagal!',
                    'data' => ""
                ],
                400
            );
        }
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
}
