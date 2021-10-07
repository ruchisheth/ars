<div class="modal fade" id="listitemModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">{{ ucwords(str_replace('_', ' ', @$lists->list_name)) }}</h4>
      </div>
      <div class="modal-body">
        <div class="nav-tabs-custom">

         {{ Form::open(array('method'=>'post',
           'id'  =>  'list_item_form',
           'url' => route('create.lists_item'),
           'class'  =>  'form-inline',
           'enctype'  =>  "multipart/form-data")) }}
           
           {{  Form::hidden('list_name',@$lists->list_name)  }}
           {{  Form::hidden('id',@$list_type->id)  }}

           <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert" style="display: none"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group f_w">
                  <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Item Name" autofocus="true">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-flat" id="save_listitem" name="save_listitem">Save</button>
                  </span>
                </div>
              </div>
           </div>
         </div>
         {{ Form::close() }}
         <div class="box-body">
          <ul class="todo-list">
            @foreach($list_types as $list_type)   
            @include('common.list_items_li',['data'=>$list_type])   
            @endforeach
          </ul>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
