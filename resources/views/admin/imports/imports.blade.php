@extends('app')
@section('page-title') | Import @stop
@section('content')
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="box box-default">
					<div class="box-header with-border">
						<i class="fa fa-download"></i>						
						<h3 class="box-title box-header">Import</h3><br>
						<small>All import files must follow a strict structure. The first line of each file must contain the column names for the data that you wish to import. For a listing of these column names, please click the "Preview Format" button next to the data you wish to import. Column order and data format must strictly match preview format. Only required values must appear in your import file.</small>
						<div class="box-tools pull-right">
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<table id="list-grid" class="table table-bordered">
									<thead>
										<tr>
											<th>Name</th>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='Client' >Clients</a></td>
											<td><a href="#" data-toggle="modal" data-target="#Client_PreviewModal"  >Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-clients.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='Chain' >Chains</a></td>
											<td><a href="#" data-toggle="modal" data-target="#ChainPreviewModal"  >Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-chains.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='FieldRep' >Fieldreps</a></td>
											<td><a href="" data-toggle="modal" data-target="#FieldRepPreviewModal">Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-fieldreps.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='Site' >Sites</a></td>
											<td><a href="#" data-toggle="modal" data-target="#SitePreviewModal"  >Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-sites.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='FieldRep_Org' >Fieldrep Organization</a></td>
											<td><a href="#" data-toggle="modal" data-target="#OrgPreviewModal"  >Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-fieldrep_orgs.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='Assignment' >Assignments</a></td>
											<td><a href="#" data-toggle="modal" data-target="#AssignmentPreviewModal">Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-assignments.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>

										<tr>
											<td><a href="#" data-toggle="modal" data-target="#ImportModal" data-entity='PrefBan' >PrefBans</a></td>
											<td><a href="#" data-toggle="modal" data-target="#PrefBanPreviewModal">Preview Format</a></td>
											<td><a href="{{AppHelper::ASSETS}}import-data/ars-prefbans.csv" title="Download Format File"><i class="fa fa-download"></i></a></td>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div><!-- /.box-body -->
					
				</div><!-- /.box -->

				
			</div><!-- /.box -->
		</div>
	</section>


	@include('admin.imports.import_modal') 

	@include('admin.imports.fieldrep_prev')

	@include('admin.imports.client_prev')

	@include('admin.imports.chain_prev')

	@include('admin.imports.sites_prev')

	@include('admin.imports.fieldreporg_prev')

	@include('admin.imports.assignment_prev')

	@include('admin.imports.prefban_prev')

</div>
@stop

@section('custom-script')

<script type="text/javascript">
	$(document).ready(function () {

		/* Input Mask */
		$("[data-mask]").inputmask();
		initSelect();

		$('#ImportModal').on('hidden.bs.modal',function(){
			var form = $("#import_save");
			form[0].reset();
			$( "#importfile" ).val(); 
			$('.alert').hide();
		})

		$("#importfile").fileinput({
			'initialPreviewAsData': false,
			initialPreviewConfig: [
			{key: 1, showDelete: true}
			],

			'showUpload': false,
      // 'showRemove': false,
      'autoReplace': true,
      'maxFileCount': 1,
      'data-show-upload': false,

    });
		$(".input-group-btn").click(function(){
			$('.alert').hide();
		});
		$(document).on('click', 'button[name="save_import"]', function (e) 
		{
			e.preventDefault();
			var form = $("#import_save");
			var url = form.attr('action');
			var type = "POST";

			var options = {
				target: '',
				url: url,
				type: type,
				beforeSend: function( xhr ) {
					$('#overlay').removeClass('hide');
				},
				complete: function( xhr ){
					$('#overlay').addClass('hide');
				},
				success: function(data) {

					$("#ImportModal").modal('hide');
					$('#overlay').addClass('hide');
					form[0].reset();
					$( "#importfile" ).val();
					$('.alert').hide();
					DisplayMessages(data['message']);
				},
				error: function (jqXHR, exception) {
					$('#overlay').addClass('hide');
					if(jqXHR.status == 500){
						Response = [jqXHR.statusText];
					}else{
						var Response = jqXHR.responseText;	
						Response = JSON.parse(Response);
						if(Response.error_log_file != undefined){
							window.location.href  = Response.error_log_file;
						}
					}
					ErrorBlock = $(form).find('.alert');
					DisplayErrorMessages([Response.message], ErrorBlock, 'div', null, false);
				},
				statusCode: {
					500: function() {
						$('#overlay').addClass('hide');
						ErrorBlock = $(form).find('.alert');
						DisplayErrorMessages(['Something went wrong'], ErrorBlock, 'div', null, false);
					}
				}
			}
			$(form).ajaxSubmit(options);
		});/*   Save Contact */


	});/* . dccument ready over*/

//   function SetImportItem(element,e)
//   {
// $('#ImportModal').modal('show');

//            }



</script>

@append