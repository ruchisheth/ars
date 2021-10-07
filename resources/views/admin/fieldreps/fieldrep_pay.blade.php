<div class="box ">
    <div class="box-header with-border">
        <h6 class="box-title">
            Payment Rules 
        </h6>
        <div class="box-tools">
            {{  Form::button('<i class="fa fa-plus"></i>',
                [
                'id' => 'create_rep_pay',
                'class' => 'btn btn-box-tool pull-right',
                'data-toggle' => 'modal',
                'data-target' => '#rep_pay' 
                ])
            }}
        </div>
    </div>
    <div class="box-body">
     <div class="table-responsive">
        <table id="rep_pay-grid" class="table table-bordered">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project Type</th>
                    <th>Item</th>
                    <th>Rate</th>
                    <th></th>
                </tr>
            </thead>
        </table>
</div>
    </div>
</div>


<div class="row">
   <!-- modal -->
   <div class="modal fade" id="confirm-pay">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
                   <h4 class="modal-title">Delete FieldRep Pay Rule</h4>
               </div>
               <div class="modal-body">
                   <p>Are you sure to Delete this FieldRep Pay Rule ?</p>
               </div>
               <div class="modal-footer">
                   <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                   <button type="button" data-dismiss="modal" class="btn">Cancel</button>
               </div>
           </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
   </div><!-- /.modal -->

</div>

<div class="row">
    <div class="modal fade" id="rep_pay"><!-- modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                      {{  (@$fieldrep_pay->id) ? 'Field Rep Payment Item Edit' : 'Field Rep Payment Item Add' }}                      
                  </h4>
              </div>
              <div class="modal-body">
                {{ Form::open(array('method'=>'post', 
                    'id' => 'rep_pay_save',
                    'name' => 'rep_pay_save',
                    'url' => route('store.fieldrep-pay')

                    )) }}

                    {{  Form::hidden('id')  }}
                    {{  Form::hidden('fieldrep_id',@$fieldrep->id)  }}

                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert" style="display: none">
                                        <sapn class="text text-danger">
                                            
                                            @include('includes.errors')
                                        </sapn>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-md-6">
                                <div class="form-group">
                                    {{  Form::label('project_type', 'Project Type',['class'=>'mandatory']) }}
                                    {{  Form::select('project_type', @$project_types, '',
                                        [
                                        'id' => 'project_type',
                                        'class' => 'form-control'
                                        ])
                                    }}
                                </div>
                            </div> 

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{  Form::label('client', 'Client',['class'=>'mandatory']) }}
                                    {{  Form::select('client_id', 
                                        @$clients,
                                        (@$clients) ? @$cients : '',
                                        [
                                        'id' => 'client_id',
                                        'class' => 'form-control',
                                        ])
                                    }}

                                    
                                </div>
                            </div>

                        </div><!-- row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{  Form::label('item', 'Item',['class'=>'mandatory']) }}
                                    {{  Form::select('item', array(
                                        ''  =>  'Select Item',
                                        '1'  => 'Advance Check',
                                        '2'  => 'Apron',
                                        '3'  => 'Asignment Pay/Time Spent',
                                        '4'  => 'Bonus',
                                        '5'  => 'Drive Time',
                                        '6'  => 'Expense - Purchases',
                                        '7'  => 'Late Paperwork',
                                        '8'  => 'Late Reporting',
                                        '9'  => 'Parking',

                                        ), '',
                                    [
                                    'id' => 'item',
                                    'class' => 'form-control',
                                    ])
                                }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{  Form::label('rate', 'Rep. Is Paid',['class'=>'mandatory']) }}
                                
                                <div class="row">
                                    <div class="col-xs-3">
                                        {{  Form::text('rate', '',
                                           [
                                           'id' => 'rate',
                                           'class' => 'form-control',
                                           'autofocus' => true,
                                           ])
                                       }}
                                   </div>
                                   <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            
                                            {{  Form::select('pay_type', array(
                                                ''  =>  'Select Pay Type',
                                                '0'  => 'Per Hour',
                                                '1'  => 'Per Assignment',
                                                ), '',
                                            [
                                            'id' => 'pay_type',
                                            'class' => 'form-control',
                                            ])
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group">
                            {{  Form::label('notes', 'Notes')}}
                            {{  Form::textarea('notes', '',
                                [
                                'id' => 'notes',
                                'class' => 'form-control',
                                'rows' => 3,
                                'cols' => 50
                                
                                ])
                            }}
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- box-body -->
    </div><!-- box -->
    <div class="box-footer">           
        <div class="pull-right">
          <div class="pull-right">
            <button type="button" class="btn btn-primary" id="save_rep_pay" name="save_rep_pay">Save</button>
        </div>
        <div class="col-md-1 pull-right">
         <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
     </div>
 </div>
 <h6><small>
    <label for="created" class="modal-label">Created</label> <label for="created_at" class="modal-label"></label> <label for="pipe" class="modal-label">|</label>
    <label for="updated" class="modal-label">Last modified</label> <label for="updated_at" class="modal-label"></label>
</small></h6>
</div>
</div><!-- modal-body -->
{{ Form::close() }}
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
</div><!-- /row -->
@section('custom-script')

<script type="text/javascript">
    $(document).ready(function () {

        oTable = $('#rep_pay-grid').DataTable({
            "serverSide": true,
            "paging": false,
            "bFilter": false,
            "bInfo": false,            
            "autoWidth":true,
            "order": [],
            ajax: {
                url: '{!! url('fieldrep_pays',[@$fieldrep_id]) !!}',
                type: 'POST',
            },
            "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0,1,2,4]},
            { "sWidth": "13%", "targets": [4] }
            ],

        });

        $('#rep_pay').on('hidden.bs.modal', function () {
            $('.alert').hide();
            var form = $("#rep_pay_save");
            form[0].reset();
            $(form).find('input[name="id"]').val('0');
            $(form).find('label[for="created_at"]').hide();
            $(form).find('label[for="updated_at"]').hide();
            $(form).find('label[for="pipe"]').hide();            
            $(form).find('label[for="created"]').hide();
            $(form).find('label[for="updated"]').hide();
        });

        $(document).on('click', 'button[name="save_rep_pay"]', function (e) {
            e.preventDefault();
            var formData = $("#rep_pay_save").serialize();
            var form = $("#rep_pay_save");
            var url = $('#rep_pay_save').attr('action');
            var type = "POST";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $("#rep_pay").modal('hide');
                    oTable.draw(true);
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

    function SetEdit(element) //
    {
        //var Form = $('#contacts').find('form');
        var Form = $("#rep_pay_save");
        var Id = $(element).attr('data-id');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        var url = APP_URL + '/fieldrep_pays/' + Id + '/edit';
        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + Id,
            dataType: "json",
            success: function (res) {
                $('#rep_pay').modal('show');
                SetFormValues(res.inputs, Form);
                $(Form).find('label[for="created_at"]').show();
                $(Form).find('label[for="updated_at"]').show();                
                $(Form).find('label[for="created"]').show();
                $(Form).find('label[for="updated"]').show();
                $(Form).find('label[for="pipe"]').show();
            }
        });
    }

    

</script>
@append
