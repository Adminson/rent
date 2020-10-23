<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\House;
use App\Models\Charge;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\TenancyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    function servicelist()
    {
        $servicelist = DB::table('services')
            ->select('services.*', 'users.*', 'houses.*', 'services.status AS services_status', 'services.description AS services_description')
            ->join('users', 'users.id', '=', 'services.renter_id')
            ->join('houses', 'houses.house_id', '=', 'services.house_id')
            ->where('renter_id', Auth::user()->id)
            ->orderBy('service_id', 'desc')
            ->get();
        return view('service.servicelist', compact('servicelist'));
    }
    function servicelandlordlist()
    {
        $servicelandlordlist = DB::table('services')
            ->select('services.*', 'users.*', 'houses.*', 'services.status AS services_status', 'services.description AS services_description')
            ->join('users', 'users.id', '=', 'services.renter_id')
            ->join('houses', 'houses.house_id', '=', 'services.house_id')
            ->where('houses.landlord_id', Auth::user()->id)
            ->orderBy('service_id', 'desc')
            ->get();

        //update count
        $pending = DB::table('services')
            ->where('status', 'Pending')
            ->count();
        $accepted = DB::table('services')
            ->where('status', 'Accepted')
            ->count();
        $rejected = DB::table('services')
            ->where('status', 'Rejected')
            ->count();
        $counting = [];
        $counting['pending'] = $pending;
        $counting['accepted'] = $accepted;
        $counting['rejected'] = $rejected;
        //  dd($counting);
        return view('service.servicelandlordlist', compact('servicelandlordlist','counting'));
    }
    function servicecreate($id)
    {
        $servicelist = DB::table('tenants')
            ->select('tenants.*', 'users.*', 'houses.*', 'tenants.status as request_status')
            ->join('users', 'users.id', '=', 'tenants.renter_id')
            ->join('houses', 'houses.house_id', '=', 'tenants.house_id')
            ->where('tenants.renter_id', Auth::user()->id)
            ->where('tenants.house_id', $id)
            ->get();
        return view('service.servicecreate', compact('servicelist'));
    }

    public function servicestore(Request $request)
    {

        // dd($request);
        $service = Service::create([
            'service_title' => $request['service_title'],
            'description' => $request['description'],
            'status' => "Pending",
            'house_id' => $request['house_id'],
            'renter_id' => Auth::user()->id,
            'landlord_id' => $request['landlord_id'],
        ]);

        return response()->json($service, 201);
    }

    public function servicechangestatus(Request $request)
    {
        // dd($request);
        $services_status = $request->services_status;
        $service = Service::find($request->service_id);
        $service->status = $services_status;
        $service->save();

        //update count
        // $pending = DB::table('tenancy_request')
        //         ->where('status', 'Pending')
        //         ->count();
        // $viewing = DB::table('tenancy_request')
        //         ->where('status', 'Viewing')
        //         ->count();
        // $accepted = DB::table('tenancy_request')
        //         ->where('status', 'Accepted')
        //         ->count();
        // $rejected = DB::table('tenancy_request')
        //         ->where('status', 'Rejected')
        //         ->count();
        // $request = [];
        // $request['pending']=$pending;
        // $request['viewing']=$viewing;
        // $request['accepted']=$accepted;
        // $request['rejected']=$rejected;
        return response()->json($service, 201);
    }
}