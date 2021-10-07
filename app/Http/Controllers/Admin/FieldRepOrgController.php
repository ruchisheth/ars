<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Database\Eloquent\Collection;
use Validator;
use App\Site;
use App\Client;
use App\Chain;
use App\FieldRep;
use App\FieldRep_Org;
use App\AppData;
use DB;
use Datatables;
use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class FieldRepOrgController extends Controller
{
  public function index(){
    $res = parent::isDataAvailable('FieldRep Organization','fieldreporgs.create');
    if($res === true){
      $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name');
      $fieldreporg_list = ['' => 'Select FieldRep Org'] + FieldRep_Org::lists('fieldrep_org_name','id')->all();
      return view('admin.fieldrep_orgs.fieldrep_orgs',compact('fieldreporg_list','states'));
    }
    return $res;
  }

  public function create()
  {

    $fieldrep_org_id = FieldRep_Org::max('id');
    $fieldrep_org_id++;
    return view('admin.fieldrep_orgs.create_fieldreporg',compact('fieldrep_org_id'));
  }

  public function store(Request $request){

    $this->validate($request, [
      "fieldrep_org_name"   =>  "required",  
      ]);

    if($request->input('id')==''){
      $fieldrep_org = $request->all();
        //$site['chains'] = implode(',',$site['chains']);
      $fieldrep_org = FieldRep_Org::create($fieldrep_org);            
      return redirect('fieldreporgs')->with('success', 'FieldRep Org added successfully!');
    }
    else{
      $fieldrep_org = FieldRep_Org::where(['id'=>$request->input('id')])->first();
      $fieldrep_org->update($request->except(['_token']));
      return redirect('fieldreporgs')->with('success', 'FieldRep Org saved successfully!');
    }
  } 

  public function edit(Request $request,$id){

    $fieldrep_org = FieldRep_Org::findorFail($id);

    $fieldrep_org->updated = date_formats(AppHelper::getLocalTimeZone($fieldrep_org->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $fieldrep_org->created = date_formats(AppHelper::getLocalTimeZone($fieldrep_org->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 

    $app = new AppData;

    $entity_type = $app->entity_types['rep_org'];

    $contact_types = ['' => 'Select Contact'] + $app->contact_types['rep_org']; //// get contact types of clients

    $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');

    $data = [
    'fieldrep_org'    =>  $fieldrep_org,
    'entity_type'     =>  $entity_type,
    'contact_types'   =>  $contact_types,
    'states'          =>  $states
    ];

    return view('admin.fieldrep_orgs.create_fieldreporg', $data);      
    //return view('admin.fieldrep_orgs.create_fieldreporg',compact('fieldrep_org','entity_type','contact_types'));      
  }


  public function getdata(Request $request){

        //$fieldrep_orgs = FieldRep_Org::select(['id','fieldrep_org_name','notes','status']);
       // $fieldrep_list = ['' => 'Select FieldRep'] + FieldRep::select(DB::raw("concat(first_name,' ',last_name) as full_name, id"))->lists('full_name', 'id')->all();

    $fieldrep_orgs = DB::table('fieldrep_orgs as f_org')
    // ->leftjoin('fieldreps as f','f.organization_name','=','f_org.id')
    ->leftjoin('contacts as co', function ($join) {
      $join->on('co.reference_id', '=', 'f_org.id')
      ->where('co.entity_type', '=', '5')
      ->where('co.contact_type', '=', 'primary');
    })
    ->select([
      'f_org.id',
      'f_org.fieldrep_org_name',
      DB::raw('(select COUNT(id)  from fieldreps where organization_name = f_org.id) as rep_count'),
      'co.city',
      'co.state',
      'co.zipcode',
      'f_org.status'])
    ->groupBy('f_org.id');    


    if($request->order){
      if($request->columns[$request->order[0]['column']]['name'] == 'rep_count'){
        $fieldrep_orgs->orderBy('rep_count', $request->order[0]['dir']);
      }
    }

    $datatables = Datatables::of($fieldrep_orgs)
    ->addColumn('action', function ($fieldrep_orgs) {
      return '<button class="btn btn-box-tool" type="submit" name="remove_fieldreporg" data-id="'.$fieldrep_orgs->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
    })
    ->editColumn('id', function ($fieldrep_orgs) {
      return '<a href='.url("/fieldreporgs-edit/").'/'.$fieldrep_orgs->id.'>'. format_code($fieldrep_orgs->id) .'</a>';
    })
    ->editColumn('location', function ($fieldrep_orgs) {
      return format_location($fieldrep_orgs->city,$fieldrep_orgs->state,$fieldrep_orgs->zipcode);
    })

    ->editColumn('status', function ($fieldrep_orgs) {
      if($fieldrep_orgs->status == 1){
        return '<span class="label label-success">Active</span>';
      }else{
        return '<span class="label label-danger">Inactive</span>';
      }
    });

    if ($id = $request->get('fieldreporg_id')) {
      $datatables->where('f_org.id', 'like', "$id%"); // additional users.name search
    }

    if ($request->get('status') != ''  ) {
      $status = $request->get('status');
      $datatables->where('f_org.status', 'like', "$status%"); 
    }

    if ($request->get('state') != ''  ) {
      $state = $request->get('state');
      $datatables->where('co.state', 'like', "$state%"); 
    }

    $keyword = $request->get('search')['value'];

    if (preg_match("/^".$keyword."/i", 'Active', $match)) :
      $datatables->filterColumn('f_org.status', 'where', '=', "1");
    endif;

    if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
      $datatables->filterColumn('f_org.status', 'where', '=', "0");
    endif;

    $datatables->filterColumn('co.city', 'whereRaw', "CONCAT(co.city,',',co.state,' ',co.zipcode) like ? ", ["%$keyword%"]);

    return $datatables->make(true);
  }

  function deleteFieldRepOrg(Request $request){
    try{
      $fieldreporg = FieldRep_Org::find($request->input('id'));
      $fieldreporg->delete();

      Contact::where(['entity_type' => 5, 'reference_id' => $request->input('id')])->delete();

      return response()->json(array(
       "status" => "success",
       "message"=>"FieldRep Org removed successfully",
       ));   
    }catch(Exception $e){
      if($e instanceof \PDOException )
      {
        $error_code = $e->getCode();
        if($error_code == 23000){
          return response()->json([ 'message' => "Organization can not be deleted, it has Fieldreps" ], 422);
        }
      }
    }
  }
}
