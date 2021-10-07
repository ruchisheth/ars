@extends('layouts.superadmin.app')
@section('page-title') | {{ trans('messages.dashboard') }} @stop
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
     {{--    <div class="box">
            <div class="box-primary">
                <div class="box-header with-border">
                    <!-- <h6 class="box-title"></h6> -->
                    <i class="fa fa-user"></i>
                    <h3 class="box-title">Clients</h3>
                    @include('includes.success')
                    @include('includes.errors')
                    <div class="box-tools pull-right">
                        <a href="#" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
                       
                    </div>
                </div><!-- /.box-header -->
                
                <div class="box-body">                           
                <div class="box-header with-border custom-header"></div>
                    <table id="clients-grid" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>                                
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box --> --}}
    </section>

    

</div>
@stop

@section('custom-script')

<script type="text/javascript">

  
  /* .ready overe*/

</script>

@stop

