{{ Form::open(array('method'=>'post',
  'id'  =>  'create_role_form',
  //'url' => (@$role) ? route('roles.update', ['role' => @$role->id ]) : route('roles.store'),
  'class'  =>  'form-inline'
  )) }}

  {{  Form::hidden('id')  }}
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <div class="alert" style="display: none"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="input-group f_w">
          <input type="text" class="form-control" name="display_name" id="display_name" placeholder="Role Name" autofocus="true">
          <span class="input-group-btn">
            <button type="button" class="btn btn-primary btn-flat" id="create_role" name="create_role">Save</button>
          </span>
        </div>
        
        <span for="display_name" class="help-block has-error">
        </span>
        
      </div>
    </div>
  </div>
  {{ Form::close() }}

  @section('custom-script')
  <script type="text/javascript">

    $(document).on('click', 'button[name="create_role"]', function (e) {
      e.preventDefault();
      createRole();
    });

    $(document).on("keypress", "#display_name", function (e) {
      if (e.keyCode == 13){
        e.preventDefault();
        createRole();
      }
    });

    function createRole(){
      var form = $("#create_role_form");
      var formData = $(form).serialize();
      var url = $(form).attr('action');
      var id = form.find('input[name="id"]').val();
      if($.trim(id) !== ""){
        var role_name = $('#roles_list').find('#role_'+id+' .role_name');
        var role_slug = $('#roles_list').find('#role_'+id+' .role_slug');
        
      }
      $(form).find('.input-group').removeClass('has-error');
      $(form).find('.help-block').html('');
      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          if(data.new_role){
            $('#roles_list').append(data.new_role);
          }else if(data.updated_role){
            $(role_name).html(data.updated_role);
            $(role_slug).html(data.updated_role_slug);
          }
          $(form)[0].reset();
          form.find('input[name="id"]').val('');
          DisplayMessages(data['message']);
          $('#display_name').focus();
          $('button[name="create_role"]').html('Save');
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    }


    function SetRoleEdit(element,e){
      e.preventDefault();
      $('#display_name').focus().addClass('bg-light-yellow');
      var form = $("#create_role_form");
      var id = $(element).attr('data-id');
      var parent_li = $(this).closest('li');
      var url = APP_URL + '/roles/' + id + '/edit';
      $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (res) {
          SetFormValues(res.inputs, form);
          $('#display_name').select();
          $('#display_name').focus().removeClass('bg-light-yellow');
          $('button[name="create_role"]').html('Update');
        }
      });

    }
    $('#roles_modal').on('hide.bs.modal', function (event) {
      $('button[name="create_role"]').html('Save');
    });
  </script>
  @append