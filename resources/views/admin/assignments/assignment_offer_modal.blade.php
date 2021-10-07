<div class="row">
	<div class="modal fade" id="assignment_offer_modal"><!-- modal -->
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">
						Send Offer
					</h4>
				</div>
				<div class="modal-body">
					{{  
						Form::open([
							'method' => 'post',
							'url'	=> route('offer.assignment'),
							'id' => 'assignment_offer']) 
						}}

						{{-- {{  Form::text('assignment_id')  }} --}}
						{{-- {{  Form::hidden('fieldrep_id')  }} --}}
						<div class="row">
							<div class="col-md-12">
								<div class="alert" style="display: none"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<p>Are you sure you want to send this Offer?</p>
							</div>
						</div>
						{{ Form::close() }}
					</div>
					<div class="modal-footer">
						<div class="pull-right">
							<div class="pull-right">
								<button type="button" data-dismiss="modal" id="cancel" class="btn btn-default">Cancel</button>
							</div>
							<div class="col-md-1 pull-right">
								<button type="button" class="btn btn-primary pull-right" id="offer_assignment" name="offer_assignment">Yes</button>
							</div>
						</div>                                                                                                    
					</div><!-- /.modal  -footer -->
				</div>
			</div>
		</div>
	</div><!-- /.row -->

	@section('custom-script')

	<script type="text/javascript">

		$(document).ready(function () {

			$('#assignment_offer_modal').on('shown.bs.modal', function (event) 
			{
				SelectedFieldRep = $(event.relatedTarget).data('fieldrep-id');
			});

			$('#assignment_offer_modal').on('hidden.bs.modal', function (event) 
			{
				if($('#fieldrep_schedule_modal').hasClass('in'))
				{
					setTimeout(function(){$('body').addClass('modal-open')}, 300);
				}
			});

			$(document).on('click', 'button[name="offer_assignment"]', function (e) {
				e.preventDefault();
				var form = $("#assignment_offer");
				//var formData = $(form).serialize();
				var formData = {'assignment_id': SelectedAssignment, 'fieldrep_id': SelectedFieldRep};
				
				
				var url = form.attr('action');
				var type = "POST";

				$.ajax({
					type: "POST",
					url: url,
					data: formData,
					dataType: 'json',
					success: function (data) {
						console.log(data);
						DisplayMessages(data['message']);
						$("#assignment_offer_modal").modal('hide');
						setTimeout(function(){$("#fieldrep_schedule_modal").modal('hide');}, 300);
						oAssignmentTable.draw();

					},
					error: function (jqXHR, exception) {
						var Response = jqXHR.responseText;
						ErrorBlock = $(form).find('.alert');
						Response = $.parseJSON(Response);
						DisplayErrorMessages(Response, ErrorBlock, 'div');
					}
				});
			});/*  Send Offer */

		});
	</script>
	@append
