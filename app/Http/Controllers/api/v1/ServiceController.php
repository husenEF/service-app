<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tekanan_angin' => 'required',
            'tebal_tapak' => 'required',
            'posisi' => 'required|int',
            'jarakkm' => 'required|int',

        ]);
        $service = new Service();
        $service->tire_id = $request->input("tire_id");
        $service->user = $request->input("user");
        $service->kendaraan = $request->input("kendaraan");
        $service->tekanan_angin = $request->input("tekanan_angin");
        $service->tebal_tapak = $request->input("tebal_tapak");
        $service->posisi = $request->input("posisi");
        $service->jarakkm = $request->input("jarakkm");
        $service->catatan = $request->input("catatan");
        $service->kelainan = $request->input("kelainan");
        $service->alasanlepas = $request->input("alasanlepas");
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
