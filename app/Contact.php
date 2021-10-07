<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Client;

use App\ContactType;

class Contact extends Model
{
	
    protected $fillable = ['entity_type','contact_type','contact_type_other','reference_id','first_name', 'last_name', 'initial','organization' ,'title', 'email', 'phone_number', 'fax_number', 'cell_number', 'pager_number', 'address1','address2','city','state','zipcode','billing_contact','lat','long','notes'];

}
