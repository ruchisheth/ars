<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">      
      <li class="{{ (url()->current() == route('admin_list') ? 'active' : '') }}">
        <a href="{{ route('admin_list') }}">
          <i class="fa fa-user fa-lg"></i> <span>{{ trans('messages.admins') }}</span>
          <span class="label entities-counter pull-right hide-counter">{{@$counters['clients']}}</span>
          <span class="label pull-right add-new" data-entity-url="create-clients"><i class="fa fa-plus fa-lgg"></i></span>
           <!-- <span class="label label-primary pull-right">1</span> -->
        </a>        
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>