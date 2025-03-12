<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Delivery;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryHistoryController extends Controller
{
    //
    public function index()
    {
        $districts = District::select(
            'districts.id',
            'districts.name',
        )->get();

        $curret_date = date('Y-m-d');
        $from_date = session('from', $curret_date);
        $to_date = session('to', $curret_date);
        $district_id = session('district_id', 0);
        $project_id = session('project_id', 0);
       
    
        return view('deliveryhistory', compact( 'from_date', 'to_date', 'districts', 'district_id','project_id'));
    }
    public function gather_projects($id){
        session(['district_id' => $id]);
        $projects = DB::table('project_offices')
            ->select('project_offices.id', 'project_offices.name')
            ->where('project_offices.district_id', $id)
            ->get();
        return response()->json(['data' => $projects], 200);
    }
    public function gather_data( Request $request ){
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:from_date',
            'project_id' => 'required'
        ],[],[
            'date_from' => 'From Date',
            'date_to' => 'To Date',
            'project_id' => 'Project'
        ]);
        session(['project_id' => $request->project_id]);
        session(['from' => $request->date_from]);
        session(['to' => $request->date_to]);

        $deliveries = Delivery::select(
            'deliveries.id',
            'deliveries.photo',
            'demand_notice_trackers.name as notice_type_name',
            'project_offices.name as project_name',
             DB::raw('CONCAT(beneficiaries.first_name, " ", beneficiaries.middle_name, " ", beneficiaries.last_name) as beneficiary_name'),
            'beneficiary_new_addresses.new_address as address',
            'deliveries.date_captured',
            'users.name as collector_name'
        )
        ->leftJoin('demand_notice_trackers', 'deliveries.notice_type_id', 'demand_notice_trackers.id')
        ->leftJoin('project_offices', 'project_offices.id', 'deliveries.project_id')
        ->leftJoin('beneficiaries', 'beneficiaries.id', 'deliveries.beneficiary_id')
        ->leftJoin('beneficiary_new_addresses', 'beneficiary_new_addresses.bin', 'beneficiaries.beneficiaries_id')
        ->leftJoin('users', 'users.id', 'deliveries.user_id')
        ->where('deliveries.project_id', $request->project_id)
        ->whereBetween('deliveries.date_captured', [Carbon::parse($request->date_from)->startOfDay(), Carbon::parse($request->date_to)->endOfDay()])
        ->get();

        return response()->json(['data' => $deliveries], 200);
    }
    
}
