    @if(@!$role->is_default)
    <li id="role_{{$role->id}}" class="{{@$role->id}}">
      <span class="handle">
        <i class="fa fa-ellipsis-v"></i>
        <i class="fa fa-ellipsis-v"></i>
      </span>
      <span class="text role_name">{{ $role->name  }}</span> 

      <div class=" box-tools pull-right">
        <a href="javascript:void(0)" class="btn btn-box-tool show-tooltip btn-action" onclick="SetRoleEdit(this,event)" data-id="{{ $role->id }}" title="Edit"><i class="fa fa-edit"></i></a>
        <button class="btn btn-box-tool show-tooltip btn-action" name="remove_role" data-toggle="modal" data-target="#delete_role_modal" data-action="{{  route('roles.destroy',  ['role' => $role->id]) }}" data-id="{{ $role->id }}" title="Delete"><i class="fa fa-trash"></i></a>
      </div>
    </li>
    @endif