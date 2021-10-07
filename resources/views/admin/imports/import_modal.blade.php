<div class="row">
<div class="modal fade" id="ImportModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Import File</h4>
      </div>
      <div class="modal-body">
        <div class="nav-tabs-custom">

          {{  Form::open(
          array('method'=>'post',
          'url'  =>  route('import.data'),
          'id' => 'import_save',
          'enctype' =>  "multipart/form-data")) 
        }}
        {{  Form::hidden('entity','',
          [
            'id' => 'entity'
          ])  }}
        <div class="row">
          <div class="col-md-12">
            <div class="alert" style="display: none"></div>
          </div>
        </div>
                      <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('import', 'Fieldrep') }}
                          {{  Form::file(
                          'importfile',
                          [
                          'id' => 'importfile',
                          'class' => 'file-loading',                          
                          'data-allowed-file-extensions'=>'["csv", "txt"]',
                          'data-file' =>  '',                        
                          ]) 
                        }}
                        <p class="help-block">Upload your File.</p>
                        
                        </div>
                     </div> 
                    {{--  <table class="table-condensed">
                       <tr>
                        <td><a href="" data-toggle="modal" data-target="#FieldRepPreviewModal">Preview Format</a></td>
                       </tr>
                     </table>  --}}

                      </div>
                            <div class="box-footer">
                              <div class="pull-right">
                                <div class="pull-right">
                                  <button type="button" class="btn btn-primary" id="save_import" name="save_import">Save</button>
                                </div>
                                <div class="col-md-1 pull-right">
                                  <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                                </div>
                              </div>                      
                            </div><!-- /.box-footer -->

      {{ Form::close() }}
  
  </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

@section('custom-script')

<script type="text/javascript">
$(document).ready(function () {

 $('#ImportModal').on('shown.bs.modal', function (event) 
    {
        var entity = $(event.relatedTarget).data('entity');
        $(event.currentTarget).find('#entity').val(entity);
        $(event.currentTarget).find('label[for="import"]').text(entity);
     });
 });

</script>
@endsection