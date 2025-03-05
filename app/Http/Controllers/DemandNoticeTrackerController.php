<?php

namespace App\Http\Controllers;

use App\Models\DemandNoticeTracker;
use Illuminate\Http\Request;

class DemandNoticeTrackerController extends Controller
{
    //
    // Show the demand notice page
    public function index()
    {

        return view('masterdata.demandnotice');
    }

    // Fetch all demand notices for Vue table
    public function show()
    {
        $demandNotices = DemandNoticeTracker::all();
        return response()->json(['data' => $demandNotices], 200);
    }

    // Store a new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $demandNotice = DemandNoticeTracker::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Demand Notice successfully created!',
            'data' => $demandNotice,
        ], 200);
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $demandNotice = DemandNoticeTracker::findOrFail($id);
        $demandNotice->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Demand Notice successfully updated!',
            'data' => $demandNotice,
        ]);
    }

    // Delete a record
    public function destroy($id)
    {
        $demandNotice = DemandNoticeTracker::where('id', $id)->first();
        $demandNotice->delete();

        return response()->json([
            'message' => 'Demand Notice successfully deleted!',
        ]);
    }
}
