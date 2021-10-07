<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('first_name', 'First Name',['class'=>'mandatory']) }}
			{{  Form::text('first_name', @$fieldrep->first_name,
				[
				'id' => 'first_name',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{{  Form::label('last_name', 'Last Name',['class'=>'mandatory']) }}
			{{  Form::text('last_name', @$fieldrep->last_name,
				[
				'id' => 'last_name',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			{{  Form::label('email', 'Email',['class'=>'mandatory']) }}
			{{  Form::text('email', @$user->email,
				[
				'id' => 'email',
				'class' => 'form-control',
				])
			}}
		</div>
	</div>
</div>