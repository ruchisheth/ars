@if($type=='input')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="text-input" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>

	{!! Form::label('name','',['class'=>'label label-primary que_no']) !!}
	{!! Form::label('name','Label',['class'=>'control-label label_text']) !!}
	{!! Form::text('name','',['class'=>'form-control builder-control', 'data-input_type' => 'text']) !!}
</div>
@elseif($type=='date')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="date-input" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>

	{!! Form::label('name','',['class'=>'label label-primary que_no']) !!}
	{!! Form::label('name','Label',['class'=>'control-label label_text']) !!}
	<div class="input-group date">
		<div class="input-group-addon">
			<i class="fa fa-calendar"></i>
		</div>
		{!! Form::text('name','',['class'=>'form-control builder-control date-input','data-input_type' => 'date']) !!}
		{{-- <input class="form-control pull-right" id="datepicker" type="text"> --}}
	</div>
</div>
@elseif($type=='textarea')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="textarea" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	{!! Form::label('name','',['class'=>'label label-primary  que_no']) !!}
	{!! Form::label('name','Label',['class'=>'control-label label_text']) !!}
	{!! Form::textarea('name','',['rows'=>'3','class'=>'form-control builder-control','data-input_type' => 'textarea']) !!}
</div>
@elseif($type=='file')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="file" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	<div class="custom-file-input custom-size input-holder">
		{!! Form::label('name','',['class'=>'label label-primary  que_no']) !!}
		{!! Form::label('name','File',['class'=>'control-label label_text']) !!}
		{{-- {!! Form::file('name',['accept'=>'.jpg,.gif,.png,.jpeg','class'=>'file-input builder-control', 'multiple' => true,'data-input_type' => 'file']) !!} --}}
		{!! Form::file('name',['class'=>'file-input builder-control', 'data-input_type' => 'file', 'data-accept' => '.jpg, .jpeg, .png, .txt, .pdf']) !!}
	</div>
	<small>Only jpg, .jpeg, .png, .txt, .pdf files are allowed</small>
	{{-- <small></small> --}}
</div>
@elseif($type=='checkbox')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="checkbox" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	{!!  Form::label('name','',['class'=>'label label-primary  que_no']) !!}
	<div class="custom_chk_div">
	{!!  Form::checkbox('name',1,false,['class'=>  'minimal builder-control','data-input_type' => 'checkbox']) !!}
	{!!  Form::label('name','Label',['class'=>'control-label chk_label label_text']) !!}
	</div>
</div>
@elseif($type=='checkbox-group')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="AddOption(this, 'checkbox')" data-type="checkbox" class="builder-action"><i class="fa fa-plus"></i></a>
		<a href="javascript:;" onclick="EditInput(this)" data-type="checkbox-group" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	{!!  Form::label('name','',['class'=>'label label-primary  que_no']) !!}
	{!!  Form::label('name', 'Label',['class' => 'control-label rb_label label_text']) !!}
	<div class="options row margin-0">
		<label class="col-md-6">
			{{ Form::checkbox('name', 'option_1', false,['class'=>'minimal custom_radio builder-control group checkbox-group','data-input_type' => 'checkbox-group']) }}
			<span class="rb_span">Option_1</span>
		</label>
		<label class="col-md-6">  
			{{ Form::checkbox('name', 'option_2', false,['class'=>'minimal custom_radio  builder-control group checkbox-group', 'data-input_type' => 'checkbox-group']) }}
			<span class="rb_span">Option_2</span>
		</label>
	</div>
</div>
@elseif($type=='radio')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="AddOption(this, 'radio')" data-type="radio" class="builder-action"><i class="fa fa-plus"></i></a>
		<a href="javascript:;" onclick="EditInput(this)" data-type="radio-group" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	{!!  Form::label('name','',['class'=>'label label-primary  que_no']) !!}
	{!!  Form::label('name', 'Label',['class' => 'control-label rb_label label_text']) !!}
	<div class="options row margin-0">
		<label class="col-md-6">
			{{ Form::radio('name', 'Yes', false,['class'=>'minimal custom_radio builder-control group radio-group','data-input_type' => 'radio']) }}
			<span class="rb_span">Yes</span>
		</label>
		<label class="col-md-6">  
			{{ Form::radio('name', 'No', false,['class'=>'minimal custom_radio  builder-control group radio-group','data-input_type' => 'radio']) }}
			<span class="rb_span">No</span>
		</label>
	</div>
</div>
@elseif($type=='select')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="AddOption(this, 'select')" data-type="select" class="builder-action"><i class="fa fa-plus"></i></a>
		<a href="javascript:;" onclick="EditInput(this)" data-type="select" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	{!! Form::label('name','',['class'=>'label label-primary  que_no']) !!}
	{!! Form::label('name','Label',['class'=>'control-label label_text']) !!}
	{{  Form::select('name',
		[
		'' => 'Select Option',
		'1' => 'Option 1',
		'2' => 'Option 2',
		],'',
		[
			'class' => 'form-control builder-control',
			'data-input_type' => 'select'
		])
	}}
</div>
@elseif($type=='header')
<div class="builder-input form-group">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="header" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" data-type="header" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>
	<h3 class="builder_control" data-input_type="header"><label class="control-label label_text">Header</label></h3>
</div>
@elseif($type=='service_code')
<div class="builder-input form-group question-builder">
	<div class="field-actions pull-right">
		<a href="javascript:;" onclick="EditInput(this)" data-type="text-input" class="builder-action"><i class="fa fa-pencil"></i></a>
		<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>
		<span class="builder-action handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
	</div>

	{!! Form::label('name','',['class'=>'label label-primary que_no']) !!}
	{!! Form::label('service_code','Service Code',['class'=>'control-label label_text']) !!}
	{!! Form::text('service_code','',['class'=>'form-control builder-control', 'data-input_type' => 'text', 'data-fix_name' => 'service_code' ]) !!}
</div>
@endif

