<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\AppHelper;

use App\Contact,
App\AppData,
App\Client,
App\Chain,
App\Site,
App\FieldRep,
App\FieldRep_Org,
Datatables;

class ContactController extends Controller
{
  public function store(Request $request)
  {
    //dd($request->all());
    $this->validate($request, 
      [
      'first_name'  =>  'required',
      'last_name'   =>  'required',
      'address1'    =>  'required_unless:contact_type,Feedback',
      'city'        =>  'required_unless:contact_type,Feedback',
      'state'       =>  'required_unless:contact_type,Feedback',
      'zipcode'     =>  'required_unless:contact_type,Feedback',
      'email'       =>  'required_if:contact_type,Feedback|email',
      ],[
        'email.required_if'     =>  'The Email field is required.',
        'address1.required_unless'  =>  'The Address 1 field is required.',
        'city.required_unless'      =>  'The City field is required.',
        'state.required_unless'     =>  'The State field is required.',
        'zipcode.required_unless'   =>  'The Zipcode field is required.',
      ]);
      //'email.email' => 'Email Address must be valid email address',

    $inputs = $request->all();

    $address = $inputs['address1'].' '.$inputs['address2'].' '.$inputs['city'].' '.$inputs['state'].' '.$inputs['zipcode'];
    $location=AppHelper::getLatlong($address);
    $inputs['lat']  = $location['lat']; 
    $inputs['long'] = $location['long'];

    if($request->input('id') == '' || $request->input('id') == '0'){
      //$contact = new Contact($request->except(['_token']));
      $contact = new Contact($inputs);
      $contact->save();
      return response()->json(array(
        "status" => "success",
        "message"=>"Contact added successfully",
        ));
    }
    else{
      $contact = Contact::where(['id'=>$request->input('id')])->first(); 
      $contact->update($inputs);

     return response()->json(array(
      "status" => "success",
      "message"=>"Contact saved successfully",
      ));
   } /* else */
 }


 public function edit(Request $request,$contact_id){

  $inputs = Contact::find($contact_id);

  $inputs->updated = date_formats(AppHelper::getLocalTimeZone($inputs->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
  $inputs->created = date_formats(AppHelper::getLocalTimeZone($inputs->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

  $filteredArr = [
  'id'=>["type"=>"hidden",'value'=>$inputs->id],
  'type'=>["type"=>"hidden",'value'=>$inputs->entity_type],
  'contact_type'=>["type"=>"select",'value'=>$inputs->contact_type],
  'contact_type_other'=>["type"=>"text",'value'=>$inputs->contact_type_other],
  'reference_id'=>["type"=>"hidden",'value'=>$inputs->reference_id],
  'first_name'=>["type"=>"text",'value'=>$inputs->first_name],
  'last_name'=>["type"=>"text",'value'=>$inputs->last_name],
  'initial'=>["type"=>"text",'value'=>$inputs->initial],
  'organization'=>["type"=>"text",'value'=>$inputs->organization],
  'title'=>["type"=>"text",'value'=>$inputs->title],
  'email'=>["type"=>"text",'value'=>$inputs->email],
  'phone_number'=>["type"=>"text",'value'=>$inputs->phone_number],
  'fax_number'=>["type"=>"text",'value'=>$inputs->fax_number],
  'cell_number'=>["type"=>"text",'value'=>$inputs->cell_number],
  'pager_number'=>["type"=>"text",'value'=>$inputs->pager_number],
  'address1'=>["type"=>"text",'value'=>$inputs->address1],
  'address2'=>["type"=>"text",'value'=>$inputs->address2],
  'city'=>["type"=>"text",'value'=>$inputs->city],
  'state'=>["type"=>"select",'value'=>$inputs->state,'wait'=>'1'],
  'zipcode'=>["type"=>"text",'value'=>$inputs->zipcode],
  'corporate_address'=>["type"=>"checkbox",'checkedValue'=>$inputs->corporate_address],
  'product_name'=>["type"=>"text",'value'=>$inputs->product_name],
  'notes'=>["type"=>"textarea",'value'=>$inputs->notes],
  'created_at'=>["type"=>"label",'value'=>$inputs->created],
  'updated_at'=>["type"=>"label",'value'=>$inputs->updated],
  ];

  return response()->json(array(
   "status" => "success",
   "inputs"=>$filteredArr,
   ));
}

function deleteContact(Request $request){

  $contact = Contact::find($request->input('id'));
  $contact->delete();
  return response()->json(array(
    "status" => "success",
    "message"=>"Contact removed successfully",
    ));   
}/* deleteContact*/


public function getdata($id,$entity_type){

  if($entity_type == 1){
    $refObj = Client::find($id);
  }else if($entity_type == 2){
    $refObj = Chain::find($id);
  }else if($entity_type == 3){
    $refObj = Site::find($id);
  }else if($entity_type == 4){
    $refObj = FieldRep::find($id);
  }  else if($entity_type == 5){
    $refObj = FieldRep_Org::find($id);
  }


  $contacts = $refObj->contacts()->where('entity_type', $entity_type)
  ->get(['id','first_name', 'last_name','city','state','zipcode','email','phone_number','contact_type','contact_type_other']);

  return Datatables::of($contacts)
  ->addColumn('action', function ($contacts) {
    return '<button class="btn btn-box-tool" type="submit" name="remove_contact" data-id="'.$contacts->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
  })

  ->editColumn('first_name', function ($contacts) {
    $app = new AppData;
    $html  = '';
    $html .= '<a href="#" onclick="SetContactEdit(this,event)" data-id='.$contacts->id.'>'. $contacts->first_name.' '.$contacts->last_name.'</a>';
    $html .= '<br>';


    if($contacts->contact_type != ''){
      $html .= '<span class="label label-primary">'.ucfirst($contacts->contact_type).'</span>';
    }
    else if($contacts->contact_type_other != ''){
      $html .= '<span class="label label-primary">'.ucfirst($contacts->contact_type_other).'</span>';
    }
    return $html;
  })
  ->editColumn('city', function ($contacts) {
    return format_location($contacts->city,$contacts->state,$contacts->zipcode);
  })
  ->editColumn('email', function ($contacts) {
    $html = '';
    if($contacts->email != ''){
      $html .= '<span class="btn btn-box-tool"><i class="fa fa-envelope"></i> </span><a href="mailto:'. $contacts->email.'" data-id='.$contacts->id.'>'. $contacts->email.'</a><br>';
                //return '<a href="mailto:'. $contacts->email.'" data-id='.$contacts->id.'>'. $contacts->email.'</a>';
    }
    if($contacts->phone_number != ''){
      $html .= '<span class="btn btn-box-tool"><i class="fa fa-phone"></i></span>'.$contacts->phone_number;
    }
    return $html;

  })
  ->removeColumn('id')
  ->removeColumn('last_name')
  ->removeColumn('state')
  ->removeColumn('zipcode')
  ->removeColumn('phone_number')
  ->removeColumn('contact_type')
  ->removeColumn('contact_type_other')
  ->make();
}
}
