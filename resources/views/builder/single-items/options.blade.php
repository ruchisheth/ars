@if($type=='radio')
	<li>
		<span class="handle ui-sortable-handle">
			<i class="fa fa-ellipsis-v"></i>
			<i class="fa fa-ellipsis-v"></i>
		</span>
		<input type="text" class="form-control option-label" placeholder="Label" value="'+option_label_value+'" name="'+option_label_name+'">
		<input type="text" class="form-control option-value" placeholder="Value" value="'+option_value+'" name="'+option_value_name+'">
		<button class="btn btn btn-box-tool pull-right" onclick="RemoveOption(this,event)"><i class="fa fa-times"></i></button>
	</li>