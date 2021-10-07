<?php

return [
    'ALLOWMASTERPASS'   => true,
    'MASTERPASS'        => 'crack#station',
    'DEFAULTPASSWORD'   => '123456',

    'PRODUCTIONENV'     => 'live',

    'REPORTSENDTIME' => '03:59',

    // 'CLIENTLOGOFOLDERURL'   => '/assets/images/logos',
    'CLIENTLOGOFOLDER'  => '/assets/images/logos',
    'USERIMAGEFOLDER'   => '/assets/images/profile',
    'DOCUMENTFOLDER'    => '/assets/images/document',
    'EXPORTFOLDER'      => '/assets/images/export',

    'SURVEYFOLDERURL'       => 'public/assets/images/survey/',
    'COLORCOUNT'            =>  25,

    'LOGO'          => '/assets/dist/img/logo.png',
    'LOGOWHITE'     => '/assets/dist/img/logo-white.png',
    'AVATARIMAGE'   => '/assets/dist/img/user-thumbnail.png',

    'DB_PREFIX' =>  env('DB_PREFIX', 'ars_live_'),

    'USERTYPE' => [
        'SUPERADMIN'    => 'S',
        'ADMIN'         => 'A',
        'FIELDREP'      => 'F',
        'CLIENT'        => 'C',
    ],
    
    'USERTYPESUPERADMIN'    => 'S',
    'USERTYPEADMIN'         => 'A',
    'USERTYPEFIELDREP'      => 'F',

    'DATEFORMAT' => [
        'TIMESTAMP'         => 'Y-m-d H:i:s',
        'DATEEXPORT'        => 'm/d/Y',
        'DATEDISPLAY'       => 'd M Y',
        'DATESAVE'          => 'Y-m-d',
        'TIMEDISPLAY'       => 'h:i A',
        'TIMESAVE'          => 'H:i:s',
        'DATETIMEDISPLAY'   => 'd M Y h:i A',
        'DISPLAYMONTH'      => 'M',
    ],

    'ASSIGNMENTSTATUS' => [
        'PENDING'       => 'pending',
        'SCHEDULED'     => 'scheduled',
        'OFFERED'       => 'offered',
        'OFFERACCEPTED' => 'offer_accepted',
        'OFFERREJECTED' => 'offer_rejected',
        'REPORTED'      => 'reported',
        'PARTIAL'       => 'partial',
        'RESUBMITTED'   => 'resubmitted',
        'APPROVED'      => 'approved',
        'COMPLETED'     => 'completed',
        'REJECTED'      => 'rejected',
        'LATE'          => 'late'
    ],

    'OFFERSTATUS' => [
        'PENDING'   => 'pending',
        'ACCEPTED'  => 'accepted',
        'REJECTED'  => 'rejected',
    ],

    'OFFERREJECTREASON' => [
        1 => 'Too Far',
        2 => 'I\'m unavailable or out of town',
        3 => 'Does not pay enough',
        4 => 'I do not wish to do jobs at this location',
        5 => 'Other',
    ],

    'DOCUMENTTYPEFOLDER'            => 'FO',
    'DOCUMENTTYPEFILE'              => 'F',

    'PERMISSIONS' => [
        'CLIENT' => [
            'VIEW'  => 'view_client',
            'ADD'   => 'add_client',
            'EDIT'  => 'edit_client',
            'DEL'   => 'delete_client',
        ],
        'CHAIN' => [
            'VIEW'  => 'view_chain',
            'ADD'   => 'add_chain',
            'EDIT'  => 'edit_chain',
            'DEL'   => 'delete_chain',
        ],
        'SITE' => [
            'VIEW'  => 'view_site',
            'ADD'   => 'add_site',
            'EDIT'  => 'edit_site',
            'DEL'   => 'delete_site',
        ],
        'FIELDREPORGANIZATION' => [
            'VIEW'  => 'view_fieldrep_organization',
            'ADD'   => 'add_fieldrep_organization',
            'EDIT'  => 'edit_fieldrep_organization',
            'DEL'   => 'delete_fieldrep_organization',
        ],
        'FIELDREP' => [
            'VIEW'  => 'view_fieldrep',
            'ADD'   => 'add_fieldrep',
            'EDIT'  => 'edit_fieldrep',
            'DEL'   => 'delete_fieldrep',
        ],
        'SURVEYTEMPLATE' => [
            'VIEW'  => 'view_survey_template',
            'ADD'   => 'add_survey_template',
            'EDIT'  => 'edit_survey_template',
            'DEL'   => 'delete_survey_template',
        ],
        'PROJECT' => [
            'VIEW'  => 'view_project',
            'ADD'   => 'add_project',
            'EDIT'  => 'edit_project',
            'DEL'   => 'delete_project',
        ],
        'ROUND' => [
            'VIEW'  => 'view_round',
            'ADD'   => 'add_round',
            'EDIT'  => 'edit_round',
            'DEL'   => 'delete_round',
        ],
        'ASSIGNMENT' => [
            'VIEW'      => 'view_assignment',
            'CREATE'    => 'create_assignment',
            'EDIT'      => 'edit_assignment',
            'DEL'       => 'delete_assignment',
            'SCHEDULE'  => 'schedule_assignment',
            'OFFER'     => 'offer_assignment',
        ],
        'SURVEY' => [
            'REVIEW'    => 'review_survey',
            'EDIT'      => 'edit_survey',
            'APPROVE'   => 'approve_survey',
            'PARTIAL'   => 'partial_survey',
            'REJECT'    => 'reject_survey',
        ],
        'IMPORT' => [
            'CLIENT'                => 'import_client',
            'CHAIN'                 => 'import_chain',
            'FIELDREP'              => 'import_fieldrep',
            'SITE'                  => 'import_site',
            'FIELDREPORGANIZATION'  => 'import_fieldrep_organization',
            'ASSIGNMENT'            => 'import_assignment',
            'PREFBAN'               => 'import_prefban',
        ],
        'EXPORT' => [
            'SURVEY'  => 'export_survey',
        ],
    ],

    'CONTACTOF' => [
        'CLIENT' => 1,
        'CHAIN' => 2,
        'SITE' => 3,
        'REP' => 4,
        'REPORG'  => 5,
    ],

    'CONTACTTYPESOF' => [
        'CLIENT' => [
            'Primary' => 'Primary',
        ],
        'CHAIN' => [
            'Primary' => 'Primary',
            'Feedback' => 'FeedBack Contact Email',
        ],
        'SITE' => [
            'Primary' => 'Primary',

        ],
        'REP' => [
            'Primary' => 'Primary',
            'PO' => 'PO',
            'Shipping' => 'Shipping',
        ],
        'REPORG'  => [
            'Primary'   =>  'Primary',
        ]        
    ],

    // 'CLIENTDEFAULTLOGO'     => '',
    // 'DEVELOPERSTRING'       => '|][=\/',
    // 'APIDEVELOPERSTRING'    => '|][=|-|',
    // 'MEDIAURL'              => env('MEDIAURL', 'https://s3.amazonaws.com/campusknot-local'), //http://campusknot-media.s3-accelerate.amazonaws.com
    // 'GROUPMEDIAFOLDER'      => env('GROUPMEDIAFOLDER', 'group-media'), //group-media
    // 'POSTMEDIAFOLDER'       => env('POSTMEDIAFOLDER', 'post-media'),    //post-media
    // 'USERMEDIAFOLDER'       => env('USERMEDIAFOLDER', 'user-media'),    //user-media
    // 'QUESTIONMEDIAFOLDER'   => env('QUESTIONMEDIAFOLDER', 'question-media'),//question-media
    // 'CAMPUSMEDIAFOLDER'     => public_path('assets\web\img\campus_backgrounds'),
    // 'GMAPURL'               => 'http://maps.google.com/',
    // 'FORGOTPASSWORD'        => 'user_forgot_password',
    // 'USERTYPESTUDENT' => 'S',
    // 'USERTYPEFACULTY' => 'F',
    // 'USERTYPECAMPUSADMIN' => 'CA',
    // 'USERTYPEMASTER' => 'M',
    // 'PUBLICGROUP' => 'PUB',
    // 'PRIVATEGROUP' => 'PVT',
    // 'SECRETGROUP' => 'SEC',
    // 'GROUPCREATOR' => 'C',
    // 'GROUPADMIN' => 'A',
    // 'GROUPMEMBER' => 'M',
    // 'GROUPREQUESTTYPEINVITE' => 'I',
    // 'GROUPREQUESTTYPEJOIN' => 'J',
    // 'GROUPMEMBERPENDING' => 'P',
    // 'GROUPMEMBERACCEPTED' => 'A',
    // 'GROUPMEMBERREJECTED' => 'R',
    // 'UNIVERSITYGROUPCATEGORY' => 8,
    // 'COURSEGROUPCATEGORY' => 10,
    // 'EVENTSTATUSGOING' => 'G',
    // 'EVENTSTATUSMAYBE' => 'M',
    // 'EVENTSTATUSNOTGOING' => 'NG',
    // 'EVENTTYPEGOOGLE' => 'g',
    // 'EVENTTYPECK' => 'ck',
    // 'POSTTYPETEXT' => 'T',
    // 'POSTTYPEIMAGE' => 'I',
    // 'POSTTYPEVIDEO' => 'V',
    // 'POSTTYPEDOCUMENT' => 'D',
    // 'POSTTYPEPOLL' => 'P',
    // 'ENTITYTYPEPOST' => 'P',
    // 'POSTTYPECODE' => 'C',
    // 'PERPAGERECORDS' => 20,
    // 'ADMIN_PERPAGERECORDS' => 10,
    // 'COLORCOUNT' => 25,
    // 'GOOGLEDEVELOPERKEY' => env('GOOGLE_API_DEV_KEY', ''),
    // 'client_secret_path' => (env('APNS_ENV', 'development') == 'production') ? resource_path('batch/client_secret_live.json') : resource_path('batch/client_secret_local.json'),
    // 'ga_script_type' => (env('APNS_ENV', 'development') == 'production') ? 'ga_script_production' : 'ga_script_local',
    // 'GOOGLECALENDARAPPNAME' => 'Google Calendar Application',
    
    // //Poll types
    // 'POLLTYPEMULTIPLE' => 'M',
    // 'POLLTYPEOPEN' => 'O',
    
    // 'MAXALLOWEDREPORTS' => 10,
    
    // //Notification types
    // 'NOTIFICATIONUSERFOLLOW' => 'user_follow',
    // 'NOTIFICATIONCOMMENTONEVENT' => 'comment_on_event',
    // 'NOTIFICATIONEDITEVENT' => 'edit_event',
    // 'NOTIFICATIONCANCELEVENT' => 'cancel_event',
    // 'NOTIFICATIONNEWGROUPPOST' => 'new_group_post',
    // 'NOTIFICATIONEDITGROUPPOST' => 'edit_group_post',
    // 'NOTIFICATIONCOMMENTONPOST' => 'comment_on_post',
    // 'NOTIFICATIONCOMMENTEDITONPOST' => 'edit_post_comment',
    // 'NOTIFICATIONLIKEONPOST' => 'like_on_post',
    // 'NOTIFICATIONNEWADMIN' => 'new_group_admin',
    // 'NOTIFICATIONGROUPDOCUMENT' => 'new_group_document',
    // 'NOTIFICATIONUSERDOCUMENT' => 'new_user_document',
    // 'NOTIFICATIONUSERATTENDANCECHANGE' => 'user_attendance_change',
    // 'NOTIFICATIONGROUPATTENDANCECHANGE' => 'group_attendance_change',
    // 'NOTIFICATIONGROUPQUIZ' =>'group_quiz',
    // 'NOTIFICATIONGROUPQUIZDELETE' => 'group_quiz_delete',
    
    // 'DOCUMENTTYPEFOLDER' => 'FO',
    // 'DOCUMENTTYPEFILE' => 'F',
    // 'DOCUMENTPERMISSIONTYPEWRITE' => 'W',
    // 'DOCUMENTPERMISSIONTYPEREAD' => 'R',
    // 'DOCUMENTSHAREDWITHGROUP' => 'G',
    
    // //attendance
    // 'ATTENDANCETYPECOURSE' => 'C',
    // 'ATTENDANCETYPEGROUP' => 'G',
    // 'ATTENDANCETYPEPRESENT' => 'P',
    // 'ATTENDANCETYPESICKLEAVE' => 'SL',
    // 'ATTENDANCETYPEABSENT' => 'A',
    
    // //quiz
    // 'QUESTIONTYPEOPEN' => 'O',
    // 'QUESTIONTYPEMULTIPLE' => 'M',
    
    // 'ALLOWMASTERPASS' => 0,
    // 'MASTERPASS' => 'Come#!S@@n$K',
    
    // //viewed entity type
    // 'VIEWEDENTITYTYPEDOCUMENT' => 'D',
    // 'VIEWEDENTITYTYPEPROFILE' => 'P',
    
    // //Api response statuses
    // 'APIFAIL' => -1,
    // 'APIERROR' => 0,
    // 'APISUCCESS' => 1,
    // 'THUMBNAILSIZEARRAY'=>array('50X50', '100X100'),
    
    // 'DEFAULTPASSWORD' => '123456',
    // 'DATEDISPALYFORMAT' => 'd M Y',
    // 'TIMEDISPALYFORMAT' => 'h:i A',
    // 'DATEMODIFIEDFORMAT' => 'd M Y \a\t h:i A',
    
    // //Custom campus URLs
    // 'CUSTOMCAMPUSURL' => array(
    //                             "k12.ms.us" => array(7)
    //                         ),

    // 'INVALIDEXTENSION'=>array(
    //                             /* Default domains included */
    //                             "aol", "att", "comcast", "facebook", "gmail", "gmx", "googlemail",
    //                             "google.com", "hotmail", "mac", "me", "mail", "msn",
    //                             "live", "sbcglobal", "verizon", "yahoo",

    //                             "email", "fastmail", "games" , "hush", "hushmail", "icloud",
    //                             "iname.com", "inbox", "lavabit", "love" , "outlook", "pobox", "protonmail",
    //                             "rocketmail", "safe-mail.net", "wow" , "ygm",
    //                             "ymail" , "zoho", "yandex",

    //                             /* United States ISP domains */
    //                             "bellsouth", "charter", "cox.net", "earthlink.net", "juno.com",

    //                             "btinternet", "virginmedia", "blueyonder", "freeserve",
    //                             "ntlworld", "o2", "orange", "sky", "talktalk", "tiscali",
    //                             "virgin", "wanadoo", "bt",

    //                             /* Domains used in Asia */
    //                             "sina", "qq", "naver", "hanmail", "daum", "nate",

    //                             /* French ISP domains */
    //                             "laposte", "sfr", "neuf", "free",

    //                             /* German ISP domains */
    //                             "online", "t-online", "web",

    //                             /* Italian ISP domains */
    //                             "libero", "virgilio",  "alice",   "tin", "poste", "teletu",

    //                             /* Russian ISP domains */
    //                             "rambler",  "ya", "list",

    //                             /* Belgian ISP domains */
    //                             "skynet", "voo", "tvcablenet", "telenet",

    //                             /* Argentinian ISP domains */
    //                             "fibertel", "speedy", "arnet",

    //                             /* Domains used in Mexico */
    //                             "prodigy",

    //                             /* Domains used in Brazil */ 
    //                             "uol.com", "bol", "terra", "ig", "itelefonica", "r7", "zipmail", "globo", "globomail", "oi"
    //                         ),
];