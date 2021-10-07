<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Payments</h3>
    <div class="box-tools">
      {{  Form::button('<i class="fa fa-plus"></i>',
      [
      'id' => 'create_payment',
      'class' => 'btn btn-box-tool pull-right',
      'data-toggle' => 'modal',
      'data-target' => '#payment'
      ])
  }}
</div>

      <!-- <div class="col-md-6" style="float:right">
        <div class="alert" style="display: none"></div>
    </div> -->
</div>

<div class="box-body">
<div class="table-responsive">
    <table id="payment-grid" class="table table-bordered table-hover" width="100%">
      <thead>
         <tr>
            <th rowspan="2">Item</th>
            <th colspan="3">Rep Payments</th>
            <th colspan="2">Client Billing</th>
            <th rowspan="2">&nbsp;</th>
        </tr>
        <tr>
            <th>Qty</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Rate</th>
            <th>Total</th>
        </tr>
    </thead>
</table>
</div>
</div><!-- /.box-body -->
</div><!-- /.box -->

@include('admin.payments.payment_editor_modal')

@include('includes.confirm-modal',['name'   => 'Payment'])


@section('custom-script')
<script type="text/javascript">

    $(document).ready(function () {

        oPaymentTable = $('#payment-grid').DataTable({
            "processing": true,
            "serverSide": true,
            "paging": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "autoWidth":true,
            ajax: {
                url: '{!! url("payments",[@$assignment->id]) !!}',
                type: 'POST',
            },
            columns: [
                {data: 'payment_type', name: 'payment_type'},
                {data: 'qty', name: 'qty'},
                {data: 'pay_rate', name: 'pay_rate'},
                {data: 'total', name: 'total'},
                {data: 'bill_rate', name: 'bill_rate'},
                {data: 'bill_total', name: 'bill_total'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

    });

</script>
@endsection



