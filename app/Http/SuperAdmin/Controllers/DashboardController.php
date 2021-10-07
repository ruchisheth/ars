<?php

namespace App\Http\SuperAdmin\Controllers;

use App\Http\SuperAdmin\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\FieldRep;

use Auth;

class DashboardController extends Controller
{
	public function index()
	{
		return view('SuperAdminView::dashboard');
	}
}
