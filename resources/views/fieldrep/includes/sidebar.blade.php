<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="{{ (url()->current() == route('fieldrep.show.assignments.get') ? 'active' : '') }}">
        <a href="{{route('fieldrep.show.assignments.get')}}">
          <i class="fa fa-check-square-o fa-lg"></i> <span>ASSIGNMENTS</span><span class="label entities-counter pull-right">{{@$counters['assignments']}}</span>
        </a>
      </li>
      <li class="{{ (url()->current() == route('fieldrep.show.offers.get') ? 'active' : '') }}">
        <a href="{{route('fieldrep.show.offers.get')}}">
          <i class="fa fa-check-square-o fa-lg"></i> <span>OFFERS</span><span class="label entities-counter pull-right">{{@$counters['offers']}}</span>
        </a>
      </li>
      <li class="{{ (url()->current() == route('fieldrep.document-list') ? 'active' : '') }}">
        <a href="{{route('fieldrep.document-list')}}">
          <i class="fa fa-folder fa-lg"></i> <span>@lang('messages.resources')</span>
          <span class="badge badge-secondary label-danger">@lang('new')</span>
        </a>
      </li>
      <li class="{{ (url()->current() == route('fieldrep.show.events.get') ? 'active' : '') }}">
        <a href="{{route('fieldrep.show.events.get')}}">
          <i class="fa fa-calendar fa-lg"></i> <span>CALENDAR</span>
        </a>
      </li>
      <li class="{{ (url()->current() == route('fieldrep.show.profile.get') ? 'active' : '') }}">
        <a href="{{route('fieldrep.show.profile.get')}}">
          <i class="fa fa-user fa-lg"></i> <span>PROFILE</span>
        </a>
      </li>
      <li class="{{ (url()->current() == route('settings') ? 'active' : '') }}">
        <a href="{{route('settings')}}">
          <i class="fa fa-wrench fa-lg"></i> <span>SETTINGS</span>
        </a>
      </li>
    </ul>
  </section>
</aside>