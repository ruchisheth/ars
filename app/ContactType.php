<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Contact;

use App\Client;

class ContactType extends Model
{
    public $types = [
    	'client' => 1,
    	'chain' => 2,
    	'site' => 3
    ];

    public $contact_types = [
        0   =>  '',
        1   =>  'Client Primary',
        2   =>  'Client',
        3   =>  'Chain Primary',
        4   =>  'Chain',
        5   =>  'Site',
        6   =>  'Rep',
        7   =>  'Rep Shipping',
        8   =>  'Rep PO',
        9   =>  'Other',
        'clinent' => [
            'primary' => 'Primary'
        ],
        'chain' => [
            'primary' => 'Primary'
        ],
        'rep' => [
            'primary' => 'Primary',
            'rep_po' => 'Rep PO',
            'rep shipping' => 'Rep Shipping'

        ]
    ];


    public $states = [
    	'AA' => 'AA', 'AK' => 'AK', 'AL' => 'AL', 'AP' => 'AP',
        'AR' => 'AR', 'AS' => 'AS', 'AZ' => 'AZ', 'CA' => 'CA',
        'CN' => 'CN', 'CO' => 'CO', 'CT' => 'CT', 'DC' => 'DC',
        'DE' => 'DE', 'FL' => 'FL', 'FM' => 'FM', 'GA' => 'GA', 
        'GU' => 'GU', 'HI' => 'HI', 'IA' => 'IA', 'ID' => 'ID', 
        'IL' => 'IL', 'IN' => 'IN', 'KS' => 'KS', 'KY' => 'KY', 
        'LA' => 'LA', 'MA' => 'MA', 'MD' => 'MD', 'ME' => 'ME', 
        'MH' => 'MH', 'MI' => 'MI', 'MN' => 'MN', 'MO' => 'MO', 
        'MP' => 'MP', 'MS' => 'MS', 'MT' => 'MT', 'NC' => 'NC', 
        'ND' => 'ND', 'NE' => 'NE', 'NH' => 'NH', 'NJ' => 'NJ', 
        'NM' => 'NM', 'NV' => 'NV', 'NY' => 'NY', 'OH' => 'OH', 
        'OK' => 'OK', 'OR' => 'OR', 'PA' => 'PA', 'PR' => 'PR', 
        'PW' => 'PW', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 
        'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VA' => 'VA', 
        'VI' => 'VI', 'VT' => 'VT', 'WA' => 'WA', 'WI' => 'WI', 
        'WV' => 'WV', 'WY' => 'WY', 'AB' => 'AB', 'BC' => 'BC', 
        'MB' => 'MB', 'NB' => 'NB', 'NL' => 'NL', 'NS' => 'NS', 
        'NT' => 'NT', 'NU' => 'NU', 'ON' => 'ON', 'PE' => 'PE', 
        'QC' => 'QC', 'SK' => 'SK', 'YT' => 'YT'
    ];

}
