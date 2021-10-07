@extends('app')
@section('page-title') | {{  (@$chain->id) ? 'Chain Edit' : 'Chain Add' }} @stop
@section('content')

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-6">
				{{ Form::open(
					[
					'method'=>'post',
					'url' => route('store.chain')
					]) }}
					{{  Form::hidden('id',@$chain->id)  }}
					{{  Form::hidden('url',URL::previous())  }}
					<div class="box">
						<div class="box-header with-border">
							<i class="fa fa-cube"></i>
							<h6 class="box-title text-muted">
								{{
									(@$chain->id) ? 'Chain Edit' : 'Chain Add' 
								}}
							</h6>
						</div><!-- /.box header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									@include('includes.success')
									@include('includes.errors')
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										{{  Form::label('chain_code', 'Chain Code')}}
										{{  Form::text(
											'chain_code',(@$chain->id) ?  format_code(@$chain->id) : format_code(@$chain_id),
											[
											'id' => 'chain_code',
											'class' => 'form-control',
											'placeholder' => '0000',
											'disabled'	=>	'disabled',
											'readonly'	=> 'true',
											])
										}}
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										{{  Form::label('chain_abbrev', 'Abbrev')}}
										{{	Form::text(
											'chain_abbrev',@$chain->chain_abbrev,
											[
											'id' => 'chain_abbrev',
											'class' => 'form-control',
											'autofocus' => 'true',
											])
										}}
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										{{  Form::label('client', 'Select Client',['class'=>'mandatory']) }}
										{{  Form::select(
											'client_id',
											@$clients,
											(@$chain->client_id) ? @$chain->client_id : @$client_id,
											
											[
											'class' =>  'form-control',
											])
										}}
									</div>
									
								</div>
							</div><!-- 1st row over -->

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										{{  Form::label('chain_name', 'Chain Name',['class' => 'mandatory'])}}
										{{
											Form::text(
												'chain_name',@$chain->chain_name,
												[
												'id' => 'chain_name',
												'class' => 'form-control',	
												])
											}}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{{  Form::label('retailer_type', 'Retailer Type') }}
											
											{{  Form::select('retailer_type', 
												@$retailer_type,
												(@$chain->retailer_type) ? @$chain->retailer_type : '',
												[
												'id' => 'retailer_type',
												'class' => 'form-control',
												])
											}}
										</div>
									</div>
									@if(@$chain->id)				          
									<div class="col-md-6">
										<div class="form-group">
											{{  Form::label('status', 'Status') }}
											{{  Form::select('status', array(
												'1'  => 'Active',
												'0'  => 'Inactive',
												
												), @$chain->status,
											[
											'id' => 'status',
											'class' => 'form-control',
											])
										}}
									</div>
								</div>				           
								@endif
							</div> <!-- 2nd row over -->
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										{{  Form::label('notes', 'Notes') }}
										{{  Form::textarea(
											'notes',@$chain->notes,
											[
											'id' => 'notes',
											'class' => 'form-control',
											'rows' => 3,
											])
										}}
									</div>
								</div>
							</div> <!-- /.row -->
						</div>
						<div class="box-footer">
							<div class="pull-right">
								<div class="pull-right">
									{{  Form::submit('Save',
										[
										'id' => 'create',
										'class' => 'btn btn-primary pull-right'
										])
									}}
								</div>
								<div class="col-md-1 pull-right">			            	 
									<a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">Cancel</a> 				             
								</div>

							</div>   
							@if(@$chain->id != '')
							<h6><small>Created {{@$chain->created }} | Last modified {{ @$chain->updated }} </small></h6> 
							@endif                            
						</div><!-- /.box -->
						<div class="toggle" data-toggle-on="true" data-toggle-height="20" data-toggle-width="60"></div>
					</div>
					{{	Form::close() }}
				</div><!-- main col-md-6 -->

				<div class="col-md-6">
					<!-- show contacts -->
					@if(@$chain->id != '')			
					@include('admin.contacts.contacts',
						[
						'entity_type'=>$entity_type,
						'contact_types => $contact_types',
						'reference_id'=>$chain->id
						
						])
						@endif
						<!-- / show contacts -->
					</div>
				</div>
				
			</section>
		</div><!-- /.content-wrapper -->
		@stop

		@section('custom-script')

		<script type="text/javascript">
			$(document).ready(function () {
				
				$(this).find('[autofocus]').focus();
				$("[data-mask]").inputmask();

				$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
					checkboxClass: 'icheckbox_minimal-blue',
					radioClass: 'iradio_minimal-blue'
				});
			});


			
		</script>
		@append