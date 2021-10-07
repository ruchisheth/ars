@section('custome-style')

	{{ Html::style(env('ASSETS').'plugins/iCheck/all.css') }}

@stop

{{ Form::open(array('method'=>'post'
)) }}
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Assign Chain</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<select class="form-control select2" multiple="multiple" data-placeholder="Select a Chain" style="width: 100%;">
						@foreach($chains as $chain)
						<option>{{	$chain->chain_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="pull-right">
			{{  Form::submit('Save',
			[
			'id' => 'create',
			'class' => 'btn btn-primary pull-right'
			])
		}}

		</div>
		<div class="col-md-1 pull-right">
		{{  Form::submit('Cancel',
		[
		'id' => 'cancel',
		'class' => 'btn btn-default pull-right'
		])
		}}
		</div>

	</div>
</div> <!-- /.box -->
{{ Form::close() }}

@section('custom-script')

	{{ Html::script(env('ASSETS').'plugins/iCheck/icheck.min.js') }}

	<script type="text/javascript">
		$(document).ready(function(){
			$(".select2").select2();

			$('input[type="checkbox"].minimal').iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass: 'iradio_minimal-blue'
			});
		})
	</script>
@endsection