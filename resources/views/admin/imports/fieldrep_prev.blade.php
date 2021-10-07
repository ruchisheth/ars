<div class="row">
  <div class="modal fade prev_modal" id="FieldRepPreviewModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Fieldrep</h4>
        </div>
        <div class="modal-body">
          <div class="nav-tabs-custom">       
            <div class="row">
              <div class="col-md-12">   
                {{--  <div class="text-light-blue">Import FieldReps.These are the members of your work force who will be completing assignments.</div> --}}

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
                        <td>fieldrep code</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>6</td>
                        <td>Rep's code</td>                      
                      </tr>
                      <tr>
                        <td>first_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>64</td>
                        <td>Rep's first name</td>                      
                      </tr>
                      <tr class="text-danger">
                        <td>mi</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>1</td>
                        <td>Middle Initial</td>                      
                      </tr>
                      <tr>
                        <td>last_name</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>1</td>
                        <td>64</td>
                        <td>Last Name</td>                      
                      </tr>
                      <tr>
                        <td>email</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>7</td>
                        <td>255</td>
                        <td>Email Address</td>                      
                      </tr>
                      <tr>
                        <td>password</td>
                        <td>Text</td>
                        <td class="text-bold text-success"><i class="fa fa-check"></i></td>
                        <td>6</td>
                        <td>32</td>
                        <td>Password</td>                      
                      </tr>
                      <tr class="text-danger">
                        <td>alternate_email</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>7</td>
                        <td>255</td>
                        <td>Alternate Email Address</td>                      
                      </tr>
                      {{-- <tr>
                        <td>dob</td>
                        <td>Date</td>
                        <td>&nbsp;</td>
                        <td>8</td>
                        <td>8</td>
                        <td>Date Of Birth(YYYY-MM-DD) eg.1990-12-30</td>                      
                      </tr> --}}
                      {{-- <tr>
                        <td>gender</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>4</td>
                        <td>6</td>
                        <td>Male,Female</td>                      
                      </tr> --}}
                      <tr>
                        <td>address1</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>255</td>
                        <td>Rep's Primary Address</td>                      
                      </tr>
                      <tr>
                        <td>address2</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>255</td>
                        <td>Rep's Primary Address Second Line</td>                      
                      </tr>
                      <tr>
                        <td>city</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>64</td>
                        <td>Rep's City of Primary Address</td>                      
                      </tr>
                      <tr>
                        <td>state</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>2</td>
                        <td>2</td>
                        <td>Rep's State of Primary Address eg. FL</td>                      
                      </tr>
                      <tr>
                        <td>zip</td>
                        <td>Zipcode</td>
                        <td>&nbsp;</td>
                        <td>5</td>
                        <td>10</td>
                        <td>Rep's zip of Primary Address </td>                      
                      </tr>
                      <tr>
                        <td>phone</td>
                        <td>Integer</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Rep's Primary Address Phone Number eg.222-222-2222</td>
                      </tr>
                      <tr class="text-danger">
                        <td>phone_ext</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>8</td>
                        <td>Primary Phone Extension</td>                     
                      </tr>
                      <tr class="text-danger">
                        <td>work_phone</td>
                        <td>Integer</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Work Phone</td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>work_phone_ext</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>8</td>
                        <td>Work Phone Extension</td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>other_phone</td>
                        <td>Integer</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Other Phone</td>                        
                      </tr>
                      <tr class="text-danger">
                        <td>other_phone_ext</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>8</td>
                        <td>Other Phone Extension</td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>fax</td>
                        <td>Intger</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Rep's Primary Address Fax Number</td>                       
                      </tr>
                      <tr>
                        <td>cellphone</td>
                        <td>Intger</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>10</td>
                        <td>Rep's Cellphone Number</td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>hourly_rate</td>
                        <td>Decimal</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep's hourly rate for payroll purposes</td>                       
                      </tr>
                      <tr>
                        <td>subcontractor_code</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>1</td>
                        <td>6</td>
                        <td>Code for the FieldReps subcontractor</td>                       
                      </tr>
                      <tr>
                        <td>distance_willing_to_travel</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>4</td>
                        <td>Distance willing to travel.Must match option on Profile.
                        </td>                       
                      </tr>
                      <tr>
                        <td>has_digital_camera</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep has a digital camera.If yes then 1 else 0.
                        </td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>digital_camera_supports_minicd</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Digital camera supports mini-CDs.If yes then 1 else 0.
                        </td>                       
                      </tr>
                      <tr>
                        <td>has_computer</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep has a Computer.</td>                       
                      </tr>
                      {{-- <tr>
                        <td>notes</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Notes pertaining to the rep</td>                       
                      </tr> --}}
                      <tr>
                        <td>experience</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>255</td>
                        <td>Rep's work experience in different fields.Must match on profile eg.Financial, Technical</td>
                      </tr>
                      <tr>
                        <td>availability</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>1024</td>
                        <td>Availability must be like eg. <p class="text-danger">'Weekdays;Mornings,Weekends;Afternoons' or 'Monday;Evenings,Tuesday;Mornings' or 'All'</p></td>                       
                      </tr>
                      <tr>
                        <td>education</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>255</td>
                        <td>Highest education level.Must match on Profile</td>
                      </tr>
                      <tr>
                        <td>active</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>FieldRep is active or not( 1/0, true/false, Yes/No ). Default true</td>                       
                      </tr>
                      <tr>
                        <td>approved_for_work</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>FieldRep is approved for work or not( 1/0, true/false, Yes/No ). Default true</td>                       
                      </tr>
                      <tr>
                        <td>paperwork_received</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Paperwork has been received</td>                       
                      </tr>
                      <tr class="text-danger">
                        <td>view_name</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>50</td>
                        <td>Name of the view to associate the FieldRep to</td>
                      </tr>
                      <tr class="text-danger">
                        <td>element_name_level_1</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>25</td>
                        <td>Name of the element at level 1 for view association</td>
                      </tr>
                      <tr class="text-danger">
                        <td>element_name_level_2</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>25</td>
                        <td>Name of the element at level 2 for view association</td>
                      </tr>
                      <tr class="text-danger">
                        <td>element_name_level_3</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>25</td>
                        <td>Name of the element at level 3 for view association</td>
                      </tr>
                      <tr class="text-danger">
                        <td>element_name_level_4</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>25</td>
                        <td>Name of the element at level 4 for view association</td>
                      </tr>
                      <tr class="text-danger">
                        <td>element_name_level_5</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>25</td>
                        <td>Name of the element at level 5 for view association</td>
                      </tr>
                      <tr class="text-danger">
                        <td>card_num</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td>16</td>
                        <td>16</td>
                        <td>Rep's debit card number</td>
                      </tr>
                      <tr>
                        <td>have_done</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>1024</td>
                        <td>Comma delimited list of activities,must match options on profile</td>
                      </tr>
                      <tr>
                        <td>interested_in</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>1024</td>
                        <td>Comma delimited list of activities,must match options on profile</td>
                      </tr>
                      <tr class="text-danger">
                        <td>languages</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>1024</td>
                        <td>Comma delimited list of languages,must match options on profile</td>
                      </tr>
                      <tr class="text-danger">
                        <td>language_other</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>1024</td>
                        <td>Other languages</td>
                      </tr>
                      <tr>
                        <td>employed</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep is employed</td>
                      </tr>
                      <tr>
                        <td>occupation</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>64</td>
                        <td>Rep's occupation</td>
                      </tr>
                      <tr class="text-danger">
                        <td>ethnicity</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>30</td>
                        <td>Rep's ethnicity,must match on profile</td>
                      </tr>
                      <tr>
                        <td>can_print</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep can print</td>
                      </tr>
                      <tr>
                        <td>browser</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>255</td>
                        <td>Rep's browser,Must match on profile</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history1</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Rep has experience with history1</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history2</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Rep has experience with history2</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history3</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Rep has experience with history3</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history1_exp</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Explanation of Reps history with history1</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history2_exp</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Explanation of Reps history with history2</td>
                      </tr>
                      <tr class="text-danger">
                        <td>history3_exp</td>
                        <td>Text</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>256</td>
                        <td>Explanation of Reps history with history3</td>
                      </tr>
                      <tr class="text-danger">
                        <td>apply_date</td>
                        <td>Date</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Date of the FieldRep applied</td>
                      </tr>
                      <tr>
                        <td>has_smartphone</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep has a smartphone.(1 | 0)</td>
                      </tr>
                      <tr>
                        <td>has_internet</td>
                        <td>Boolean</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td>Rep has internet.(1 | 0)</td>
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