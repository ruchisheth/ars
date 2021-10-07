@extends('.app')
@section('page-title') | Export @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
          'method'=>'post',
          //'url' => route('save.clients'),
          'enctype'  =>  "multipart/form-data"]) 
        }}
        {{  Form::hidden('id',@$client->id)  }}
        {{  Form::hidden('url',URL::previous())  }}
        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-upload"></i>
            <h6 class="box-title">
             Export
           </h6>
         </div>

         <div class="box-body">
           <div class="row">
            <div class="col-md-12">
              <table id="list-grid" class="table table-bordered">
                <tbody>
                  <tr>
                    <td><span><i class="fa fa-star-half-o fa-lg"></i></span><a href="{{ url('/exports') }}/survey" onClick="getExport('survey')">Survey</a></td>
                </tr>
                <tr>
                    <td>
                      <span><i class="fa fa-group fa-lg"></i></span><a target='_blank' href="{{ url('/exports') }}/fieldreps">{{ trans('messages.field_reps') }}</a>
                    </td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /.box-body -->
      {{-- <div class="box-footer">

        <div class="pull-right">
          <div class="pull-right">
            {{  Form::submit('Save',
              [
              'id' => 'create',
              'class' => 'btn btn-primary pull-right'
              ])
            }}
          </div>
          <div class="col-md-1 pull-right">
            <a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">Cancel</a>  
          </div>
        </div>
      </div> --}}
      {{ Form::close() }}
    </div><!-- /.box -->
  </div>
</div>
</section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {


  });/* . document ready over*/

  function getExport(entity){
    // if(window.event.ctrlKey) {
    //     console.log('test');
    // }

    url = "{{ url('/exports') }}";
    url = url+"/"+entity;
    window.location = url;
    
    // $.ajax({
    //     type: "GET",
    //     url: url,
    //     dataType: 'html',
    //     success: function (data) {
    //       $(data).find('.content-wrapper').remove();
    //       $('.content-wrapper').html(data);
    //     },
    //     error: function (jqXHR, exception) {
    //     }
    //   });
  }

</script>

@append