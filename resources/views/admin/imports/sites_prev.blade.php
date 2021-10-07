<div class="row">
  <div class="modal fade prev_modal" id="SitePreviewModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Site</h4>
        </div>
        <div class="modal-body">
          <div class="nav-tabs-custom">
            <div class="row">
              <div class="col-md-12">                        
                {{--  <div class="text-light-blue"><b>Note:</b>  Imports the locations where work will be performed. The chain must already exist in System.</div> --}}                   
                <div class="box">              
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
                        <td>chain_code</td>
                        <td>Integer</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>6</td>
                        <td>Code used to reference an existing chain</td>                      
                      </tr>
                      <tr>
                        <td>site_code</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>6</td>
                        <td>Code used to reference a specific site</td>
                      </tr>
                      <tr>
                        <td>address</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>255</td>
                        <td>Site address</td>                      
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
                        <td>site_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td></td>
                        <td>256</td>
                        <td>Site Name</td>                       
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