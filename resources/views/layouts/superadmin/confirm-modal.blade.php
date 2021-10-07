        <div class="row">
          <!-- modal -->
          <div class="modal fade" id="{{ (@$id) ? @$id : 'confirm'}}">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                       <h4 class="modal-title">{{ (@$action) ? ucwords(@$action): 'Delete' }} {{ (@$name) ? ucwords($name) : '' }}</h4>
                   </div>
                   <div class="modal-body">
                       <p>{{ (@$msg) ? : 'Are you sure you want to delete this '.@ucwords($name).'?' }}</p>
                   </div>
                   <div class="modal-footer">
                       <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">{{ (@$btn[0]) ?  : 'Delete'}}</button>
                       <button type="button" data-dismiss="modal" class="btn">{{ (@$btn[1]) ?  : 'Cancel' }}</button>
                   </div>
               </div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
   </div><!-- /row -->
