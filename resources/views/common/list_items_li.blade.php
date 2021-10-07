<li id="list_order_{{$data->id}}" class="{{@$data->id}}">
  <!-- drag handle -->
  <span class="handle">
    <i class="fa fa-ellipsis-v"></i>
    <i class="fa fa-ellipsis-v"></i>
  </span>
  <span class="text">{{ $data->item_name  }}</span>
  <!-- Emphasis label -->
  
  <!-- General tools such as edit or delete-->
  <div class=" box-tools pull-right">
   <button id="create_listitem" class="btn btn-box-tool" type="button" data-id={{ $data->id  }}   onclick="SetTextEdit(this,event)" >
    <i class="fa fa-edit" ></i></button>
    @if(!$data->is_default)
    <button name="remove_listitem" class="btn btn-box-tool" type="button" data-id={{ $data->id  }} > 
      <i class="fa fa-trash-o"></i></button>
    </div>
    @endif
  </li>