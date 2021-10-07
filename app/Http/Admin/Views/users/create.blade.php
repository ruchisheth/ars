<div class="row">
  <div class="modal fade" id="user_modal" tabindex='-1'>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-user"></i>
          <h4 class="modal-title box-title">User</h4>
        </div>
        {{ Form::open(array('method'=>'post',
          'id'  =>  'create_user_form',
          'url' => (@$user) ? route('users.update', ['user' => @$user->id ]) : route('users.store'),
        )) 
      }}
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="alert" style="display: none"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="mandatory">Name</label>
              {{  Form::text(
                'name',@$user->name,
                [
                  'id' => 'name',
                  'class' => 'form-control',
                ])
              }}
              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="mandatory">Email</label>
              {{  Form::text(
                'email',@$user->email,
                [
                  'id' => 'email',
                  'class' => 'form-control',
                ])
              }}
              @if ($errors->has('email'))
              <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group has-feedback{{ $errors->has('role_id') ? ' has-error' : '' }}">
              <label class="mandatory">Role</label>
              {{  Form::select(
                'role_id', 
                @$roles_user,
                (@$user->role_id) ? @$user->role_id : '',
                [
                  'id' => 'role_id',
                  'class' => 'form-control',
                ])
              }}
              @if ($errors->has('role_id'))
              <span class="help-block">
                <strong>{{ $errors->first('role_id') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
              <label class="mandatory">Status</label>
              {{  Form::select(
                'status', 
                array('1'=>'Active', '0'=>'Inactive'),@$user->status,
                [
                  'id' => 'organization_name',
                  'class' => 'form-control',
                ])
              }}
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" name="create_user">Save</button>
      </div>
      {{ Form::close() }}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->

@section('custom-script')
<script type="text/javascript">
  $(document).ready(function(e){

    $(document).on('click', 'button[name="create_user"]', function (e) {
      e.preventDefault();
      createUser();
    });

  });

  function createUser(){
    
    var form = $("#create_user_form");
    var formData = $(form).serialize();
    var url = $(form).attr('action');
    // var id = form.find('input[name="id"]').val();
    // if($.trim(id) !== ""){
    //   var role_name = $('#roles_list').find('#role_'+id+' .role_name');
    //   var role_slug = $('#roles_list').find('#role_'+id+' .role_slug');

    // }
    $(form).find('.input-group').removeClass('has-error');
    $(form).find('.help-block').html('');
    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      dataType: 'json',
      success: function (data) {
        $(form)[0].reset();
        // form.find('input[name="id"]').val('');
        DisplayMessages(data['message']);
        $('#user_modal').modal('hide');
        oUserTable.draw();
        // $('#display_name').focus();
        // $('button[name="create_role"]').html('Save');
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText;
        ErrorBlock = $(form).find('.alert');
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response, ErrorBlock, 'div');
      }
    });
  }

</script>
@endsection