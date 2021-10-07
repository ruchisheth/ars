@extends('app')
@section('page-title') {{  (@$fieldrep->id) ? 'FieldRep Edit' : 'FieldRep Add' }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        @include('common.fieldreps.primary_details', ['fieldrep_id'  =>  @$fieldrep->id])

        @include('common.fieldreps.other_details', ['fieldrep_id'  =>  @$fieldrep->id])
      </div>

      <div class="col-md-6">
        @if(@$fieldrep->id != '')
        @include('admin.fieldreps.change_password', ['id'  =>  $fieldrep->id])
        @include('admin.contacts.contacts',                 ['entity_type'  =>  @$entity_type,  'contact_types => $contact_types','reference_id'=>@$fieldrep->id, 'states' => @$states])
        @include('admin.fieldreps.fieldrep_ratings',        ['fieldrep_id'  =>  $fieldrep->id])
        @include('admin.fieldreps.fieldrep_prefbans',       ['fieldrep_id'  =>  $fieldrep->id,  'project_types' => $project_types])
        @include('admin.fieldreps.fieldrep_recentactivity', ['fieldrep_id'  =>  $fieldrep->id])
        @endif
        
        @include('common.fieldreps.fieldrep_have_done')
        @include('common.fieldreps.fieldrep_availability')

      </div><!-- main col md 6 over -->
    </div><!-- row -->
  </section>
</div><!-- /.content-wrapper -->
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {

    initSelect();

    $("[data-mask]").inputmask();

    $('#cities').tagsinput({
      confirmKeys: [13], //32-space, 13-enter
      tagClass: 'label label-primary',
      allowDuplicates: false,
      maxTags: 3
    });

    $('.modal').on('shown.bs.modal', function() {
      $(this).find('[autofocus]').focus();
    });
  })

</script>
@append