<div class="row">
  <div class="modal fade" id="AssignmentPreviewModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Assignment</h4>
        </div>
        <div class="modal-body">
          <div class="nav-tabs-custom">        
            
            <div class="row">
              <div class="col-md-12">                                            
               <div class="box">
                <div class="box-header">
                  <small>Assignments can be imported from here. Round Code, Chain Code and site code are mandatory and must exist in the master table before importing here. Chain code and Site code must be associated with Round Code.</small>
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
                    <tr class="text-danger">
                      <td>project_code</td>
                      <td>Text</td>
                      <td>&nbsp;</td>
                      <td>1</td>
                      <td>6</td>
                      <td>Project number from the project setup screen</td>                      
                    </tr>
                    <tr>
                      <td>round_code</td>
                      <td>Integer</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>6</td>
                      <td>Round Code</td>
                    </tr>
                    <tr class="text-danger">
                      <td>chain_code</td>
                      <td>Integer</td>
                      <td>&nbsp;</td>
                      <td>1</td>
                      <td>6</td>
                      <td>Chain Code</td>                      
                    </tr> 
                    <tr>
                      <td>site_code</td>
                      <td>Text</td>
                      <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                      <td>1</td>
                      <td>6</td>
                      <td>Site Code</td>                      
                    </tr>                     
                    <tr>
                      <td>date_intended_completion</td>
                      <td>Date</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td>Date of intended completion (YYYY-MM-DD)</td>                      
                    </tr>
                    <tr>
                      <td>time_intended_completion</td>
                      <td>Time</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td>Time of intended completion (HH:MM:SS) eg. 02:30:00</td>                      
                    </tr>
                    <tr class="text-danger">
                      <td>notes</td>
                      <td>Text</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td>256</td>
                      <td>Notes</td>                       
                    </tr>                     
                    <tr>
                      <td>assignment_code</td>
                      <td>Integer</td>
                      <td>&nbsp;</td>
                      <td>1</td>
                      <td>6</td>
                      <td>Assignment Code</td>
                    </tr>
                    <tr>
                      <td>fieldrep_code</td>
                      <td>Text</td>
                      <td>&nbsp;</td>
                      <td>1</td>
                      <td>6</td>
                      <td>FieldRep Code of the Rep you wish to schedule</td>
                    </tr>
                    <tr>
                      <td>email_rep</td>
                      <td>Boolean</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td>Determine if you want to email the FieldRep upon scheduleing ( 1/0, true/false, Yes/No )</td>                      
                    </tr>
                    <tr  class="text-danger">
                      <td>debitcard_fund_amount</td>
                      <td>Decimal</td>
                      <td>&nbsp;</td>
                      <td></td>
                      <td>8</td>
                      <td>Dollar amount to fund the associated Debit Card with</td>                      
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