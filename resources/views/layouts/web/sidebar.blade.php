<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <!-- Optionally, you can add icons to the links -->
      @if(Auth::user()->user_type == config('constants.USERTYPE.CLIENT'))
      <li class="">
        <a href="{{ route('client.assignment-list') }}"><img src="{{ asset('public/assets/web/img/asignment.png') }}">
          <span class="">{{trans('messages.assignments')}}</span>
        </a>
      </li>


      @elseif(Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
      <li class="">
        <a href="{{ route('fieldrep.assignment-list') }}"><img src="{{ asset('public/assets/web/img/asignment.png') }}">
          <span class="">{{trans('messages.assignments')}}</span>
        </a>
      </li>
      <li class="">
        <a href="{{ route('fieldrep.offer-list') }}"><img src="{{ asset('public/assets/web/img/offers.png') }}">
          <span class="">{{trans('messages.offers')}}</span>
        </a>
      </li>
      <li class="">
        <a href="{{ route('fieldrep.document-list') }}">
          <img src="{{ asset('public/assets/web/img/folder-icon.png') }}">
          <span class="">{{trans('messages.documents')}}</span>
        </a>
      </li>
      @endif
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>