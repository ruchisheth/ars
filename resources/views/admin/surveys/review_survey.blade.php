@extends('app_no_header')
@section('page-title') | Review Survey @stop
@section('content')
<div class="content-wrapper fill-survey">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default collapsed-box">
          <div class="box-header with-border">
            <h4 class="box-title">
              Survey Details
            </h4>
            <div class="pull-right box-tools">
              <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-angle-down"></i></button>
            </div>
            
          </div>
          <div class="box-body">
            <div class="col-md-10 table-responsive">
              <table class="table no-border">
                <tr>
                  <th>Client</th>
                  <td>{{ @$survey_details->client_name }}</td>
                  <th>Project</th>
                  <td>{{ @$survey_details->project_name }}</td>               
                </tr>
                <tr>
                  <th>Round</th>
                  <td>{{ @$survey_details->round_name }}</td> 
                  <th>Site</th>
                  <td>{{ @$survey_details->site_name }}</td>  
                </tr>
                <tr> 
                  <th>Site Location</th>
                  <td>{{ @$survey_details->site_location }}</td>               
                  <th>FieldRep</th>
                  <td>{{ @$survey_details->fieldrep_name }}</td>                
                </tr>
                <tr>                
                  <th>Schedule Date & Time</th>
                  <td>{{ @$survey_details->schedule }}</td>   
                  <th>Reported Date</th>
                  <td>{{ @$survey_details->reported_at }}</td>
                </tr>
              </table>
            </div>
          </div>
          {{-- <div class="box-body">
            <div class="col-md-8">
              <table class="table no-border no-row-height">
                <tr>
                  <th>Client</th>
                  <td>{{ @$survey_details->client_name }}</td>
                  <th>Site</th>

                  <td>{{ @$survey_details->site_name }}</td>
                </tr>
                <tr>
                  <th>Project</th>
                  <td>{{ @$survey_details->project_name }}</td>
                  <th>FieldRep</th>
                  <td>{{ @$survey_details->fieldrep_name }}</td>
                </tr>
                <tr>
                  <th>Round</th>
                  <td>{{ @$survey_details->round_name }}</td>
                  <th>Assignment Code</th>
                  <td>{{ @$survey_details->site_code }}</td>
                </tr>
              </table>
            </div>
          </div> --}}
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              Review Survey
              <small>
               {{-- {!! @$survey_template->getSurveyStatus($survey_template->status) !!} --}}
               {!! @$survey_template->assignments->getAssignmentStatus() !!}
             </small>
           </h4>
           <div class="pull-right box-tools">
             @if(!@$survey_template->assignments->is_partial && !@$survey_template->assignments->is_approved)
             <a target="_blank" class="btn btn-primary btn-sm btn-box-tool editSurvey changeStatus"  href='{{ url("/survey").'/'.Crypt::encrypt($id).'/'.base64_encode(Auth::user()->client_code) }}'>Edit Survey</a>
             {{-- <button class="btn btn-primary btn-sm btn-box-tool" value="{{ @$id }}">Edit Survey</button> --}}
             <button class="btn btn-danger btn-sm btn-box-tool changeStatus" data-id="{{ $id }}" data-status="partial" onclick="changeStatus(this, event)">Mark As {{ trans('messages.assignment_status.rejected') }}</button>
             <button class="btn btn-success btn-sm btn-box-tool changeStatus" data-id="{{ $id }}" data-status="approved" onclick="changeStatus(this, event)">Approve</button>
             @endif            
             <button class="btn btn-default btn-sm btn-box-tool" data-id="{{ $id }}" onclick="exportSurvey(this, event)">Export</button>
             <a href="{{ route('surveys') }}" id="cancel" class="btn btn-default btn-sm btn-box-tool">Back</a>
           </div>
         </div>
         @if(Session::get('success')!='')
         {{ Form::hidden('saved_files',Session::get('files')) }}
         @endif
         {!! Form::open(["id"=>"form-holder","method"=>"POST","enctype"=>"multipart/form-data"]) !!}
         <div class="box-body">
          {{ Form::hidden('status',@$survey_template->status) }}
          {{ Form::hidden('id',$id) }}
          {{ Form::hidden('template','') }}
          {{ Form::hidden('KeyPairs','') }}
          <div class="controls-holder review-survey">
            {!! $survey_template->filled_surveydata !!}
          </div>

        </div><!-- box-body -->
        {!! Form::close() !!} 
      </div>
    </div>
  </div>
  @include('includes.confirm-modal',
    [
    'action' => 'Mark Survey '.trans('messages.assignment_status.rejected'),
    'id'    =>  'confirm_partial',
    'msg' => 'Are you sure you want to Mark this survey as '.trans('messages.assignment_status.rejected').'?',
    'btn' => ['Yes','No'],
    ])

  @include('includes.confirm-modal',
    [
    'action' => 'Approve Survey',
    'id'    =>  'confirm_approve',
    'msg' => 'Are you sure you want to Approve this Survey?',
    'btn' => ['Yes','No'],
    ])

  {{-- @include('includes.confirm-modal',['name'   => 'Payment','id'  => 'confirm_delete_payment']) --}}

  </section>
</div>
@stop
{{-- {!! Form::button('Save Surveys', ['class' => 'btn btn-primary','onclick'=>'SubmitSurvey(this,"1","'.$id.'")','type'=>'button']) !!}
{!! Form::button('Submit Surveys', ['class' => 'btn btn-success','onclick'=>'SubmitSurvey(this,"2","'.$id.'")','type'=>'button']) !!}
{!! Form::button('Cancel', ['class' => 'btn btn-default','type'=>'reset']) !!} --}}

{{-- @include('includes.scripts') --}}
@section('custom-script')

{{ Html::script(AppHelper::ASSETS.'plugins/builder/builder.js') }}

<script>
  // oPaymentTable = '';
  $(document).ready(function(){
    // button =  _builder.markup('button','Cancel', 
    // {
    //   'id': 'cancel_payment', 
    //   'type': 'button', 
    //   'onClick': 'cancelPayment()', 
    //   'class': 'btn btn-default',
    // });
    // $('#payment_edit_box').find('.box-footer .pull-right').prepend(button);
    // $('#reset_payment').hide();

    // oPaymentTable = $('#payment-grid').DataTable({
    //   "processing": false,
    //   "serverSide": true,
    //   "paging": false,
    //   "bFilter": false,
    //   "bInfo": false,
    //   "autoWidth":true,
    //    "ordering": false,
    //   ajax: {
    //     url: APP_URL+'/payments/'+{{ @$assignment_id }},
    //     type: 'POST',
    //   },
    //   columns: [
    //   {data: 'payment_type', name: 'payment_type', 'width': '160px'},
    //   {data: 'qty', name: 'qty'},
    //   {data: 'pay_rate', name: 'pay_rate'},
    //   {data: 'total', name: 'total'},
    //   {data: 'action', name: 'action', orderable: false, searchable: false}
    //   ],
    // });

    // oPaymentTable.on('xhr', function() {
    //   var ajaxJson = oPaymentTable.ajax.json();
    //   TotalPay = 0;
    //   for ( var i=0, ien=ajaxJson.data.length ; i<ien ; i++ ) {
    //     TotalPay += ajaxJson.data[i].total;
    //   }
    //   $('#total_pay').html(TotalPay+'$');
    // });

    // $(document).on('click', 'button[name="remove_payment"]', function(e){
    //   e.preventDefault();
    //   var form=$("#payment_save_form");
    //   var payment_id =  $(this).data('id');
    //   var formData = {id :payment_id};
    //   var url = APP_URL+'/payments-delete';
    //   var type = "POST";
    //   $('#confirm_delete_payment').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function() {
    //     $.ajax({
    //       type: type,
    //       url: url,
    //       data: formData,
    //       dataType: 'json',
    //       success: function (data) {
    //         oPaymentTable.draw();
    //         form.find('#reset_payment').trigger('click');
    //         DisplayMessages('Payment removed successfully','success');
    //       },
    //       error: function (data) {
    //         DisplayMessages('Please try again','error');
    //       }
    //     });
    //   });
    // });

    // $('img').each(function(i, row) 
    // {
    //   src = $(this).attr('src');      
    //   anchor =  _builder.markup('a',null, {'href': src, 'target': '_blank'});
    //   $(this).wrap(anchor);
    // });

  });

  // function cancelPayment()
  // {
  //   $('#payment_edit_box').slideUp( "slow" );
  //   $('#payment-grid').slideDown( "slow" );
  //   $('#reset_payment').trigger('click');
  // }

  // function addPayment()
  // {
  //   $('#reset_payment').trigger('click');
  //   $('#payment_edit_box').slideDown( "slow" );
  //   $('#payment-grid').hide();
  // }
</script>

@append


