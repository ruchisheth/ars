<div class="row">
	<div class="col-md-4">
		<div class="box-header with-border custom-header custom-header-title">
			<h6 class="box-title">
				<small>Primary Address</small>
			</h6>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('address1', 'Address1',['class' => 'mandatory'])}}
			{{  Form::text('address1','',
				[
				'id' => 'address1',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('address2', 'Address2')}}
			{{  Form::text('address2','',
				[
				'id' => 'address2',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('city', 'City',['class' => 'mandatory'])}}
			{{  Form::text('city','',
				[
				'id' => 'city',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			{{  Form::label('state', 'State',['class' => 'mandatory']) }}
			{{  Form::select('state', @$states,'',
			[
			'id' => 'state',
			'class' => 'form-control',
			])
		}}
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		{{  Form::label('zipcode', 'Zip Code',['class' => 'mandatory'])}}
		{{  Form::text('zipcode','',
			[
			'id' => 'zipcode',
			'class' => 'form-control',
			])
		}}
	</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('phone_number', 'Cell Phone Number', ['class' => 'mandatory']) }}
			{{  Form::text('phone_number','',
				[
				'id' => 'cell_number',
				'class' => 'form-control',
				'data-inputmask' => '"mask": "(999) 999-9999"',
				'data-mask' => '',
				])
			}}
		</div>
	</div>
</div><!-- row -->

<div class="row">
	<div class="col-md-4">
		<div class="box-header with-border custom-header custom-header-title">
			<h6 class="box-title">
				<small>Shipping Address</small>
			</h6>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('address1', 'Address1')}}
			{{  Form::text('s_address1','',
				[
				'id' => 'address1',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('address2', 'Address2')}}
			{{  Form::text('s_address2','',
				[
				'id' => 'address2',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('city', 'City')}}
			{{  Form::text('s_city','',
				[
				'id' => 'city',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			{{  Form::label('state', 'State') }}
			{{  Form::select('s_state', @$states,'',
			[
			'id' => 'state',
			'class' => 'form-control',
			])
		}}
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		{{  Form::label('zipcode', 'Zip Code')}}
		{{  Form::text('s_zipcode','',
			[
			'id' => 'zipcode',
			'class' => 'form-control',
			])
		}}
	</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('phone_number', 'Cell Phone Number') }}
			{{  Form::text('s_phone_number','',
				[
				'id' => 'cell_number',
				'class' => 'form-control',
				'data-inputmask' => '"mask": "(999) 999-9999"',
				'data-mask' => '',
				])
			}}
		</div>
	</div>
</div><!-- row -->