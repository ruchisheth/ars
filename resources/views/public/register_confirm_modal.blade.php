<div class="row">
	<div class="modal fade" id="register_confirm_modal"><!-- modal -->
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">
						Confirm Registration
					</h4>
				</div>
				<div class="modal-body">
					{{  
						Form::open([
							'method' => 'post',
							'id' => 'register_confirm_form']) 
						}}

						{{-- {{  Form::text('assignment_id')  }} --}}
						{{-- {{  Form::hidden('fieldrep_id')  }} --}}
						<div class="row">
							<div class="col-md-12">
								<p>
									It is understood and agreed that the information listed in the preceding profile is true and accurate
								</p>
							</div>
						</div>
						{{ Form::close() }}
					</div>
					<div class="modal-footer">
						<div class="pull-right">
							<div class="pull-right">
								<button type="button" data-dismiss="modal" id="cancel" class="btn btn-default">Disagree and Exit</button>
							</div>
							<div class="col-md-1 pull-right">
								<button type="button" class="btn btn-primary pull-right" id="confirm_register" name="confirm_register">Agree and Submit</button>
							</div>
						</div>                                                                                                    
					</div><!-- /.modal  -footer -->
				</div>
			</div>
		</div>
</div><!-- /.row -->
