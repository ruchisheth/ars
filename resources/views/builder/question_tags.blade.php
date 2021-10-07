@extends('.app')
@section('page-title') | Survey Question Tag @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        {{ Form::open(
          [
          'method'=>'post',
          'url' => route('store.question.tags')
          ]) }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-star-half-o"></i>
              <h6 class="box-title text-muted">
               Question Tag
             </h6>
          {{  Form::hidden('template_id',base64_encode(@$template->id))  }}
           </div><!-- /.box header -->
           <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                @include('includes.success')
                @include('includes.errors')
              </div>
            </div>

            
            <div class="row">
              <div class="col-md-12">
                <div id="questions">
                  <table class="table" id="question-grid">
                    <thead>
                      <tr>
                        {{-- <th><input name="select_all" type="checkbox" class="minimal" id="bulk_selete" data-scope="#assignments-grid" /></th> --}}
                        <th>Questions of template <b>{{ @$template->template_name }}</b></th>
                        <th style="display: none;">&nbsp;</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div><!-- 1st row over -->
            
            <div class="box-footer action-btns pull-right">
              <a href="{{ URL::previous() }}" id="cancel" class="btn btn-default">Cancel</a>
              {{  Form::submit('Save',
                [
                'id' => 'create',
                'class' => 'btn btn-primary'
                ])
              }}

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

        questionTable = $('#question-grid').DataTable({
          serverSide: true,
          ordering: false,
          searching: false,
          processing: true,
          paging: false,
          info: false,
          ajax: {
            url: '{{ route('list.question.tags') }}',
            type: 'POST',
            data: function (d) {
              d.template_id = {{ @$template->id }};
            }
          },
          columns: [
          // {data: 'bulk_select',    name: 'bulk_select', width: '1%'},
          {data: 'name',  name: 'name'},
          {data: 'id',    name: 'id', className:"text-white", visible: false},
          ],
        //   'rowCallback': function(row, data, dataIndex){
        //   var rowId = data['id']; // Get row ID

        //   if($.inArray(rowId, rows_selected) !== -1){
        //     $(row).find('.entity_chkbox').prop('checked', true);
        //     $(row).addClass('selected');
        //   }
        // },
        // drawCallback: function() {
        //   initCheck('.entity_chkbox');
        // },
      });
      // initSelectAll('#question-grid', questionTable);

    });
      

  </script>

  @append