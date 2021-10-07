@extends('layouts.web.main_layout')
@section('content')
<section class="content">
 <div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-solid">
     <div class="cards-panel">
      <div class="tab-content">
       <ul class="nav nav-tabs">
        <li class="{{ (@$sOfferStatus == config("constants.OFFERSTATUS.PENDING")) ? 'active' : ''}}" onClick="callShowAssignmentOfferList('{{ config("constants.OFFERSTATUS.PENDING") }}', '{{ route('fieldrep.offer-list') }}');">
         <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.offer_status.pending') }} | {{ $aAssignmentOffersCount['pending_offer_count'] }}</a>
       </li>
       <li class="{{ (@$sOfferStatus == config("constants.OFFERSTATUS.ACCEPTED")) ? 'active' : ''}}" onClick="callShowAssignmentOfferList('{{ config("constants.OFFERSTATUS.ACCEPTED") }}', '{{ route('fieldrep.offer-list') }}');">
         <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.offer_status.accepted') }} | {{ $aAssignmentOffersCount['accepted_offer_count'] }}</a>
       </li>
       <li class="{{ (@$sOfferStatus == config("constants.OFFERSTATUS.REJECTED")) ? 'active' : ''}}" onClick="callShowAssignmentOfferList('{{ config("constants.OFFERSTATUS.REJECTED") }}', '{{ route('fieldrep.offer-list') }}')">
         <a href="#completed_assignments" data-toggle="tab">{{ trans('messages.offer_status.rejected') }} | {{ $aAssignmentOffersCount['rejected_offer_count'] }}</a>
       </li>
     </ul>
     <div class="card-body">
      <div class="card-list">
       <div class="input-group margin">
        <input type="text" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
        </span>
      </div>

      @include('layouts.web.loader')
      <div class="" id="offer-list">
       @include('WebView::fieldrep.offers._more_offers_list', ['oAssignmentOffers' => $oAssignmentOffers])
     </div>
   </div>
 </div>
</div>
</div>
</div>
</div>
@include('includes.confirm-modal',
  [
    'action' => 'Accept',
    'name'   => 'Offer',
    'id'  => 'accept_offer',
    'msg' => 'Are you sure you want to accept the Offer?',
    'btn' => ['Yes','No'],
    ])

@include('includes.confirm-modal',
  [
    'action' => 'Reject',
    'name'   => 'Offer',
    'id'  => 'reject_offer',
    'msg' => 'Are you sure you want to reject these Offers?',
    'btn' => ['Yes','No'],
    ])

    @include('WebView::fieldrep.offers.reject_offer_reason')
  </section>
{{-- </div> --}}
<!--Tab Content-->
{{-- </section> --}}
@stop

@section('custom-scripts')
<script type="text/javascript">

  $(document).on('click','.offer-accept', function () {
    var eOffer = $(this);
    var nIdOffer = $(this).data('id_offer')
    var aOffer = [nIdOffer];
    console.log(aOffer);
    $('#accept_offer').modal();
    $('#accept_offer').find('#delete').bind('click', function() {
      $.ajax({
        type: 'POST',
        url: APP_URL+'/fieldrep/offers/accept',
        data: {'aIdOffer': aOffer},
        dataType: 'json',
        success: function (data) {
            $(eOffer).parent('.row.no-padding').remove();
          },
          error: function (jqXHR, ) {
          }
        });
    });      
  });

  $(document).on('click','.offer-reject', function () {
    var eOffer = $(this);
    var nIdOffer = $(this).data('id_offer');
    var aOffer = [nIdOffer];
    $('#reject_offer').modal();
    $('#reject_offer').find('#delete').bind('click', function() {
      setTimeout(function() {$('#reason_reject').modal();},500);
      $('#reason_reject').find('#submit_reason').bind('click', function() {
        var sOtherReason = '';
        $form =  $('#reason_reject').find('form');
        ErrorBlock = $form.find('.alert');
        var sReasonToReject = $('input[name="reject_reason"]:checked').val();
        reason_reject = $('input[name="reject_reason"]:checked').val();
        if(typeof sReasonToReject == "undefined") {
          DisplayErrorMessages(['Please select a reason why you are unable to perform this assignment.'], ErrorBlock, 'div');
          return;
        }
        // formData += "&reject_reason=" + sReasonToReject;
        if(sReasonToReject == 5){
          sOtherReason = $('textarea[name="other_reason"]').val();
          if(sOtherReason == "") {
            DisplayErrorMessages(['Please specify a reason.'], ErrorBlock, 'div');
            return;
          }
          // formData += "&other_reason=" + sOtherReason;
        }
        $.ajax({
          type: 'POST',
          url: APP_URL+'/fieldrep/offers/reject',
          data: {'aIdOffer': aOffer, 'sReasonToReject': sReasonToReject, 'sOtherReason': sOtherReason},
          dataType: 'json',
          success: function (data) {
            $('#reason_reject').modal('hide');
            $(eOffer).parent('.row.no-padding').remove();
          },
          error: function (data) {
          }
        });
      });





      // $.ajax({
      //   type: 'POST',
      //   url: APP_URL+'/fieldrep/offers/accept',
      //   data: {'aIdOffer': aOffer},
      //   dataType: 'json',
      //   success: function (data) {
      //       // DisplayMessages(data['message']);
      //     },
      //     error: function (jqXHR, ) {
      //     }
      //   });
    });      
  });

  $('#reason_reject').on('hidden.bs.modal',function(){
    var $form=$(this).find('form');
    $(this).find('input[name="reject_reason"]').iCheck('uncheck');
    $("#other_reason").hide();
    $form[0].reset();
    $('#reason_reject').find( "#submit_reason" ).unbind( "click" );
  });

  $('#other_reason').hide();
  $('input[name="reject_reason"]').on('ifChecked', function(event){
    var val = $(this).val();
    if(val == 5){
      $("#other_reason").slideDown(500);
    }else{
      $("#other_reason").slideUp(500);
    }
  });
</script>
@stop
