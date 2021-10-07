@extends('app')
@section('page-title') | Surveys @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-star-half-o"></i>
          <h3 class="box-title">Surveys</h3>
          <div class="alert" style="display: none"></div>
          {{-- <div class="box-tools">
            <a href="{{url('/survey-template-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
          </div> --}}
        </div><!-- /.box-header -->
        <div class="box-body">
          <form class="form-inline section-filter" id="search-form" method="post">
            <div class="form-group">
              <label for="Project">Project</label>
              {{  Form::select(
                'project_id', @$project_list,
                @$project_list,
                [
                'id' => 'project_id',
                'class'=>'form-control',
                'data-placeholder'=>'Select Chain'
                ]) 
              }}
              <input type="hidden" id="all_round" name="all_round" value='all'>
            </div>
            <div class="form-group">
              <label for="round">Round</label>
              {{  Form::select(
                'round_id', @$round_list,
                @$round_list,
                [
                'id' => 'round_id',
                'class'=>'form-control',
                'data-placeholder'=>'Select Round'

                ]) 
              }}
              <input type="hidden" id="all_sitecodes" name="all_sitecodes" value='all'>
            </div>

            <div class="form-group">
              <label for="site_code">Site Code</label>
              {{  Form::select(
                'site_code', @$site_code,"",
                [
                'id' => 'site_code',
                'class'=>'form-control',
                'data-placeholder'=>'Select SiteCode'

                ]) 
              }}
            </div>
            <div class="form-group">
              <label for="status" class="">Status</label>
              {{  Form::select('status', array(
                ''  =>  'Select Status',
                'reported'  => trans('messages.assignment_status.reported'),
                'partial'  => trans('messages.assignment_status.rejected'),                           
                'approved'  => trans('messages.assignment_status.completed'),
                ), '',
              [
              'id' => 'status',
              'class' => 'form-control',
              ])
            }}
          </div>               
          <div class="action-btns">
            <input type="submit" id="search" class="btn btn-default" value="Search">
            <input type="reset" id="search-form-reset" class="btn btn-default" value="Reset">
          </div>
        </form>
        <div class="box-header with-border custom-header"></div>
        <div class="table-responsive individual_search">
          <table id="survey-grid" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>Site Code</th>                     
                <th>SYI Reference Number</th>                     
                <th>Project</th>
                <th>Round</th>
                <th>Location</th>                       
                <th title="Schedule Date/Time">Schedule DT</th>
                <th>Template</th>
                <th>Schedule To</th>
                <th>Is Invoiced?</th>
                <th>Status</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tfoot>
            <tr>
              <td class="non_searchable"></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class="non_searchable"></td>
              <td class="non_searchable"></td>
              <td class="non_searchable"></td>              
            </tr>
          </tfoot>
          </table>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">

  var oSurveyTable ='';
  $(document).ready(function(){

     $("#round_id").depdrop({
      url: "{{ url('api/dropdown/rounds')}}",
      depends: ['project_id'],
      params: ['all_round'],
    });

    $('#round_id').on('depdrop.beforeChange', function(event, id, value) {
      if(value == ''){
        value = 'all';
      }
    });

    $("#site_code").depdrop({
      url: "{{ url('api/dropdown/sitecodes')}}",
      depends: ['project_id'],
      params: ['all_sitecodes'],
    });

    oSurveyTable = $('#survey-grid').DataTable( {
      "processing": true,
      "serverSide": true,
      //"ordering": false,
      "order": [ 1, "desc" ],
      ajax: {
        url: APP_URL+'/surveys',
        type: 'POST',
        data: function (d) {
          d.project_id = $('select[name=project_id]').val();  
          d.round_id = $('select[name=round_id]').val();              
          d.site_code = $('select[name=site_code]').val();
          d.status = $('select[name=status]').val();
        }
      },
      columns: [
      {data: 'client_logo', 							name: 'c.client_logo', 'className': "client-td", 'width': '10%', orderable:false,searchable :false},
      {data: 'site_code', 								name: 's.site_code'},           
      {data: 'service_code', 							name: 'su.service_code'},           
      {data: 'project_name', 							name: 'p.project_name'},
      {data: 'round_name', 								name: 'r.round_name'},
      {data: 'city', 									name: 's.city'},
      {data: 'assignment_scheduled',      name: 'assignment_scheduled', 'width': '14%', className: 'text-right'},
      {data: 'template_name', 						name: 't.template_name'},
      {data: 'schedule_to', 							name: 'schedule_to', 'width': '11%'},
      {data: 'is_invoiced', 							name: 'is_invoiced', orderable: false, searchable: false},
      {data: 'status', 										name: 'a.status', orderable: false, searchable: false},
      {data: 'action', 										name: 'action', orderable: false, searchable: false}
      ],initComplete: function () {
        this.api().columns().every(function () {
          var column = this;
          var input = document.createElement("input");
          var columnClass = column.footer().className;
          var timeoutId = 0;
          if (columnClass.indexOf("non_searchable") == -1){
            // $(input).appendTo($(column.header())).addClass('form-control').attr('tabindex', 1)
            $(input).appendTo($(column.footer()).empty()).addClass('form-control').attr('tabindex', 1)
            .on('change', function(e){
                column.search($(this).val(), false, false, true).draw();
            });
          }
        });
      }
    });

    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {                       
      oSurveyTable.draw();
      e.preventDefault();                
    });
    $('#search-form-reset').on('click', function(e) {           
      $('#search-form')[0].reset();
      oSurveyTable.draw();           
    });
  });/* .ready overe*/


</script>

@stop

