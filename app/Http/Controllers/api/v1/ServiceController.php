<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ServiceTransformer;
use App\Service;
use App\Tire;
use App\Exports\ServiceExport;
use App\Exports\TireExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ["except" => ["export"]]);
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
        // dd($re->all());
        // $this->validate(
        //     $re,
        //     [
        //         'vehicle' => 'required',
        //         'tire' => 'required'
        //     ],
        //     [
        //         "vehicle.required" => "Silahkan pilih kendaraan",
        //         "tire.required" => "Silahkan pilih Ban"
        //     ]
        // );

        // $service = Service::where([
        //     ['tire_id', '=', $re->tire['id']],
        //     ['kendaraan', '=', $re->vehicle['id']]
        // ])->with(['getUser', 'tire', 'vehicle'])->get();
        // dd($re->all());

        if ($re->tire !== null && $re->vehicle !== null) {
            $service = Service::where([
                ['tire_id', '=', $re->tire['id']],
                ['kendaraan', '=', $re->vehicle['id']]
            ])->with(['getUser', 'tire', 'vehicle'])->get();
        } else if ($re->tire !== null && $re->vehicle == null) {
            // echo "tire";
            // $service = Service::where([
            //     ['tire_id', '=', $re->tire['id']],
            //     // ['kendaraan', '=', $re->vehicle['id']]
            // ])->with(['getUser', 'tire', 'vehicle'])->get();
            $service = Service::where("tire_id", $re->tire['id'])->with(['getUser', 'tire', 'vehicle'])->get();
        } else if ($re->vehicle !== null && $re->tire == null) {
            // echo "vehicle";
            $service = Service::where([
                // ['tire_id', '=', $re->tire['id']],
                ['kendaraan', '=', $re->vehicle['id']]
            ])->groupBy('tire_id')->with(['getUser', 'tire', 'vehicle'])->get();
        } else {
            return  response()->json([
                'success' => false,
                'message' => 'Pilih Kendaraan atau Nama ban terlebih dahulu',
                'data' => ""
            ], 404);
        }
        // dd([$re->all(), $service]);

        // $service = Service::where([
        //     ['tire_id', '=', $re->tire['id']],
        //     ['kendaraan', '=', $re->vehicle['id']]
        // ])->with(['getUser', 'tire', 'vehicle'])->get();
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
        if ($request->input('lepasban')) {
            $this->validate(
                $request,
                [
                    // 'image' => 'mimes:jpeg,jpg,png,gif|required|max:500',
                    'alasanlepas' => 'required'
                ],
                [
                    'alasanlepas.required' => "Harus Pilih salah satu alasan lepas ban"
                ]
            );
        } else {
            $this->validate($request, [
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:500',
                'tekanan_angin' => 'required',
                'tebal_tapak' => 'required',
                'posisi' => 'required|int',
                'jarakkm' => 'required|int',
                'image' => 'required|mimes:jpeg,jpg,png,gif|required|max:10000'
            ], parent::errorValidationMessage());
        }
        // $path = $request->file('image')->store('public/service');
        $filename = "";
        if ($request->hasFile("image")) {
            $image = $request->file("image");
            $filename = time() . "." . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/service', $filename);
        }
        // dd($request->all());
        $service = new Service();
        $service->tire_id = $request->input("tire_id");
        $service->user = $request->input("user");
        $service->kendaraan = ($request->input('lepasban')) ? 0 : $request->input("kendaraan");
        $service->tekanan_angin = ($request->input("tekanan_angin")) ? $request->input("tekanan_angin") : 0;
        $service->tebal_tapak = ($request->input("tebal_tapak")) ? $request->input("tebal_tapak") : 0;
        $service->posisi = ($request->input('lepasban')) ? 0 : $request->input("posisi");
        $service->jarakkm = ($request->input("jarakkm")) ? $request->input("jarakkm") : 0;
        $service->catatan = $request->input("catatan");
        $service->kelainan = $request->input("kelainan");
        $service->lepasban = $request->input("lepasban");
        $service->alasanlepas = $request->input("alasanlepas");
        $service->image = $filename;
        if ($service->save()) {
            if ($request->input('lepasban')) {
                $tire = Tire::find($service->tire_id);
                $tire->posistion = 0;
                $tire->id_vehicle = 0;
                $tire->save();
            }
            $data = [
                'success' => true,
                'message' => 'Simpan data sukses!',
                'data' => $service
            ];
            if ($request->input('lepasban')) {
                $data['redirect'] = "/vehicle/tire/" . $request->input("kendaraan");
            }
            return response()->json(
                $data,
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

    public function export(Request $re)
    {
        // dd($re->all());
        $where = [];
        if ($re->has('tire') && ($re->tire !== null)) {
            $where['tire_id'] = $re->tire['id'];
        }
        if ($re->has('vehicle') && ($re->vehicle !== null)) {
            $where['kendaraan'] = $re->vehicle['id'];
        }
        // dd([$where, $re->all()]);
        // return Excel::download(new ServiceExport($where), 'service.xlsx');
        Excel::store(new ServiceExport($where), 'public/excel/service.xlsx');
        return url("/media/excel/service.xlsx");
    }

    public function filterDate(Request $re)
    {
        $val = '';
        if ($re->value !== null)
            $val = date('Y-m-d', strtotime($re->value));

        if ($re->key == "buy_date") {
            $tire = Tire::whereDate('buy_date', '=', $val)->get();
            if ($tire->count()) {
                Excel::store(new TireExport($val), 'public/excel/tire.xlsx');
                return response()->json([
                    'success' => true,
                    'message' => 'Dowload',
                    'data' => url("/media/excel/tire.xlsx")
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tidak Ada, silahkan cari waktu lain',
                    'data' => []
                ], 404);
            }
        }

        if ($re->key == "check_date") {
            $service = Service::where(\DB::raw('DAY("created_at")'), $val)->get();
            dd($service->count(), $service->getSql(), $service->getBindings());
            if ($service->count()) {
                Excel::store(new ServiceExport(['check_date' => $val]), 'public/excel/service.xlsx');
                return response()->json([
                    'success' => true,
                    'message' => 'Dowload',
                    'data' => url("/media/excel/tire.xlsx")
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tidak Ada, silahkan cari waktu lain',
                    'data' => []
                ], 404);
            }
        }
        // dd($re->value, $service);
        return response()->json([
            'success' => false,
            'message' => 'Silahkan pilih jenis waktu',
            'data' => ($re->key == "check_date")
        ], 404);
    }
}
