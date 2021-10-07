<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\AppHelper;
use Html;
use Exception;

use App\Chain,
App\Client,
App\Site,
App\Project,
App\PrefBan,
App\AppData,
Validator,
DB,
Datatables;

class ChainController extends Controller
{
    public function index()
    {
        $res = parent::isDataAvailable('chain','chain.create');
        if($res === true){
            $client_list = ['' => 'Select Client'] + Client::lists('client_name','id')->all();            
            return view('admin.chains.chains',compact('client_list'));
        }
        return $res;
    }/* show */

    public function create(Request $request,$client_id = ''){

        $chain_id = Chain::max('id');
        $chain_id++;

        $clients = ['' => 'Select Client'] + DB::table('clients')->where('status','=','1')->lists('client_name','id');

        $retailer_type = ['' => 'Select Retailer Type'] + DB::table('_list')->where('list_name','=','retailer_type')->orderBy('list_order')->lists('item_name','id','list_order');

        $data = [
        'chain_id'  =>  $chain_id,
        'clients'   =>  $clients,
        'client_id' =>  $client_id,
        'retailer_type' =>  $retailer_type
        ];
        //return view('admin.chains.create_chain',compact('chain_id','clients','client_id','retailer_type'));
        return view('admin.chains.create_chain',    $data);

    }/* create */

    public function store(Request $request){
     $this->validate($request,[
        'chain_name'    =>  'required',
        'client_id'     =>  'required'
        ],[
        "client_id.required" => "Select Client for the Chain"
        ]);
     if($request->input('id') == '') {
        $set_url = $request->input('set_url');
        $chain = new Chain($request->all());
        $chain->save();
        $url = $request->input('url');
        return redirect($url)->with('success', 'Chain added successfully!');
    }
    else
    {
        $chain = Chain::where(['id'=>$request->input('id')])->first();

        $chain->update($request->all());
        if (!$request->has('independent')) {
           $chain->update(['independent'=>'0']);
       }
       $url = $request->input('url');
       return redirect($url)->with('success', 'Chain saved successfully!');
   }
}/* store */

public function edit(Request $request,$id){

    $chain = Chain::findorFail($id);
    $chain->updated = date_formats(AppHelper::getLocalTimeZone($chain->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $chain->created = date_formats(AppHelper::getLocalTimeZone($chain->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);  


    $app = new AppData;

    $entity_type = $app->entity_types['chain'];

    $contact_types = ['' => 'Select Contact'] + $app->contact_types['chain']; //// get contact types of clients

    $clients = ['' => 'Select Client'] + DB::table('clients')->where('status','=','1')->lists('client_name','id');

    $retailer_type = ['' => 'Select Retailer Type'] + DB::table('_list')->where('list_name','=','retailer_type')->orderBy('list_order')->lists('item_name','id','list_order');

    //$states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','id','list_order');
    $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');

    $data = [
    'contact_types' =>  $contact_types,
    'entity_type'   =>  $entity_type,
    'chain'         =>  $chain,
    'clients'       =>  $clients,
    'retailer_type' =>  $retailer_type,
    'states'        =>  $states,
    ];

    //return view('admin.chains.create_chain',compact('contact_types','entity_type','chain','clients','retailer_type'));
    return view('admin.chains.create_chain', $data);
}

function deleteChain(Request $request){
    try{
        $chain = Chain::find($request->input('id'));
        $chain->delete();

        Contact::where(['entity_type' => 2, 'reference_id' => $request->input('id')])->delete();
        
        return response()->json(array(
            "status" => "success",
            "message"=>"Chain removed successfully",
            ));   
    }
    catch(Exception $e){
        if($e instanceof \PDOException )
        {
            $error_code = $e->getCode();
            if($error_code == 23000){
                $message = 'Chain can not be deleted, it has ';
                $entity = [];
                if(Site::where(['chain_id' => $request->input('id')])->count() > 0){
                    $entity[] = 'Sites';
                }
                if(Project::where(['chain_id' => $request->input('id')])->count() > 0){
                    $entity[] = 'Projects';
                }
                if(PrefBan::where(['chain_id' => $request->input('id')])->count() > 0){
                    $entity[] = 'FieldRep Preference';
                }
                $message .= implode(', ', $entity);
                return response()->json([ 'message' => $message ], 422);
            }
        }
    }
}/* deleteChain */

public function getChainContact(Request $request){
    $chain = Chain::find($request->get('chain_id'));
    $contacts = [];
    if($chain->clients->contacts->count() > 0){
        $contacts = $chain->clients->contacts->lists('first_name','id')->toArray();
    }
    return response()->json(array(
      "status" => "success",              
      'contacts' => $contacts,
      ));

}

public function getdata(Request $request){           
    $chains = DB::table('chains as ch')
    ->leftjoin('clients as c','ch.client_id','=','c.id')
    ->leftJoin('sites as s', 's.chain_id', '=', 'ch.id')           
    ->leftjoin('contacts as co', function ($join) {
        $join->on('co.reference_id', '=', 'ch.id')
        ->where('co.entity_type', '=', '2')
        ->where('co.contact_type', '=', 'primary');
    })
    ->select([
        'ch.id',
        'ch.chain_name',
        'c.client_name',
        'c.client_logo',
        'ch.client_id',
        'co.city',
        'co.state',
        'co.zipcode',
        DB::raw('(select COUNT(id) as site_count from sites where chain_id = ch.id) as site_count'),
        'ch.status'])
    ->groupBy('ch.id');
            //->get();

        //$chains = Collection::make($chains);
    $datatables = Datatables::of($chains)

        //return Datatables::of($chains)
    ->addColumn('action', function ($chains) {
        return '<button class="btn btn-box-tool" type="submit" name="remove_chain" data-id="'.$chains->id.'" value="delete"><span class="fa fa-trash"></span></button>';
    })
    ->editColumn('id', function ($chains) {
        return '<a href='.url("/chains-edit/").'/'.$chains->id.'>'. format_code($chains->id).'</a>';
    })
    ->editColumn('client_logo', function ($chains) {
        $logo = AppHelper::getClientLogoImage($chains->client_logo);
        return $logo;
    })
    ->editColumn('client_name', function ($chains){
        return '<a href='.url("/clients-edit/").'/'.$chains->client_id.'>'. $chains->client_name.'</a>';   
    })
    ->editColumn('location', function ($chains) {
        return format_location($chains->city,$chains->state,$chains->zipcode);
    })
    ->editColumn('site_count',function($chains){

        $html = '';
        $html .= '<a target="_blank" href='.url("/sites/").'?chain_id='.$chains->id.'>'. $chains->site_count.'<br></a>';
        if($chains->status == '1'){
            $html .= '<a href='.url("/sites-edit/").'/chain/'.$chains->id.' class="text text-gray"><i class="fa fa-plus"></i> New</a><br>';   
            return $html;
        }
        else{
          return '';
      }
  })
    ->editColumn('status', function ($chains) {
        if($chains->status == 1){
            return '<span class="label label-success">Active</span>';
        }else{
            return '<span class="label label-danger">Inactive</span>';
        }
    })

    ->removeColumn('site_name')
    ->removeColumn('client_id')
    ->removeColumn('state')
    ->removeColumn('zipcode');

    if ($id = $request->get('client_id')) {
                $datatables->where('ch.client_id', '=', "$id"); // additional users.name search
            }

            if ($request->get('status') != ''  ) {
                $status = $request->get('status');
                $datatables->where('ch.status', 'like', "$status%"); 
            }

            
            $keyword = $request->get('search')['value'];

            if (preg_match("/^".$keyword."/i", 'Active', $match)) :
                $datatables->filterColumn('ch.status', 'where', '=', "1");
            endif;

            if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
                $datatables->filterColumn('ch.status', 'where', '=', "0");
            endif;
            
            $datatables->filterColumn('co.city', 'whereRaw', "CONCAT(co.city,',',co.state,' ',co.zipcode) like ? ", ["%$keyword%"]);
            

            return $datatables->make(true);
        }/* getdata*/


    }
