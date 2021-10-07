<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="{{ (url()->current() == url('/projects') ? 'active' : '') }}">
        <a href="{{ url('/projects') }}">
          <i class="fa fa-th fa-lg"></i> <span>PROJECTS</span>
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['projects']}}</span>
          <a href="{{url('/projects-edit')}}"><span class="label pull-right add-new" data-entity-url="projects-edit"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>   
      
      <li class="{{ (url()->current() == url('/rounds') ? 'active' : '') }}">
        <a href="{{ url('/rounds') }}">
          <i class="fa fa-dot-circle-o fa-lg"></i> <span>ROUNDS</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['rounds']}}</span>
          <span class="label pull-right add-new" data-entity-url="rounds-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['rounds']}}</span>
          <a href="{{url('/rounds-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>      
      <li class="{{ (url()->current() == url('/assignments') ? 'active' : '') }}">
        <a href="{{ url('/assignments') }}">
          <i class="fa fa-check-square-o fa-lg"></i> <span>ASSIGNMENTS</span>
          <span class="label pull-right entities-counter">{{@$counter['assignments']}}</span>          
        </a>
      </li>
      <li class="{{ (url()->current() == url('/surveys') ? 'active' : '') }}">
        <a href="{{ url('/surveys') }}">
          <i class="fa fa-star-half-o fa-lg"></i> <span>SURVEYS</span><span class="label pull-right entities-counter">{{@$counter['surveys']}}</span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-text-o fa-lg"></i> <span>REPORTS</span>
          <span class="label entities-counter pull-right">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ (url()->current() == url('/reports/site-geolocations') ? 'active' : '') }}">
            <a href="{{url('/reports/site-geolocations')}}"><i class="fa fa-location-arrow"></i>Site GeoLocations</a>
          </li>
          <li class="{{ (url()->current() == url('/reports/fieldrep-geolocations') ? 'active' : '') }}">
            <a href="{{url('/reports/fieldrep-geolocations')}}"><i class="fa fa-location-arrow"></i>FieldRep GeoLocations</a>
          </li>
        </ul>
      </li>
      <li class="{{ (url()->current() == url('/document-list') ? 'active' : '') }}">
        <a href="{{ url('/document-list') }}">
          <i class="fa fa-folder fa-lg"></i> <span>{{ trans('messages.resources') }}</span><span class="label pull-right entities-counter"></span>
          <span class="badge badge-secondary label-danger">@lang('new')</span>
        </a>
      </li>
      <li class="{{ (url()->current() == url('/imports') ? 'active' : '') }}">
        <a href="{{url('/imports')}}">
          <i class="fa fa-download fa-lg"></i> <span>IMPORT</span>
        </a>
      </li>
      <li class="{{ (url()->current() == url('/exports') ? 'active' : '') }}">
        <a href="{{url('/exports')}}">
          <i class="fa fa-upload fa-lg"></i> <span>EXPORT</span>
        </a>
      </li>
      <li class="{{ (url()->current() == url('/settings') ? 'active' : '') }}">
        <a href="{{url('/settings')}}">
          <i class="fa fa-wrench fa-lg"></i> <span>SETTINGS</span>
        </a>
      </li>
      <li class="header">MANAGE</li>

      <li class="{{ (url()->current() == url('/clients') ? 'active' : '') }}">
        <a href="{{url('/clients')}}"><i class="fa fa-user fa-lg"></i>  <span>CLIENTS</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['clients']}}</span>
          <span class="label pull-right add-new" data-entity-url="clients-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['clients']}}</span>
          <a href="{{url('/clients-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>

      <li class="{{ (url()->current() == url('/chains') ? 'active' : '') }}">
        <a href="{{url('/chains')}}"><i class="fa fa-cube fa-lg"></i> 
          <span>CHAINS</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['chains']}}</span>
          <span class="label pull-right add-new" data-entity-url="chains-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['chains']}}</span>
          <a href="{{url('/chains-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>

      <li class="{{ (url()->current() == url('/sites') ? 'active' : '') }}">
        <a href="{{url('/sites')}}"><i class="fa fa-cubes fa-lg"></i>
          <span> SITES</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['sites']}}</span>
          <span class="label pull-right add-new" data-entity-url="sites-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['sites']}}</span>
          <a href="{{url('/sites-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>

      <li class="{{ (url()->current() == url('/fieldreporgs') ? 'active' : '') }}">
        <a href="{{url('/fieldreporgs')}}"><i class="fa fa-institution fa-lg"></i><span>FIELD REP ORGS</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['fieldrep_orgs']}}</span>
          <span class="label pull-right add-new" data-entity-url="fieldreporgs-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['fieldrep_orgs']}}</span>
          <a href="{{url('/fieldreporgs-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>


      <li class="{{ (url()->current() == url('/fieldreps') ? 'active' : '') }}">
        <a href="{{url('/fieldreps')}}"><i class="fa fa-group fa-lg"></i><span>FIELD REPS</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['fieldreps']}}</span>
          <span class="label pull-right add-new" data-entity-url="fieldreps-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['fieldreps']}}</span>
          <a href="{{url('/fieldreps-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>

      <li class="{{ (url()->current() == url('/templates') ? 'active' : '') }}">
        <a href="{{url('/templates')}}"><i class="fa fa-star-half-o fa-lg"></i> <span>SURVEY TEMPLATES</span>
          {{-- <span class="label pull-right entities-counter hide-counter">{{@$counter['surveys_templates']}}</span>
          <span class="label pull-right add-new" data-entity-url="survey-template-edit"><i class="fa fa-plus fa-lgg"></i></span> --}}
        </a>
        <div class="menu_addon">
          <span class="label pull-right entities-counter hide-counter">{{@$counter['surveys_templates']}}</span>
          <a href="{{url('/survey-template-edit')}}"><span class="label pull-right add-new"><i class="fa fa-plus fa-lgg"></i></span></a>
        </div>
      </li>
    </ul>
  </section>
</aside>