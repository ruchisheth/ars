<?php

namespace App\Http\SuperAdmin\Controllers;

use App\Http\SuperAdmin\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;


class HomeController extends Controller
{

      public function callGetHome(Request $oRequest)
      {
            return \View::make('SuperAdminView::home');
      }
}