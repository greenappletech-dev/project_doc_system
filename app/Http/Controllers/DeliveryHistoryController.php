<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryHistoryController extends Controller
{
    //
    public function index()
    {
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
        ->get();
    
        return view('deliveryhistory', compact('deliveries'));
    }
    
}
