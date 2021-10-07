{{ Form::open([
  'method'=>'post',
  'url' => route('fieldrep.account-setting-project-types'),
  'name' => 'project_types_form'
]) 
}}
<div class="box box-solid collapsed-box">
  <div class="box-header">
    <span class="profile-header-img bg-work">
      <img src="{{ asset('public/assets/web/img/projects.png') }}" alt="" class="">
    </span>
    <h4 class="box-title">{{ trans('messages.project_types') }}</h4>
    <div class="pull-right box-tools">
      <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-angle-down fa-2x"></i></button>
    </div>
  </div>
  <div class="box-body">
    <table class="table">
      @foreach($aProjectTypes as $nIdProjectType => $sProjectType)
      <tr>
        <td>{{ $sProjectType }}</td>
        <td>
          <label>
            {{  Form::checkbox(
             "have_done[$nIdProjectType]",1,
             (@str_contains($oFieldRep->have_done,$nIdProjectType)) ? true : false,
             [
               'class' => 'minimal',
             ])
           }}
         </label>
       </td>
       <td>
        <label>

          {{  Form::checkbox(
            "interested_in[$nIdProjectType]",1,
            (@str_contains($oFieldRep->interested_in,$nIdProjectType)) ? true : false,
            [
              'class' => 'minimal ',
            ])
          }}
        </label>
      </td>
    </tr>
    @endforeach
  </table>
</div>
<div class="box-footer">
  <div class="pull-right">
    <button type='reset' id="cancel" class="btn btn-default">{{ trans('messages.cancel') }}</button>
    <button type='submit' id="create" class="btn btn-primary" data-loading-text="<div class='spinner'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>">{{ trans('messages.save') }}</button>
  </div>                                                                                                    
</div>
</div>
{{ Form::close() }}

@section('custom-scripts')
<script type="text/javascript">
  $(document).on('submit', 'form[name="project_types_form"]', function(e){
    e.preventDefault();
    var oElement = $(this);
    var sUrl = $(this).attr('action');
    $.ajax({
      type: "POST",
      url: sUrl,
      data : oElement.serialize(),
      success: function (oResponse) {
        showToast(oResponse.message, 'success');
      },
      error: function (jqXHR, exception) {
        var oResponse = $.parseJSON(jqXHR.responseText);
        $('.prev-attendace-container').html(oResponse.html);
      },
      beforeSend: function(){
        oElement.find('button[type="submit"]').button('loading');
      },complete: function(){
        oElement.find('button[type="submit"]').button('reset');
      }
    });
    return false;
  });
</script>
@append
