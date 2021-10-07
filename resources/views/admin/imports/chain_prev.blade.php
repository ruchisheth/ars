<div class="row">
  <div class="modal fade prev_modal" id="ChainPreviewModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Chain</h4>
        </div>
        <div class="modal-body">
          <div class="nav-tabs-custom">

            
            <div class="row">
              <div class="col-md-12">
                <div class="alert" style="display: none"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">                                        
                <div class="box">    
                  <div class="box-header">
                    <small>Client must exist in system before importing chains for that client from here.</small>
                  </div><!-- /.box-header -->           
                  <div class="box-body no-padding">
                   <div class="table-responsive">
                    <table class="table table-condensed" style="font-size:11px">
                      <tr>
                        <th>Column</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Description</th>                      
                      </tr>
                      <tr>
                        <td>chain_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>255</td>
                        <td>Name of the client or chain</td>                      
                      </tr>
                      <tr>
                        <td>client_code</td>
                        <td>Integer</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>6</td>
                        <td>Code used to reference a specific client <p class="text-danger">Enter client_code to reference a specific client for a chain only</p></td>
                      </tr>
                      <tr>
                        <td>first_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>255</td>                        
                        <td>First Name of Contact Person</td>
                      </tr>
                      <tr>
                      <tr>
                        <td>last_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>255</td>
                        <td>Last Name of Contact Person</td>
                      </tr>
                      <tr>
                        <td>address1</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>255</td>
                        <td>Primary Address</td>                      
                      </tr>
                      <tr>
                        <td>address2</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>255</td>
                        <td>Primary Address Second Line</td>                      
                      </tr>
                      <tr>
                        <td>city</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>64</td>
                        <td>City</td>                      
                      </tr>
                      <tr>
                        <td>state</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>2</td>
                        <td>2</td>
                        <td>2 Letter state abbreviation. eg. FL</td>                      
                      </tr>
                      <tr>
                        <td>zip</td>
                        <td>Zipcode</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>5</td>
                        <td>10</td>
                        <td>Zipcode</td>                      
                      </tr>
                      <tr>
                        <td>phone</td>
                        <td>Integer</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Phone Number eg. 222-222-2222</td>
                      </tr>                                       
                      <tr>
                        <td>notes</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Notes</td>                       
                      </tr>
                      
                      
                      
                    </table>
                  </div>
                </div><!-- /.box-body -->

              </div><!-- /.box -->
              
            </div>
          </div>
          <div class="box-footer">
            <div class="pull-right">
              <div class="pull-right">
               
              </div>
              <div class="col-md-1 pull-right">
                <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
              </div>
            </div>                      
          </div><!-- /.box-footer -->

          
          
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div>
@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {
  });

</script>
@append