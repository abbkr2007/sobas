<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Models\zones;
use App\Models\branch;


class ZoneAndBranchController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function getZone()
    // {

    //     $data['zones'] = zones::select('id', 'name')->get();
    //     return view('auth.register', $data);
    // }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function getBranch(Request $request)
    {
        $data['zone'] = branch::where("zone", $request->zone)
            ->get(["name", "id"]);

        return response()->json($data);
    }
}
