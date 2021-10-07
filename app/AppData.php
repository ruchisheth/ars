<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppData extends Model
{
    public $entity_types = [
    'client' => 1,
    'chain' => 2,
    'site' => 3,
    'rep' => 4,
    'rep_org'  =>5,
    ];

    public $contact_types = [
    'client' => [
    'Primary' => 'Primary',
    ],
    'chain' => [
    'Primary' => 'Primary',
    'Feedback' => 'FeedBack Contact Email',
    ],
    'site' => [
    'Primary' => 'Primary',

    ],
    'rep' => [
    'Primary' => 'Primary',
    'PO' => 'PO',
    'Shipping' => 'Shipping',
    ],
    'rep_org'  => [
    'Primary'   =>  'Primary',
    ]        
    ];

    public static $project_types = [

        'Special Project' => 'merchandise_specialproject',
        'Reset' => 'merchandise_reset',
        'Product Cut-in' => 'merchandise_product_cutin',
        'New Store Setup'=> 'merchandise_new_store_setup',
        'Display Setup'=> 'merchandise_display_setup',
        'Continuity/Product Re-order'=> 'merchandise_continuity',
        'Product Sampling'=> 'demoevent_product_sampling',
        'In-Person Mystery Shopping' => 'mystery_inperson_shopping',
        'Phone Shops' => 'mystery_shopping_phone_shops',
    ];

    public $days = [

        'Monday' =>['availability' => [0,0,0]],
        'Tuesday' => ['availability' => [0,0,0]],
        'Wednesday' => 
        [
        'availability' => [0,0,0]
        ],
        'Thursday' => 
        [
        'availability' => [0,0,0]
        ],
        'Friday' =>
        [
        'availability' => [0,0,0]
        ],                     
        'Saturday' => 
        [
        'availability' => [0,0,0]
        ],
        'Sunday' => 
        [
        'availability' => [0,0,0]
        ],
    ];
}
