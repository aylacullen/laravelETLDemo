<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function getStaffData() {
        $staffData = Staff::all();
        return response()->json($staffData);
    }
}
