@extends('fieldrep.app')
@section('page-title') | Fieldrep Profile @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        @include('common.fieldreps.primary_details')
        
        @include('common.fieldreps.other_details')
        
      </div>

    {{-- </div>main col md 6 over --}}

    <div class="col-md-6">    
      @include('admin.contacts.contacts', ['reference_id' =>  $fieldrep->id])

      @include('common.fieldreps.fieldrep_have_done')

      @include('common.fieldreps.fieldrep_availability')

      @include('common.change_password')
    </div>
    <!-- main col md 6 over -->
  </div><!-- row -->
</section>
</div><!-- /.content-wrapper -->
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {

    $(".select2").select2();

  });
</script>
@append