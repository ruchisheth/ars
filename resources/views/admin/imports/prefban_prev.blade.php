<div class="row">
<div class="modal fade" id="PrefBanPreviewModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Pref/Bans</h4>
      </div>
      <div class="modal-body">
        <div class="nav-tabs-custom">
                      <div class="row">
                      <div class="col-md-12">                        
                      {{--  <div class="text-light-blue"><b>Note:</b>  Imports the locations where work will be performed. The chain must already exist in System.</div> --}}                   
                         <div class="box">              
                <div class="box-body no-padding">
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
                      <td>setting_type</td>
                      <td>Text</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>1</td>
                      <td>'P' for Preference or 'B' for Ban</td>                      
                    </tr>
                     <tr>
                      <td>fieldrep_code</td>
                      <td>Integer</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>6</td>
                      <td>Fieldrep code for FieldRep to apply Pref/Ban</td>
                    </tr>
                    <tr>
                      <td>chain_code</td>
                      <td>Integer</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>6</td>
                      <td>Chain to apply Pref/Ban</td>
                    </tr>
                    <tr>
                      <td>site_code</td>
                      <td>Text</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>6</td>
                      <td>Site to apply Pref/Ban.Must be a site code of above given chain code.</td>
                    </tr>
                    <tr>
                      <td>Activity</td>
                      <td>Text</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>256</td>
                      <td>Activity on you wnat to apply Pref/Ban</td>
                    </tr>
                      <tr class="text-danger">
                      <td>ForAll</td>
                      <td>Boolean</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td>Apply Pref/Ban for all clients,chains,and sites</td>                      
                    </tr> 
                     
                  </table>
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