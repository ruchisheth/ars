@extends('.app')
@section('page-title') | Export @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        {{ Form::open(
          [
          'method'=>'post',
          'url' => route('export.survey.data')
          ]) }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-star-half-o"></i>
              <h6 class="box-title text-muted">
               Export Survey
             </h6>
           </div><!-- /.box header -->
           <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                @include('includes.success')
                @include('includes.errors')
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('client', 'Select Client',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'client_id',@$client_list,'',
                    [
                    'class' =>  'form-control',
                    'id'  => 'clients',
                    ])
                  }}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('project', 'Select Project',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'project_id',['' => 'Select Project'],'',
                    [
                    'class' =>  'form-control',
                    'id'    =>  'projects',
                    ])
                  }}
                </div>
                <input type="hidden" id="unexported_count" name="unexported_count" value="true">
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('round', 'Select Round',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'round_id',['' => 'Select Round'],'',
                    [
                    'class' =>  'form-control',
                    'id'    =>  'rounds',
                    ])
                  }}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div id="questions">
                  <table class="table" id="question-grid" style="display: none">
                    <thead>
                      <tr>
                        <th><input name="select_all" type="checkbox" class="minimal" id="bulk_selete" data-scope="#assignments-grid" /></th>
                        <th>Questions</th>
                        <th style="display: none;">&nbsp;</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            {{-- </div>

            
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  {{  Form::label('questions', 'Select Question',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'question_id[]',
                    ['' => 'Select Question'],'',
                    [
                    'class' =>  'form-control custom-select-control',
                    'id'    =>  'questions',
                    'multiple' => 'multiple'
                    ])
                  }}
                </div>
              </div> --}}

              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('approved_survey', 'Filter Survey', ['class' => ' row col-xs-12']) }}
                  <div class="col-md-12">
                    <label>
                      {{ Form::checkbox('reported_survey', '1', false,
                        ['class'=>'minimal custom_radio']) 
                      }}
                      <span class="rb_span">{{ trans('messages.reported_surveys_only') }}</span>
                    </label> 
                  </div>
                  <div class="col-md-12">
                    <label>
                      {{ Form::checkbox('approved_survey', '1', false,
                        ['class'=>'minimal custom_radio']) 
                      }}
                      <span class="rb_span">{{ trans('messages.approved_surveys_only') }}</span>
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label>
                      {{ Form::checkbox('unexported_survey', '1', false,
                        ['class'=>'minimal custom_radio']) 
                      }}
                      <span class="rb_span">{{ trans('messages.unexported_surveys_only') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div><!-- 1st row over -->
          </div>
          <div class="box-footer">
            <div class="pull-right">
              <div class="pull-right">
                {{  Form::submit('Export',
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
          </div><!-- /.box -->
          {{  Form::close() }}
        </div><!-- main col-md-6 -->
      </div>
    </section>
  </div>
  @stop

  @section('custom-script')

  <script type="text/javascript">
    var questionTable = "";
    var rows_selected = [];
    $(document).ready(function () {


      $("#projects").depdrop({
        url: "{{ url('api/dropdown/projects')}}",
        depends: ['clients'],
        initDepends: ['clients'],
        params: ['unexported_count']
      });
      // .on('depdrop:change', function(event, id, value, count, textStatus, jqXHR) {
      //   $('#rounds').trigger('change');
      // });


      $("#rounds").depdrop({
        url: "{{ url('api/dropdown/rounds')}}",
        depends: ['projects'],
        initDepends: ['projects'],
        params: ['unexported_count']
      }).on('depdrop:change', function(event, id, value, count, textStatus, jqXHR) {
        $('#rounds').trigger('change');
      });

      // $("#questions").depdrop({
      //   url: "{{ url('api/dropdown/questions')}}",
      //   depends: ['rounds'],
      //   initDepends: ['rounds']
      // });


      

    });/* . dccument ready over*/

    $(document).on('change', '#rounds', function(e){
      // e.preventDefault();
      if($(this).val() == ""){
       $('#question-grid').hide('slow');
     }else{
      deselect_all();
      $('#question-grid').hide('slow');
      initQuestionTable();
      $('#question-grid').show('slow');

    }
  });

    function initQuestionTable(){
      if(questionTable != ""){
        questionTable.destroy();
      }
      questionTable = $('#question-grid').DataTable({
        serverSide: true,
        ordering: false,
        searching: false,
        processing: false,
        paging: false,
        info: false,
        ajax: {
          url: '{{ url('api/dropdown/questions') }}',
          type: 'POST',
          data: function (d) {
            d.round_id = $('#rounds').val();
          }
        },
        columns: [
        {data: 'bulk_select',    name: 'bulk_select', width: '1%'},
        {data: 'name',  name: 'name'},
        {data: 'id',    name: 'id', className:"text-white", visible: false},
        ],
        'rowCallback': function(row, data, dataIndex){
          var rowId = data['id']; // Get row ID

          // If row ID is in the list of selected row IDs
          if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('.entity_chkbox').prop('checked', true);
            $(row).addClass('selected');
          }
        },
        drawCallback: function() {
          initCheck('.entity_chkbox');
        },
      });
      initSelectAll('#question-grid', questionTable);
    }

  </script>

  @append