<?php

namespace App\Http\Controllers;

use App\Models\zkt;
use RouterOS\Query;
use RouterOS\Client;
use App\Models\mikro;
use App\Models\nozkt;
use App\Models\employees;
use Illuminate\Http\Request;
use App\Models\EmployeeLocation;
use Illuminate\Support\Facades\Log;

class MikroController extends Controller
{

    public function receive(Request $request)
    {
        $data = $request->all();
        // تخزين البيانات أو تحليلها
        // Log::info('Data from device: ', $data);
        zkt::create([
            "name"=>$request->uid
        ]);

        return response()->json($request->all());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("mikro.mik");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        try {
            EmployeeLocation::create([
                'employees_id' => 1,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // dd($request->json()->all());
        //      return response()->json([
        //          'data' => $request->all(),
        //          'success' => true,

        // ]);
    }

    public function maps(Request $request, $id, $date)
    {
        // dd($date);
        ($loction = nozkt::where("numid", employees::where("id", $id)->first()->num)->where("date", $date)->first());
        $in = $loction->in;
        $out = $loction->out;
        $lat_in = $loction->latitude_in;
        $lon_in = $loction->longitude_in;
        $lat_out = $loction->latitude_out;
        $lon_out = $loction->longitude_out;;
        return view("employees.maps", compact("lat_in", "lon_in", "lat_out", "lon_out", "date", "in", "out"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $client = new Client([
            'host' => $request->ip,
            'user' => 'admin',
            'pass' => 'aaaa1111',
            'port' => 8728,
        ]);
        $query = new Query('/ip/hotspot/user/add');
        $query->equal('name', "ahmed");
        $query->equal('password', 123456); // قد تحتاج إلى تشفير كلمة المرور

        $client->query($query)->read();
    }

    /**
     * Display the specified resource.
     */
    public function show(mikro $mikro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(mikro $mikro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, mikro $mikro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mikro $mikro)
    {
        //
    }
}
