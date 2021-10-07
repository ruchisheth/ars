<div class="row">
  <div class="modal fade" id="roles_modal" tabindex='-1'>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-graduation-cap"></i>
          <h4 class="modal-title box-title">Roles</h4>
        </div>
        <div class="modal-body">
          @include('AdminView::roles.create')  
          @include('AdminView::roles.roles_list') 
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /row -->
@include('includes.confirm-modal',[
  'id'    =>  'delete_role_modal',
  'name'  =>  'Role'
  ])

  @section('custom-script')
  <script type="text/javascript">
    $('#roles_modal').on('hidden.bs.modal', function (event) {
      var form = $("#create_role_form");
      $(form)[0].reset();
      form.find('input[name="id"]').val('');
      $(form).find('.input-group').removeClass('has-error');
      $(form).find('.help-block').html('');
    });

    $('#roles_modal').on('shown.bs.modal', function () {
      $('#display_name').focus();
    })  
    
    
  </script>
  @append