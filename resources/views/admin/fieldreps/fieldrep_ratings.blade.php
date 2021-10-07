<div class="box collapsed-box">
    <div class="box-header with-border">
        <h6 class="box-title">
            Ratings 
        </h6>
        <div class="box-tools pull-right">
            {{  Form::button('<i class="fa fa-plus"></i>',
                [
                'id' => 'create_rating',
                'class' => 'btn btn-box-tool',
                'data-toggle' => 'modal',
                'data-target' => '#ratings' 
                ])
            }}
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
        </div>
    </div>
    <div class="box-body">
       <div class="table-responsive">
        <table id="rating-grid" class="table table-bordered">
            <thead>
                <tr>
                    <th>Rating Category</th>
                    <th>Date</th>
                    <th>Rating</th>
                    <th>Rater</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
</div>


<div class="row">
    <div class="modal fade" id="ratings"><!-- modal -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        Add Rating
                    </h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('method'=>'post', 
                        'url' => route('store.rating'), 
                        'id' => 'rating_save')) }}

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
                                            {{  Form::label('rating_category', 'Rating Category',['class'=>'mandatory']) }}
                                            {{  Form::select('rating_category', array(
                                                ''  => 'Select Category',
                                                '1'  => 'Field Visit',
                                                '2'  => 'Quality Assurance',
                                                '3'  => 'Review'
                                                ), '',
                                            [
                                            'id' => 'rating_category',
                                            'class' => 'form-control',
                                            ])
                                        }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{  Form::label('rater', 'User',['class'=>'mandatory']) }}
                                        {{  Form::select('rater',
                                            @$admin_client,
                                            (@$admin_client) ? @$admin_client : '',
                                            [
                                            'id' => 'rater',
                                            'class' => 'form-control',
                                            ])
                                        }}                                
                                    </div>
                                </div>

                            </div><!-- row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{  Form::label('rating', 'Rating',['class' => 'mandatory']) }}
                                        {{  Form::select('rating', array(
                                            ''  => 'Select Rating',
                                            '1'  => '1',
                                            '2'  => '2',
                                            '3'  => '3',
                                            '4'  => '4',
                                            '5'  => '5',
                                            '6'  => '6',
                                            '7'  => '7',
                                            '8'  => '8',
                                            '9'  => '9',
                                            '10'  => '10',

                                            ), '',
                                        [
                                        'id' => 'rating',
                                        'class' => 'form-control',
                                        ])
                                    }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{  Form::label('effective_date', 'Effective Date') }}
                                    {{  Form::text('effective_date', '',
                                        [
                                        'id' => 'effective_date',
                                        'class' => 'form-control no_key',
                                        ])
                                    }}
                                </div>
                            </div>
                        </div><!-- row -->
                    </div><!-- box-body -->
                    <div class="box-footer">            
                        <div class="pull-right">
                          <div class="pull-right">
                            <button type="button" class="btn btn-primary" id="save_rating" name="save_rating">Save</button>
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
        </div><!-- box -->
        {{ Form::close() }}    
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->
@include('includes.confirm-modal',
  [
  'name'   => 'Rating',
  'id'  => 'confirm_rating',
  ])


  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {


       ratingTable = $('#rating-grid').DataTable({
        "serverSide": true,
        "paging": false,
        "bFilter": false,
        "bInfo": false,
        "autoWidth":true,
        "ordering": false,
        ajax: {
            url: '{!! url('ratings',[@$fieldrep_id]) !!}',
            type: 'POST',
        },
        "aoColumnDefs": [
        {'bSortable': false, 'aTargets': [0,3,4]},
        { "sWidth": "14%", "targets": [4] },
        ],
    });


        $('#effective_date').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true
        });
        $('#ratings').on('hidden.bs.modal', function () {
            $('.alert').hide();
            var form = $("#rating_save");
            form[0].reset();
            $(form).find('input[name="id"]').val('0');
            $(form).find('label[for="created_at"]').hide();
            $(form).find('label[for="updated_at"]').hide();
            $(form).find('label[for="pipe"]').hide();            
            $(form).find('label[for="created"]').hide();
            $(form).find('label[for="updated"]').hide();
        });

        $(document).on('click', 'button[name="save_rating"]', function (e) {
            e.preventDefault();

            var formData = $("#rating_save").serialize();
            var form = $("#rating_save");
            var url = $('#rating_save').attr('action');
            var type = "POST";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $("#ratings").modal('hide');
                    ratingTable.draw(true);
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

    $(document).on('click', 'button[name="remove_rating"]', function(e){
       e.preventDefault();
       var $form=$(this).closest('form');
       var $parent_tr = $(this).closest('tr');
       var rating_id =  $(this).data('id');
       var formData = {id :rating_id};
       var url = APP_URL+'/ratings-delete';
       var type = "POST";
       $('#confirm').modal({keyboard: false});
       $('#confirm').find('#delete').bind('click', function() {      
          $.ajax({
            type: type,
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
              ratingTable.draw();
              DisplayMessages(data['message']);
          },
          error: function (jqXHR, exception) {
              var Response = jqXHR.responseText;          
              Response = $.parseJSON(Response);
              DisplayMessages(Response.message,'error');
          }
      });
      });
   });

    function SetEditRating(element)
    {
        //var Form = $('#contacts').find('form');
        var Form = $("#rating_save");
        var Id = $(element).attr('data-id');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        var url = APP_URL + '/ratings/' + Id + '/edit';
        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + Id,
            dataType: "json",
            success: function (res) {
                $('#ratings').modal('show');
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