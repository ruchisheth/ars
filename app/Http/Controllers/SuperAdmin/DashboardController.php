<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\FieldRep;

use Auth;

class DashboardController extends Controller
{
	public function index()
	{
		return view('super_admin.dashboard');
	}
}
