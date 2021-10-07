{{  Form::open([
  'method'=>'post',
  'url' => route('store.payment'),
  'id' => 'payment_save_form']) 
}}
{{ Form::hidden('payment_id')}}
{{ Form::hidden('assignment_id', @$assignment_id)}}
<div class="box">
  <div class="box-header with-border">
    <h6 class="box-title">Add Payment</h6>
    <div class="box-tools pull-right">
    </div>
  </div>
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <div class="alert" style="display: none"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('payment_type', 'Payment Type', ['class' => 'mandatory'])}}
          {{  Form::select(
            'payment_type',@$payment_types,'',
            [
            'id' => 'payment_type',

            'class' => 'form-control',
            ])
          }} 
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          {{  Form::label('qty', 'Quantity', ['class' => 'mandatory']) }}
          {{  Form::number(
            'qty','1',
            [
            'id' => 'qty',
            'class' => 'form-control',
            'min' => '1',
            ])
          }} 
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('rep_pay_type', 'Rep Payment Type', ['class' => 'mandatory'])}}
          {{  Form::select(
            'rep_payment_type',[
            '' => 'Select Rep Payment Rate',
            'rep_pay_rate' => 'Use Rep Pay Rate',
            'manual' => 'Use this Rate',
            ],'',
            [
            'id' => 'rep_payment_type',
            'class' => 'form-control',
            ])
          }} 

        </div>
      </div>
      <div class="repPay hide">
        <div class="col-md-3">
          <div class="form-group">
            {{  Form::label('pay_rate', 'Rep Payment', ['class' => 'mandatory'])}}
            {{  Form::text(
              'pay_rate','',
              [
              'id' => 'pay_rate',
              'class' => 'form-control',
              ])
            }}
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group no-label-control">
            {{  Form::select(
              'pay_type',[
              'h' => 'Per Hour',
              'f' => 'Per Assignment',
              ],'',
              [
              'id' => 'pay_type',
              'class' => 'form-control',
              ])
            }} 
          </div>
        </div>
      </div>
    </div>

    <div class="box-footer">
      <div class="pull-right">
        
        <button type="button" id="reset_payment" class="btn btn-default">Reset</button>
        
          {{  Form::button('Save',
            [
            'name' => 'save_payment',
            'id' => 'save_payment',
            'class' => 'btn btn-primary'
            ])
          }}

      </div>

    </div>
  </div><!-- /.box-body -->
</div>
{{ Form::close() }}


@section('custom-script')

<script type="text/javascript">
  var oPaymentTable ='';
  $(document).ready(function ()
  {
    if ( $('#payment_edit_box').length > 0 ) {
      $('#payment_edit_box').hide();
    }

    $('#rep_payment_type').on('change',function(){
      rep_payment_type = $(this).val();
      if(rep_payment_type == 'rep_pay_rate'){
        $('.repPay').addClass('hide');
      }else if(rep_payment_type == 'manual'){
        $('.repPay').removeClass('hide');
      }else{
        $('.repPay').addClass('hide');
      }
    })

    $('#reset_payment').click(function(){
      var form = $("#payment_save_form");
      form[0].reset();
      form.find('input[name="payment_id"]').val('');
      $('#rep_payment_type').val('').trigger('change');
      form.find('input[name="is_client_bill"]').iCheck('uncheck');
      form.find('input[name="is_client_bill"]').iCheck('update');
      form.find('input[name="is_client_bill"]').prop('checked',false);
    });

    $('#is_client_bill').on('ifToggled', function(event){
      $('.clientPay').toggleClass('hide');
    });

    $(document).on('click', 'button[name="save_payment"]', function (e) {
      e.preventDefault();
      var form = $("#payment_save_form");
      var assignment_id = form.find('input[name="assignment_id"]').val();
      var formData = form.serialize();
      var url = form.attr('action');
      var type = "POST";
      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          /*assignment review div*/
          if ( $('#payment_edit_box').length > 0 ) {
            $('#payment_edit_box').slideUp( "slow" );
            $('#payment-grid').slideDown( "slow" );
          }
          oPaymentTable.draw(true);
          $('#reset_payment').trigger('click');
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    });/*   Save Payment */

  });/* /.document-ready over */

  function setPaymentEdit(element,e)
  {

    e.preventDefault();
    payment_id = $(element).data('id');

    var form = $("#payment_save_form");
    var url = APP_URL + '/payments/' + payment_id + '/edit';
    $.ajax({
      type: "POST",
      url: url,
      data: {payment_id: payment_id},
      dataType: "json",
      success: function (res) {
        var rep_payment_type = res.inputs.rep_payment_type.value;
        $('#rep_payment_type').val(rep_payment_type).trigger('change');
        SetFormValues(res.inputs, form);
        if ( $('#payment_edit_box').length > 0 ) {
          $('#payment-grid').hide();
          $('#payment_edit_box').slideDown('slow');
        }

      }
    });
  }

</script>
@append