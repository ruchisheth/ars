        <div class="row">
          <!-- modal -->
          <div class="modal fade " id="confirm-list-modal">
             <div class="modal-dialog" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                         <h4 class="modal-title">Delete {{ ucwords($name) }}</h4>
                     </div>
                     <div class="modal-body">
                         <p>Are you sure to delete this {{ ucwords($name) }}?</p>
                     </div>
                     <div class="modal-footer">
                         <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                         <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                     </div>
                 </div><!-- /.modal-content -->
             </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div><!-- /row -->