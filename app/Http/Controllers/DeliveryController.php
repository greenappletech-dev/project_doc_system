<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\District;
use App\Models\Province;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DemandNoticeTracker;
use Illuminate\Support\Facades\File;

class DeliveryController extends Controller
{
    //
    public function index(){
        $districts = District::select(
            'districts.id', 
            'districts.name', 
        )->get();
        $documenttion_types = DemandNoticeTracker::get();
        return view('deliveries', compact('districts', 'documenttion_types'));
    }
    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'demand_id' => 'required',
            'project_id' => 'required',
            'beneficiary_id' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //pag wala pang folder na uploads/deliveries, gagawin nya
        $folderPath = public_path('uploads/deliveries');

        // ccheck kung exist na
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            //rekta save sa uploads/deliveries
            $file->move($folderPath, $filename);
            $photoPath = 'uploads/deliveries/' . $filename;
            // dd($photoPath);
        }
        
            $delivery = $request->delivered_id > 0 ? Delivery::find($request->delivered_id): new Delivery();
                $this->photoChecker($delivery->photo);
                $delivery->notice_type_id = $request->demand_id;
                $delivery->project_id = $request->project_id;
                $delivery->beneficiary_id = $request->beneficiary_id;
                $delivery->photo = $photoPath;
                $delivery->date_captured = now();
                $delivery->user_id = auth()->user()->id;
                $delivery->save();
        // $delivery = Delivery::create([
        //     'notice_type_id' => $request->demand_id,
        //     'project_id' => $request->project_id,
        //     'beneficiary_id' => $request->beneficiary_id,
        //     'photo' => $photoPath,
        //     'date_captured' => now(),
        //     'user_id' => auth()->user()->id
        // ]);

        return response()->json([
            'message' => 'Delivery saved successfully!',
            'delivery' => $delivery
        ], 201);
    }

    public function gather_project($id){
        // $cities_id_list = [];
        // $provinces = Province::where('district_id', $id)->get();
        // for($i = 0; $i < count($provinces); $i++){
        //     $cities = City::where('province_id', $provinces[$i]->id)->get();
        //     foreach($cities as $city){
        //         $cities_id_list[] = $city->id;
        //     }
        // }
        $projects = Project::select(
            'project_offices.id', 
            'project_offices.name as text' 
        )
        ->where('district_id', $id)->orderBy('name', 'Asc')->get();

        return response()->json(['data' => $projects], 200);
    }
    public function gather_beneficiaries($id){
        // $dataArr = [];
        // $current_year = date('Y');
        // $current_month = date('m');
        // $delivery_list = Delivery::whereMonth('date_captured', $current_month)->whereYear('date_captured', $current_year)->where('project_id', $id)->get();
        // foreach($delivery_list as $delivery){
        //     $dataArr[] = $delivery->beneficiary_id;
        // }
        $beneficiaries = Beneficiary::select(
            'beneficiaries.id', 
            DB::raw("CONCAT(beneficiaries.last_name, ', ', beneficiaries.first_name, ' ', beneficiaries.middle_name) as text"),
            'beneficiary_new_addresses.new_address as address',
            'beneficiary_new_addresses.com_code'
            )
            ->leftJoin('beneficiary_new_addresses', 'beneficiary_new_addresses.bin', 'beneficiaries.beneficiaries_id')
            // ->leftJoin('beneficiary_new_addresses', 'beneficiary_new_addresses.bene_id', 'beneficiaries.id')
            ->where('project_office_id',$id)->orderBy('beneficiaries.last_name', 'Asc')->get();
            $beneficiaries->map(function($item) use($id){
                $item->delivered_id=0;
                $item->delivered_photo=null;
                $current_year = date('Y');
                $current_month = date('m');
                $delivery_list = Delivery::where('beneficiary_id', $item->id)->whereMonth('date_captured', $current_month)->whereYear('date_captured', $current_year)->where('project_id', $id)->first();
                if(!empty($delivery_list)){
                    $item->delivered_id = $delivery_list->id;
                    $item->delivered_photo = $delivery_list->photo;
                }
                return $item;
            });

        return response()->json(['data' => $beneficiaries],200);
    }
    public function photoChecker($path){
        $status = false;
        if (file_exists($path)) {
            unlink($path);
            $status = true;
        }
        return $status;
    }

}
