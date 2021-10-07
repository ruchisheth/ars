<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\AssignmentsOffer;
use App\Assignment;
use App\FieldRep;
use Auth;
use DB;


class FieldrepSidebarComposer
{
    public $counters = [];

    public function __construct()
    {
        $rep_id = Auth::user()->UserDetails->id;
        $fieldrep = FieldRep::find(Auth::user()->UserDetails->id);
        $assignments = $fieldrep->assignments;//->whereIn('status', [1,3]);
        $offers = $fieldrep->offers;

        $all_offers = $offers->filter(function ($offers) {
            return ($offers->assignments->rounds->status == 1 && $offers->assignments->rounds->projects->status == 1);
        });
        $all_offers = $all_offers->groupBy('assignment_id')->count();
        //dd($all_offers);

        $pending_offers = $offers->filter(function ($offers) {
            return ($offers->is_accepted === NULL && $offers->assignments->rounds->status == 1 && $offers->assignments->rounds->projects->status == 1);
        });
        $pending_offers = $pending_offers->groupBy('assignment_id')->count();

        $all_assignments = $assignments->filter(function ($assignments) {
            return ($assignments->rounds->status == 1 && $assignments->rounds->projects->status == 1);
        });


        $active_assignments = $assignments->filter(function ($assignments) {
            return ($assignments->rounds->status == 1 && $assignments->rounds->projects->status == 1 && (($assignments->is_scheduled || $assignments->is_partial == 3) && (!$assignments->is_reported)));
        });

        $this->counters = [
            'assignments'=> $active_assignments->count()." / ".$all_assignments->count(),
            'offers'=>$pending_offers." / ".$all_offers,
        ];
    }

    public function compose(View $view)
    {
        $view->with(['counters'=>$this->counters]);
    }
}