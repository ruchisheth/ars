<div class="box collapsed-box">
  <div class="box-header with-border">
    <h3 class="box-title">Contacts</h3>
    <div class="box-tools">
      {{  Form::button('<i class="fa fa-plus"></i>',
        [
        'id' => 'create_contact',
        'class' => 'btn btn-box-tool',
        'data-toggle' => 'modal',
        'data-target' => '#contacts'
        ])
    }}
    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
</div>

      <!-- <div class="col-md-6" style="float:right">
        <div class="alert" style="display: none"></div>
    </div> -->
</div>

<div class="box-body">
    <div class="table-responsive">
        <table id="contact-grid" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Location</th>
              <th>Email / Phone</th>
              <th>&nbsp;</th>
          </tr>
      </thead>
  </table>
</div>
</div><!-- /.box-body -->
</div><!-- /.box -->

<div class="row">
  <!-- modal -->
  <div class="modal fade" id="contacts">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Add / Edit Contact</h4>
    </div>
    <div class="modal-body">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#primary_details" id="primary_detail" data-toggle="tab">Primary Details</a></li>
          <li><a href="#other_details" data-toggle="tab">Other Details</a></li>
      </ul>
      {{  Form::open(
          array('method'=>'post',
            'url' => route('store.contact'), 
            'id' => 'contacts_save')) 
        }}
        <div class="tab-content">
          <div class="active tab-pane" id="primary_details">
            {{  Form::hidden('entity_type',@$entity_type)  }}
            {{  Form::hidden('reference_id',@$reference_id)  }}
            {{  Form::hidden('id')  }}
            {{  Form::hidden('type','fieldrep')  }}

            <!-- <div class="box"> -->
            <!-- <div class="box-body"> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert" style="display: none"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('contact_type', 'Contact Type') }}
                        {{  Form::select('contact_type', @$contact_types,'',
                            [
                            'id' => 'contact_type',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('contact_type_other', 'Other Type') }}
                        {{  Form::text('contact_type_other','',
                            [
                            'id' => 'contact_type_other',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('first_name', 'First Name',['class' => 'mandatory']) }}
                        {{  Form::text('first_name','',
                            [
                            'id' => 'first_name',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('last_name', 'Last Name',['class' => 'mandatory']) }}
                        {{  Form::text('last_name','',
                            [
                            'id' => 'last_name',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
            </div><!-- row -->

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('email', 'Email Address') }}
                        {{  Form::text('email','',
                            [
                            'id' => 'email',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('phone_number', 'Phone Number') }}
                        {{  Form::text('phone_number','',
                            [
                            'id' => 'phone_number',
                            'class' => 'form-control',
                            'data-inputmask' => '"mask": "(999) 999-9999"',
                            'data-mask' => '',
                            ])
                        }}
                    </div>
                </div>

            </div>

            <div class="row">

            </div><!-- row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('address1', 'Address1',['class' => 'mandatory'])}}
                        {{  Form::text('address1','',
                            [
                            'id' => 'address1',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('address2', 'Address2')}}
                        {{  Form::text('address2','',
                            [
                            'id' => 'address2',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('city', 'City',['class' => 'mandatory'])}}
                        {{  Form::text('city','',
                            [
                            'id' => 'city',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{  Form::label('state', 'State',['class' => 'mandatory']) }}
                        {{  Form::select('state', @$states,'',
                            [
                            'id' => 'state',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{  Form::label('zipcode', 'Zip Code',['class' => 'mandatory'])}}
                        {{  Form::text('zipcode','',
                            [
                            'id' => 'zipcode',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="save_contact" name="save_contact">Save</button>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                    </div>
                </div>
                <h6><small>
                    <label class="modal-label" for="created">Created</label> <label for="created_at" class="modal-label"></label> <label for="pipe" class="modal-label">|</label>
                    <label for="updated" class="modal-label">Last modified</label> <label for="updated_at" class="modal-label"></label>
                </small></h6>
            </div>
        </div>
        <div class="tab-pane" id="other_details">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('organization', 'Organization') }}
                        {{  Form::text('organization','',
                            [
                            'id' => 'organization',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('title', 'Title') }}
                        {{  Form::text('title','',
                            [
                            'id' => 'title',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{  Form::label('cell_number', 'Cell Phone Number') }}
                        {{  Form::text('cell_number','',
                            [
                            'id' => 'cell_number',
                            'class' => 'form-control',
                            'data-inputmask' => '"mask": "(999) 999-9999"',
                            'data-mask' => '',
                            ])
                        }}
                    </div>
                </div>

            </div><!-- row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{  Form::label('notes', 'Notes') }}
                        {{  Form::textarea('notes', '',
                            [
                            'id' => 'notes',
                            'class' => 'form-control',
                            'rows' => 3,
                            ])
                        }}
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <div class="pull-right">
                    <div class="pull-right">
                      <button type="button" class="btn btn-primary" id="save_contact" name="save_contact">Save</button>
                  </div>
                  <div class="col-md-1 pull-right">
                     <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                 </div>
             </div> 
             <h6><small>
              <label class="modal-label" for="created">Created</label> <label for="created_at" class="modal-label"></label> <label for="pipe" class="modal-label">|</label>
              <label for="updated" class="modal-label">Last modified</label> <label for="updated_at" class="modal-label"></label>
          </small></h6>
      </div><!-- /.box-footer -->
  </div>
</div><!-- /.tab-content -->
{{ Form::close() }}
</div><!-- /.custom tab -->
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->

@include('includes.confirm-modal',['name'   => 'contact'])


@section('custom-script')
<script type="text/javascript">

    $(document).ready(function () {

        initSelect();

        oContactTable = $('#contact-grid').DataTable({
            "processing": true,
            "serverSide": true,
            "paging": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "autoWidth":true,
            "order": [ 0, "desc" ],
            ajax: {
                url: '{!! url('contacts',[@$reference_id,@$entity_type]) !!}',
                type: 'POST',
            },
            "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [3]},
            { "sWidth": "7%", "targets": [3] },
            { "sWidth": "auto", "targets": [2] },
            ],
        });

        $('#contacts').on('shown.bs.modal', function () {
            // var form = $("#contacts_save");
            // form[0].reset();
        });
        $('#contacts').on('hidden.bs.modal', function () {
            var form = $("#contacts_save");
            form[0].reset();
            $('#contact_type').trigger('change');
            $(form).find('input[name="id"]').val('0');
            $(form).find('label[for="created_at"]').hide();
            $(form).find('label[for="updated_at"]').hide();
            $(form).find('label[for="pipe"]').hide();            
            $(form).find('label[for="created"]').hide();
            $(form).find('label[for="updated"]').hide();

            $('#primary_detail').trigger('click');

        });

        $(document).on('change', '#contact_type', function (e) {
            var value = $(this).val();
            if(value == 'Feedback'){
                $('label[for="email"]').addClass('mandatory');
                $('label[for="address1"]').removeClass('mandatory');
                $('label[for="city"]').removeClass('mandatory');
                $('label[for="state"]').removeClass('mandatory');
                $('label[for="zipcode"]').removeClass('mandatory');
            }else{
               $('label[for="email"]').removeClass('mandatory');
                $('label[for="address1"]').addClass('mandatory');
                $('label[for="city"]').addClass('mandatory');
                $('label[for="state"]').addClass('mandatory');
                $('label[for="zipcode"]').addClass('mandatory');
            }
        });

        $(document).on('click', 'button[name="save_contact"]', function (e) {
            e.preventDefault();            
            var formData = $("#contacts_save").serialize();
            var form = $("#contacts_save");
            var url = $('#contacts_save').attr('action');
            var type = "POST";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $("#contacts").modal('hide');
                    oContactTable.draw(true);
                    DisplayMessages(data['message']);
                    $('#primary_detail').trigger('click');
                },
                error: function (jqXHR, exception) {
                    var Response = jqXHR.responseText;
                    ErrorBlock = $(form).find('.alert');
                    Response = $.parseJSON(Response);
                    DisplayErrorMessages(Response, ErrorBlock, 'div');
                    $('#primary_detail').trigger('click');

                }
            });
        });/*   Save Contact */

    });/*   /.doucment.ready over */

    function SetContactEdit(element,e)
    {
        e.preventDefault();
        var Form = $("#contacts_save");
        var Id = $(element).attr('data-id');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        var url = APP_URL + '/contacts/' + Id + '/edit';
        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + Id,
            dataType: "json",
            success: function (res) {
                $('#contacts').modal('show');
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



