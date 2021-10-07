@extends('app')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
       

       <!-- TO DO List -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Project Type List</h3>

                  <div class="box-tools pull-right">
                  
                  <!-- <button class="btn btn-default pull-right" id="add_item"><i class="fa fa-plus"></i> Add item</button> -->
     
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
               
                  <div class="row">
                    <div class="col-md-12">
                           {{ Form::open(array('method'=>'post',
                            'id'  =>  'retailer',
                            'action' => 'SettingController@store', 
                            'enctype'  =>  "multipart/form-data")) }}

                            {{  Form::hidden('list_name','retailer')  }}
                            <div class="form-group">
                              {{  Form::label('item_name', 'Project Type')}}
                              {{  Form::text(
                              'item_name',@$client->client_name,
                              [
                              'id' => 'item_name',
                              'class' => 'form-control',
                              
                              ])
                            }}
                    </div>
                      {{ Form::close() }}
                  </div>
                </div>
               
                  <ul class="todo-list">
                 {{--  @foreach($retailers as $retailer)   
                   @include('common.retailers',['data'=>$retailer])    
                  @endforeach  --}}
                  </ul>
                   <ul class="todo-list">
                    <li>
                      <!-- drag handle -->
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <!-- checkbox -->
                      <input type="checkbox" value="" name="">
                      <!-- todo text -->
                      <span class="text">Design a nice theme</span>
                      <!-- Emphasis label -->
                      <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                      <!-- General tools such as edit or delete-->
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Make the theme responsive</span>
                      <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Check your messages and notifications</span>
                      <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                  </ul>
                </div><!-- /.box-body -->
               
              </div><!-- /.box -->
</div><!-- /.box -->
</div>
</section>
</div>

@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {


          // var counter = 1;
          // $('#add_item').click(function (){

          //       var newAddItemdiv = $(document.createElement('div'))
          //       .attr("id", 'add-item' + counter);

          //       newAddItemdiv.after().html('<input type="text" class="form-control" name="item_name' + counter + 
          //        '" id="item_name' + counter + '" value="" >');
                  
          //       newAddItemdiv.appendTo("#add-item");


          // });

          $('.input').keypress(function (e) {
            if (e.which == 13) {
              $('form#retailer').submit();
             // $("li").clone().appendTo("ul");
              return false;    //<---- Add this line

                
            }
        });

          //  function getRetailer(element){
          //   var JSON = $(element).data('json'),
          //   Id = JSON.id,
          //   $.ajax({
          //   type: "POST",
          //   url: APP_URL+'/admin/get-subcats-view/'+Id,
          //   data: "",
          //   dataType: "json",
          //   success: function(res) {
             
          //     $('#SubCatsHolder').html(res.SubCategoryHtml);
          //      }
          //   });
          // }

        
      });/* . dccument ready over*/

 

</script>

@append