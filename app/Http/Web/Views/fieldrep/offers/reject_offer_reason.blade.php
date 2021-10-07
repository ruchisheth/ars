<div class="row">
  <!-- modal -->
  <div class="modal fade" id="reason_reject">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
         <h4 class="modal-title">Reason</h4>
       </div>
       <div class="modal-body">
         {{ Form::open(['method'=>'post']) }}
         <div class="row">
          <div class="col-md-12">
            <div class="alert" style="display: none"></div>
          </div>
        </div>
        <p>Please select the reason why you are unable to perform this assignment.</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>
                <label>
                  {{ Form::radio('reject_reason', '1', false,
                    ['class'=>'minimal custom_radio']) 
                  }}
                  <span class="rb_span">{{ config('constants.OFFERREJECTREASON.1') }}</span>
                </label>
              </label>
              <br>
              <label>  
                {{ Form::radio('reject_reason', '2', false,
                ['class'=>'minimal custom_radio']) }}
                <span class="rb_span">{{ config('constants.OFFERREJECTREASON.2') }}</span>
              </label>
              <br>
              <label>  
                {{ Form::radio('reject_reason', '3', false,
                ['class'=>'minimal custom_radio']) }}
                <span class="rb_span">{{ config('constants.OFFERREJECTREASON.3') }}</span>
              </label>
              <br>
              <label>  
                {{ Form::radio('reject_reason', '4', false,
                ['class'=>'minimal custom_radio']) }}
                <span class="rb_span">{{ config('constants.OFFERREJECTREASON.4') }}</span>
              </label>
              <br>
              <label>  
                {{ Form::radio('reject_reason', '5', false,
                ['id' => 'reason_other', 'class'=>'minimal custom_radio']) }}
                <span class="rb_span">Other (please specify)</span>
              </label>
            </div>
            <div class="form-group">
              <div id="other_reason" class="">
                {{ Form::textarea('other_reason','',['class' => 'form-control', 'rows' => 3])}}
              </div>
            </div>
          </div>
        </div>
        {{ Form::close() }}
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-primary" id="submit_reason">Submit</button>
       <button type="button" data-dismiss="modal" class="btn">Cancel</button>
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->


