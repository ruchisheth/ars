<div class="row"><!-- assignment editor  -->
  <div class="modal fade" id="assignment_payment_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Payments</h4>
        </div>
        <div class="modal-body">
         @include('admin.assignments.assignment_payment_editor',['payment_types' => @$payment_types])
         {{  Form::open([
           'method'=>'post',
           'id' => 'assignment_payment']) 
         }}

         <div class="box">
           <div class="box-body">
             <table id="payment-grid" class="table table-bordered table-hover" width="100%">
              <thead>
               <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>total</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

      {{ Form::close() }}
    </div><!-- /.modal-body -->
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->

@include('includes.confirm-modal',['name'   => 'Payment','id'  => 'confirm_delete_payment'])

@section('custom-script')

<script type="text/javascript">
  var oPaymentTable ='';
  $(document).ready(function () {
    $('#assignment_payment_modal').on('hidden.bs.modal', function (event) 
    {
      var form=$("#payment_save_form");
      form.find('#reset_payment').trigger('click');
      form.find('input[name="assignment_id"]').val('');

    });

    $('#confirm_delete_payment').on('hidden.bs.modal', function (event) 
    {
      if($('#assignment_payment_modal').hasClass('in'))
      {
        setTimeout(function(){$('body').addClass('modal-open')}, 200);
      }
    });

    $(document).on('click', 'button[name="remove_payment"]', function(e){

      e.preventDefault();
      var form=$("#payment_save_form");
      var $parent_tr = $(this).closest('tr');
      var payment_id =  $(this).data('id');
      var formData = {id :payment_id};
      var url = APP_URL+'/payments-delete';
      var type = "POST";
      $('#confirm_delete_payment').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function() {
        $.ajax({
          type: type,
          url: url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            $parent_tr.remove();
            form.find('#reset_payment').trigger('click');
            DisplayMessages('Payment removed successfully','success');
          },
          error: function (data) {
            DisplayMessages('Please try again','error');
          }
        });
      });
    }); 
    $('#assignment_payment_modal').on('shown.bs.modal', function (event) 
    {
      var assignment_id = $(event.relatedTarget).data('id');
      $(event.currentTarget).find('input[name="assignment_id"]').val(assignment_id);
    });
  });/* ready over*/

  function initPaymentTable(ele,event){
    event.preventDefault();
    assignment_id = $(ele).data('id');
    //oPaymentTable.serverSide  = true;
    //oPaymentTable.ajax.url(APP_URL+'/payments/'+assignment_id);
    //oPaymentTable.draw(true);
    oPaymentTable = $('#payment-grid').dataTable().fnDestroy();
    oPaymentTable = $('#payment-grid').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": false,
      "bFilter": false,
      "bInfo": false,
      "autoWidth":true,
      ajax: {
        url: APP_URL+'/payments/'+assignment_id,
        type: 'POST',
      },
      columns: [
      {data: 'payment_type', name: 'payment_type'},
      {data: 'qty', name: 'qty'},
      {data: 'pay_rate', name: 'pay_rate', orderable: false},
      {data: 'total', name: 'total'},
      {data: 'action', name: 'action', orderable: false, searchable: false}
      ],
    });
  }

  

</script>
@append