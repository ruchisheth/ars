<?php

namespace App\Http\Controllers\FieldRep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\FieldRep;
use App\Assignment;
use App\RoundsAcknowledge;
use Auth;
use Session;

class DashboardController extends Controller
{
	// public function __construct(){
	// 	parent::setDBConnection();
	// }
	
	public function index()
	{
		$acknos = null;
		$ackno = null;
		
		$fieldrep = FieldRep::find(Auth::user()->UserDetails->id);
		//$fieldrep = FieldRep::where(['user_id' => Auth::id()])->first();
		
		if(!Session::has('is_acknowledged')){
			$acknos = RoundsAcknowledge::where(['fieldrep_id' => Auth::user()->UserDetails->id, 'is_acknowledged' => false])->get();
			
			
			if($acknos->isEmpty()){
				$ackno = null;
			}else{
				$ackno = $acknos->filter(function ($acknos) {
					if($acknos->rounds->is_bulletin == true)
						return $acknos;
				});
				if($ackno->isEmpty()){
					$ackno = null;				
				}
			}
		}

		Session::put('is_acknowledged',true);

		$assignments = $fieldrep->assignments;//->whereIn('status', [1,3]);
		$offers = $fieldrep->offers->where('is_accepted', NULL);		

		$offers = $offers->filter(function ($offers) {
			return ($offers->assignments->rounds->status == 1 && $offers->assignments->rounds->projects->status == 1 && $offers->assignments->status == 0);
		});

		$assignments = $assignments->filter(function ($assignments) {
			return ($assignments->rounds->status == 1 && $assignments->rounds->projects->status == 1 && (($assignments->is_scheduled || $assignments->is_partial) && (!$assignments->is_reported)));
		});

		$assignment_count = $assignments->count();
		$offer_count = $offers->groupBy('assignment_id')->count();
		$data = [
		'assignment_count'   =>  $assignment_count,
		'offer_count'   =>  $offer_count,
		'acknos' => $ackno,
		];


		return view('fieldrep.dashboard',$data);
	}
}
