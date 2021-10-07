<div class="col-md-6">
 <!-- TO DO List -->
    <div class="box box-default">
        <div class="box-header with-border">
            <i class="ion ion-clipboard"></i>
            <h3 class="box-title">Lists</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
            <table id="list-grid" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Lists</th>
                    </tr>
                </thead>
      </table>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
</div><!-- /.box -->
<div class="modal fade" id="lists">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Add List</h4>
      </div>
      <div class="modal-body">
        <div class="nav-tabs-custom">

          {{  Form::open(
            array('method'=>'post',
              'url' => route('store.list'), 
              'id' => 'list_save')) 
            }}
            {{  Form::hidden('id')  }}
            <div class="row">
              <div class="col-md-12">
                <div class="alert" style="display: none"></div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('list_name', 'List Name',['class' => 'mandatory']) }}
                  {{  Form::text('list_name','',
                    [
                    'id' => 'list_name',
                    'class' => 'form-control',
                    'placeholder' => '',
                    'autofocus' => true,
                    ])
                  }}
                </div>
              </div>

            </div><!-- row -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                <button type="button" class="btn btn-primary" id="save_list" name="save_list">Save</button>

              </div>
            </div>
            <!-- </div>box -->

            {{ Form::close() }}
          </div><!-- /.custom tab -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  @include('includes.confirm-modal',['name'   => 'List Item'])
  @include('admin.settings.confirm-list-modal',['name'   => 'list item'])
  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {

      listTable = $('#list-grid').DataTable({
        serverSide: true,
        paging: false,
        bFilter: false,
        bInfo: false,
        autoWidth:true,
        ajax: {
          url: '{!! url('lists') !!}',
          type: 'POST',
        }
      });

      $('#lists').on('hidden.bs.modal', function () {
        $('.alert').hide();
        var form = $("#list_save");
        form[0].reset();
      });

      $('.modal').on('shown.bs.modal', function() {     
        $(this).find('[autofocus]').focus();
      });

      $('.modal').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
      });

      $(document).on('click', 'button[name="save_list"]', function (e) {
        e.preventDefault();
        listSave();
      });

      $(document).on("keypress", "#list_save input:text", function (e) {
        if (e.keyCode == 13){
          e.preventDefault();
          listSave();
        }
      });

      function listSave(){
        var formData = $("#list_save").serialize();
        var form = $("#list_save");
        var url = $('#list_save').attr('action');
        var type = "POST";
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            $("#lists").modal('hide');
            listTable.draw(true);
            DisplayMessages(data['message']);
          },
          error: function (jqXHR, exception) {
            var Response = jqXHR.responseText;
            ErrorBlock = $(form).find('.alert');
            Response = $.parseJSON(Response);
            DisplayErrorMessages(Response, ErrorBlock, 'div');
          }
        });
      }

      $(document).on('click', 'button[name="save_listitem"]', function(e){
        e.preventDefault();
        listItemSave();
      });

      $(document).on("keypress", "#list_item_form input:text", function (e) {
        if (e.keyCode == 13){
          e.preventDefault();
          listItemSave();
        }
      });

      function listItemSave(){
        var form = $("#list_item_form");
        var formData = form.serialize();
        var Id = form.find('input[name="id"]').val();                
        var url = $('#list_item_form').attr('action');
        var type = "POST";
        if($.trim(Id) !== ""){
          var todo_item = $('.todo-list').find('.'+Id +' .text');                 
          var $parent_li = $(this).closest('li');
        }
        $("#list_item_form")[0].reset();
        form.find('input[name="id"]').val('');
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          dataType: 'json',
          success: function (res) {
            if(res.ListItemHtml){
              $('.todo-list').append(res.ListItemHtml);
            }else{
              todo_item.html(res.data);                   
            }
            $("#list_item_form")[0].reset();
          },
          error: function (jqXHR, exception) {
            var Response = jqXHR.responseText;
            ErrorBlock = $(form).find('.alert');
            Response = $.parseJSON(Response);
            DisplayErrorMessages(Response, ErrorBlock, 'div');
          }
        });
      }

      $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
      });

      $(document).on('click', 'button[name="remove_listitem"]', function(e){
        e.preventDefault();
        var $form=$(this).closest('form');
        var listitem_id =  $(this).data('id');
        var $parent_li = $(this).closest('li');
        var formData = {id :listitem_id};
        var url = APP_URL+'/list_item-delete';
        var type = "POST";
        deleteRecord('#confirm', type, url, formData,$parent_li);       
      });
    });

function RefreshSortable(){
  $(".todo-list").sortable({
    placeholder: "sort-highlight",
    handle: ".handle",
    forcePlaceholderSize: true,
    zIndex: 999999,
    update: function (event, ui) {
      var data = $(this).sortable('serialize');
      $.ajax({
        data: data,
        type: 'POST',
        url: APP_URL+'/update/list-item-order'
      });
    }
  });
}
function SetListItem(element,e)
{
  e.preventDefault();
    // var Form = $("#list_item_form");
    var list_type = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    var url = APP_URL + '/lists-item-edit/' + list_type ;
    $.ajax({
      type: "POST",
      url: url,
      data: "list_type=" + list_type,
      dataType: "json",
      beforeSend: function( xhr ) {
        $('#overlay').removeClass('hide');
      },
      complete: function( xhr ){
        $('#overlay').addClass('hide');
      },
      success: function (res) {
        $('#listitemModal').remove();
        $('body').append(res.ListItemModalHtml);
        $('#listitemModal').modal('show');
        RefreshSortable();
      },
      error: function (jqXHR, exception) {
       var Response = jqXHR.responseText;
       ErrorBlock = $('#list_item_form').find('.alert');
       Response = $.parseJSON(Response);
       DisplayErrorMessages(Response, ErrorBlock, 'div');
     }
   });
    return false;
  }


  function SetTextEdit(element,e){

    e.preventDefault();
    var Form = $("#list_item_form");
    var Id = $(element).attr('data-id');
    var parent_li = $(this).closest('li');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    var url = APP_URL + '/lists-item-edit/' + Id + '/edit';
    $.ajax({
      type: "POST",
      url: url,
      data: "id=" + Id,
      dataType: "json",
      success: function (res) {
                //$('#contacts').modal('show');
                SetFormValues(res.inputs, Form);
              }
            });

  }

</script>

@append