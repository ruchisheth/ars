@extends('app')
@section('page-title') | {{  (@$fieldrep_org->id) ? 'FieldRep Organization Edit' : 'FieldRep Organization Add'  }} @stop
@section('content')

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-6">
				{{ Form::open(array('method'=>'post',
					'url' => route('store.fieldreporgs'),)) 
				}}
				{{  Form::hidden('id',@$fieldrep_org->id)  }}
				{{  Form::hidden('url',URL::previous())  }}
				<div class="box">
					<div class="box-header">
						<i class="fa fa-institution"></i>
						<h6 class="box-title text-muted">
							{{
								(@$fieldrep_org->id) ? 'FieldRep Organization Edit' : 'FieldRep Organization Add' 
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
									{{  Form::label('fieldrep_org_code', 'FieldRep Org Code')}}
									{{  Form::text(
										'fieldrep_org_code',(@$fieldrep_org->id) ?  format_code(@$fieldrep_org->id) : format_code(@$fieldrep_org_id),
										[
										'id' => 'fieldrep_org_code',
										'class' => 'form-control',
										'placeholder' => '0000',
										'disabled'	=>	'disabled',
										'readonly'	=> 'true',
										])
									}}
								</div>
							</div>
							@if(@$fieldrep_org)
							<div class="col-md-6">
								<div class="form-group">
									{{  Form::label('status', 'Status') }}
									{{  Form::select('status', array(
										''   => 'Select Status',
										'0'	 =>	'Inactive',	 		
										'1'	 =>	'Active',
										), @$fieldrep_org->status,
									[
									'id' => 'status',
									'class' => 'form-control',
									])
								}}
							</div>
						</div>
						@endif
					</div><!-- 1st row over -->

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								{{  Form::label('fieldrep_org_name', 'FieldRep Org Name',['class' => 'mandatory'])}}
								{{
									Form::text(
										'fieldrep_org_name',@$fieldrep_org->fieldrep_org_name,
										[
										'id' => 'fieldrep_org_name',
										'class' => 'form-control',
										'autofocus' => 'true',
										])
									}}
								</div>
							</div>
						</div>
						<div class="row">

						</div> <!-- 2nd row over -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									{{  Form::label('notes', 'Notes') }}
									{{  Form::textarea(
										'notes',@$fieldrep_org->notes,
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
						@if(@$fieldrep_org->id != '')
						<h6><small>Created {{ @$fieldrep_org->created }} | Last modified {{ @$fieldrep_org->updated }} </small></h6>
						@endif                            
					</div><!-- /.box -->
					<div class="toggle" data-toggle-on="true" data-toggle-height="20" data-toggle-width="60"></div>
				</div>
				{{	Form::close() }}
			</div><!-- main col-md-6 -->

			

			<div class="col-md-6">

				<!-- show contacts -->
				@if(@$fieldrep_org->id != '')

				@include('admin.contacts.contacts',[
					'entity_type'=>$entity_type,  
					'contact_types => $contact_types',
					'reference_id'=>$fieldrep_org->id

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

			$("[data-mask]").inputmask();

			$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass: 'iradio_minimal-blue'
			});
		});


		
	</script>
	@append