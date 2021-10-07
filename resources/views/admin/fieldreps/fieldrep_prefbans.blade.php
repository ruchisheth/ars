<div class="box collapsed-box">
  <div class="box-header with-border">
    <h6 class="box-title">
      Fieldrep Prefer/Ban 
    </h6>
    <div class="box-tools pull-right">
      {{  Form::button('<i class="fa fa-plus"></i>',
        [
        'id' => 'create_prefban',
        'class' => 'btn btn-box-tool',
        'data-toggle' => 'modal',
        'data-target' => '#preferbanModal' 
        ])
      }}       
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
    </div>
  </div>
  <div class="box-body">
    <div class="table-responsive">
      <table id="prefban-grid" class="table table-bordered">
        <thead>
          <tr>
            <th>Chain</th>
            <th>Site</th>
            <th>Activity</th>
            <th>P/B</th>             
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>



<div class="row">
  <div class="modal fade" id="preferbanModal"><!-- modal -->
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Add Prefer/Ban
          </h4>
        </div>
        <div class="modal-body">
          {{ Form::open(array('method'=>'post', 
            'url' => route('store.prefbans'), 
            'id' => 'prefban_save')) }}

            {{  Form::hidden('id')  }}
            {{  Form::hidden('fieldrep_id',@$fieldrep->id)  }}

            <div class="box">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert" style="display: none">
                      <sapn class="text text-danger">
                      </sapn>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{  Form::label('pref_ban', 'Prefer/Ban',
                        [
                        'class' => 'rb_label_full mandatory'
                        ]) }}        
                        <label>

                          {{ Form::radio('pref_ban', '0', (lcfirst(@$fieldrep->pref_ban) == '0' ? true : false),['class'=>'minimal']) }}
                          <span class="rb_span">Prefer</span>
                        </label>
                        <label>
                          {{ Form::radio('pref_ban', '1', (lcfirst(@$fieldrep->pref_ban) == '1' ? true : false),['class'=>'minimal']) }}
                          <span class="rb_span">Ban</span>
                        </label>
                      </div>
                    </div>
                    <div class="form-group col-md-6">

                      {{  Form::label('chain_id', 'Chain',['class'=>'control-label mandatory']) }}
                      {{  Form::select('chain_id',
                       [""=>"Select Chain"] + @$chains,'',
                       [
                       'id' => 'chain_id',
                       'class'=>'form-control',
                       'data-placeholder' => 'Select Chain',
                       'onchange'=>'changeChain(this)',
                       'style' =>  'width:100%',
                       ])
                     }}


                   </div>

                   <div class="form-group col-md-6">
                     {{ Form::label('site_id','Site',['class'=>'control-label mandatory']) }}

                     {{  Form::select('site_id',[],'',
                      [
                      'class'=>'form-control',
                      'data-placeholder' => 'Select chain first',
                      'style' =>  'width:100%',
                      ])
                    }}

                  </div>

                  <div class="col-md-6">
                   <div class="form-group">
                    {{  Form::label('activity', 'Activity', ['class' => 'mandatory']) }}
                    {{  Form::select('activity',
                     [""=>"Select Activity"] + @$project_types, '',
                     [
                     'id' => 'activity',
                     'class'=>'form-control',
                     'data-placeholder' => 'Select Activity',
                     'style' =>  'width:100%',
                     ])
                   }}

                 </div>
               </div>


             </div><!-- row -->


           </div><!-- box-body -->
           <div class="box-footer">
             <div class="pull-right">
              <div class="pull-right">
                <button type="button" class="btn btn-primary" id="save_prefban" name="save_prefban">Save</button>
              </div>
              <div class="col-md-1 pull-right">
               <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
             </div>
           </div>  
           <h6><small>
            <label for="created" class="modal-label">Created</label> <label for="created_at" class="modal-label"></label> <label for="pipe" class="modal-label">|</label>
            <label for="updated" class="modal-label">Last modified</label> <label for="updated_at" class="modal-label"></label>
          </small></h6>                    
        </div><!-- /.box-footer -->
      </div><!-- box -->
      {{ Form::close() }}    
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->

@include('includes.confirm-modal',
  [
  'name'   => 'PreferBan',
  'id'  => 'confirm_preferban',
  ])

  @include('includes.confirm-modal',['name'   => 'PreferBan'])

  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {

     preferbanTable = $('#prefban-grid').DataTable({
      "serverSide": true,
      "paging": false,
      "bFilter": false,
      "bInfo": false,
      "autoWidth":true,
      "ordering": false,
      ajax: {
        url: '{!! url('prefbans',[@$fieldrep_id]) !!}',
        type: 'POST',
      },
      "aoColumnDefs": [
      {'bSortable': false, 'aTargets': [0,1,2,4]},
      { "sWidth": "14%", "targets": [4] },
      { "sWidth": "2%", "targets": [2] },
      ],
    });

     $('#preferbanModal').on('hidden.bs.modal', function () {
      $('.alert').hide();
      var form = $("#prefban_save");
     // $('input[name="pref_ban"]').iCheck('uncheck');
     form[0].reset();
     form.find('.minimal').iCheck('update');
     $('#site_id').find('option').remove();
     $(form).find('input[name="id"]').val('0');
     $(form).find('label[for="created_at"]').hide();
     $(form).find('label[for="updated_at"]').hide();
     $(form).find('label[for="pipe"]').hide();            
     $(form).find('label[for="created"]').hide();
     $(form).find('label[for="updated"]').hide();
   });

     $(document).on('click', 'button[name="save_prefban"]', function (e) {
      e.preventDefault();
      var formData = $("#prefban_save").serialize();
      var form = $("#prefban_save");
      var url = $('#prefban_save').attr('action');
      var type = "POST";
      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          $("#preferbanModal").modal('hide');
          form[0].reset();
          preferbanTable.draw(true);
          DisplayMessages(data['message']);
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    });



   });

    function SetEditPrefBan(element)
    {
      var Form = $("#prefban_save");
      var Id = $(element).attr('data-id');
      var APP_URL = $('meta[name="_base_url"]').attr('content');
      var url = APP_URL + '/prefbans/' + Id + '/edit';       
      $.ajax({
        type: "POST",
        url: url,
        data: "id=" + Id,
        dataType: "json",
        success: function (res) {
          SetFormValues(res.inputs, Form);
          $('#preferbanModal').modal('show');
          $(Form).find('label[for="created_at"]').show();
          $(Form).find('label[for="updated_at"]').show();                
          $(Form).find('label[for="created"]').show();
          $(Form).find('label[for="updated"]').show();
          $(Form).find('label[for="pipe"]').show();
        }
      });
    }



    function changeChain(element){

      var chain_id = $('select[name=chain_id]').val();
      if(chain_id == ""){
        $('#site_id').find('option').remove();
        return;
      }
      var site_id = $('select[name="site_id"]');
      var APP_URL = $('meta[name="_base_url"]').attr('content');
      var url = APP_URL + '/prefbans/' + chain_id + '/getsite';
      $.ajax({
       type: 'POST',
       url: url,                  
       data:"chain_id="+chain_id,
       success: function (res) {
        $(site_id).val('').change().html('');
        if(res.site_ids!=''){
          var HTML = "<option value=''>Select Site</option>";
          $(site_id).append(HTML);
          $.each(res.site_ids, function(index, site_ids){
            var HTML = "<option value='"+index+"'>"+site_ids+"</option>";
            $(site_id).append(HTML);
          });
          $(site_id).val('').change();
        }
        else{
          var HTML = "<option value=''>No Sites</option>";
          $(site_id).html(HTML);
          $(site_id).val('').change();
        }
      }
    });

    }

  </script>
  @append