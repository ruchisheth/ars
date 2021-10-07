<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\AppData;
use App\Contact;
use App\_List;
use App\ContactType;
use App\Chain;
use App\Site;
use App\Http\AppHelper;
use Validator;
use Excel;
use DB;

class Chain extends Model
{


    protected $fillable = ['client_id','chain_name', 'chain_abbrev','notes', 'retailer_type','status'];

    public function contacts()
    {
        return $this->hasMany(Contact::class,'reference_id');
    }

    public function clients()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function sites()
    {
        return $this->hasMany(Site::class,'chain_id');
    }

    public function importData($request)
    {
        if(Input::hasFile('importfile')){
            $res['error_status'] = false;

            $response['error'] = $res;
            $response['success_records'] = [];

            $path = Input::file('importfile')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            $clients = [];

            $indexed = ['chain_name', 'client_id', 'first_name','last_name','address1','address2','city','state','zip','phone','notes'];
            $validate_indexed = ['Chain Name', 'Client Code', 'First Name', 'Last Name','Address','Address 2','City','State','Zip Code','Phone', 'Notes'];

            if($data->count() == 0){
                $res['error_status'] = true;
                $res['err'][]['message'] = 'No data available to import.';
                $response['error'] = $res;
                return $response;
                //return $res;
            }

            if(count($indexed) != count(array_first($data->toArray()))){
                $res['error_status'] = true;
                $res['err'][]['message'][] = 'Number of columns and order must be same as preview format';
                $response['error'] = $res;
                return $response;
                //return $res;
            }

            $arr = array_map(function($v, $k) use($validate_indexed, $indexed){
                $arr['validating_arr'] = array_combine($validate_indexed, $k);
                $arr['contact_arr'] = array_combine($indexed, $k);
                $arr['contact_arr']['zipcode'] = $arr['contact_arr']['zip'];
                $arr['contact_arr']['phone_number'] = $arr['contact_arr']['phone'];
                $arr['contact_arr']['entity_type'] = 2;
                $arr['contact_arr']['contact_type'] = 'Primary';
                unset($arr['contact_arr']['zip']);
                unset($arr['contact_arr']['phone']);
                $arr['chain_arr']['details']['chain_name'] = $arr['contact_arr']['chain_name'];
                $arr['chain_arr']['details']['client_id'] = $arr['contact_arr']['client_id'];
                $arr['chain_arr']['details']['notes'] = $arr['contact_arr']['notes'];
                $arr['chain_arr']['contact'] = $arr['contact_arr'];

                unset($arr['chain_arr']['contact']['chain_name']);
                unset($arr['chain_arr']['contact']['client_id']);
                unset($arr['chain_arr']['contact']['notes']);
                return $arr;
            }, $data->toArray(), array_values($data->toArray()));


            $validate_data = array_pluck($arr, 'validating_arr');
            array_unshift($validate_data,"");
            array_unshift($validate_data,"");
            unset($validate_data[0]);
            unset($validate_data[1]);

            $chains_data = array_pluck($arr, 'chain_arr');
            array_unshift($chains_data,"");
            array_unshift($chains_data,"");
            unset($chains_data[0]);
            unset($chains_data[1]);

            $validator =  Validator::make($validate_data, [
                '*.Chain Name'  => 'required',
                '*.Client Code' => 'required|exists:clients,id',
                '*.First Name'  => 'required',
                '*.Last Name'   => 'required',
                '*.Address'     =>  'required',
                '*.City'        =>  'required',
                '*.State'       =>  'required',
                '*.Zip Code'    =>  'required|numeric_with_arr',
                '*.Phone'       =>  'regex:/^(?! )[[0-9]{3}-[0-9]{3}-[0-9]{4}]*$/',
                ],[
                '*.Chain Name.required'         => "Chain Name is required",
                '*.Client Code.required'        => "Client Code is required",
                '*.Client Code.exists'          => "Client with Client Code you have entered does not exists.",
                '*.First Name.required'         => "First Name is required",
                '*.Last Name.required'          => "Last Name is required",
                '*.Address.required'            => "Address is required",
                '*.City.required'               => "City is required",
                '*.State.required'              => "State is required",
                '*.Zip Code.required'           => "Zip Code is required",            
                '*.Zip Code.numeric_with_arr'   => "Zip Code must be a number",
                '*.Phone.regex'                 => "Phone number has invalid format ",
                // '*.Chain Name.required' => "Error at row :Attribute is required",
                ]);

            if ($validator->fails() || $res['error_status'] == true) {
                $messages = $validator->errors()->toArray();
                if(count($messages) > 0){
                    $res['error_status'] = true;

                    foreach ($messages as $key => $message) {
                        $row_number = $row_nums[] = explode('.', $key)[0];
                        $res['err'][$row_number]['row_number'] = $row_number;
                        unset($chains_data[$row_number]);
                        if(is_array($message)){
                            foreach ($message as $msg_key => $msg) {
                                $res['err'][$row_number]['message'][] = $msg;
                            }
                        }else{
                            $res['err'][$row_number]['message'][] = $message;
                        }
                    }

                }
                $response['error']  =  $res;
                
                //return $res;
            }

            foreach($chains_data as $key => $cd){

                $response['success_records'][] = $key - 2;

                $referance_id = Chain::create($cd['details'])->id;
                $cd['contact']['reference_id'] = $referance_id;
                Contact::create($cd['contact']);
            }

            return $response;
        }
    }
}
