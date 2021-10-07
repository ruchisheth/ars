<div class="box-body">
  <ul class="todo-list" id="roles_list">
    @foreach($roles as $role)   
    @include('admin.role.roles_li')
    @endforeach
  </ul>
</div>
@section('custom-script')
<script type="text/javascript">

  var role_grid = "";
  $(document).ready(function(e){
  });


  $(document).on('click', 'button[name="remove_role"]', function(e){
    e.preventDefault();
    var role_id =  $(this).data('id');
    var url = $(this).data('action');
    $('#delete_role_modal').find('#delete').bind('click', function() {
      $.ajax({
        type: 'POST',
        url: url,
        data: {_method: "delete"},
        dataType: 'json',
        success: function (data) {
          $('#roles_list').find('#role_'+data.role).remove();
          $('#delete_role_modal').modal('hide');
          DisplayMessages(data['message']);
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;          
          Response = $.parseJSON(Response);
          DisplayMessages(Response.message,'error');
          $('#delete_role_modal').modal('hide');
        }
      });
    });
  });


  $(document).on('click', '#delete_role', function(e){
    e.preventDefault();
    var form = $(this).parent('form');
    url = $(form).attr('action');
    $.ajax({
      type: 'POST',
      url: url,
      data: {_method: "delete"},
      dataType: 'json',
      success: function (data) {
        $('#roles_list').find('#role_'+data.role).remove();
        $('#delete_role_modal').modal('hide');
        DisplayMessages(data['message']);
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText;          
        Response = $.parseJSON(Response);
        DisplayMessages(Response.message,'error');
        $('#delete_role_modal').modal('hide');
      }
    });
  });

  $("#roles_list").sortable({
    placeholder: "sort-highlight",
    handle: ".handle",
    forcePlaceholderSize: true,
    zIndex: 999999,
    update: function (event, ui) {
      var data = $(this).sortable('serialize');
      $.ajax({
        data: data,
        type: 'POST',
        url: APP_URL+'/roles/update/role-order'
      });
    }
  });
</script>
@append