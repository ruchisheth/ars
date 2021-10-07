@extends('app')
@section('page-title') | {{  (@$user->id) ? 'User Edit' : 'User Add' }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
          'method'=>'post',
          'url' => route('store.user')]) }}

          {{  Form::hidden('id',@$user->id)  }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-user"></i>
              <h6 class="box-title">
                {{  (@$user->id) ? 'User Edit' : 'User Add' }}
              </h6>
            </div>

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
                    {{  Form::label('user_type', 'User Type',['class' => 'mandatory'])}}
                    {{  Form::select('user_type',
                      $user_type,'',
                      [
                      'id' => 'user_type',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('user_level', 'User Level',['class' => 'mandatory'])}}
                    {{  Form::select('role',
                      [
                      ''  =>  'Select User Type',
                      '1'  =>  'Super Admin',
                      '2'  =>  'Administrator'
                      ],'',
                      [
                      'id' => 'user_level',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('user_name', 'User Name',['class' => 'mandatory'])}}
                    {{  Form::text(
                      'user_name','',
                      [
                      'id' => 'user_name',
                      'class' => 'form-control',              
                      ])
                    }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('user_email', 'User Email',['class' => 'mandatory'])}}
                    {{  Form::text(
                      'user_email','',
                      [
                      'id' => 'user_email',
                      'class' => 'form-control',              
                      ])
                    }}
                  </div>
                </div>
              </div>

              <div class="row">  
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('password', 'Password',['class' => 'mandatory'])}}
                    {{  Form::password('password',
                      [
                      'id' => 'password',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('password_confirmation', 'Confirm Password',['class' => 'mandatory'])}}
                    {{  Form::password('password_confirmation',
                      [
                      'id' => 'password_confirmation',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>

              @if(@$user->id)
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('status', 'Status') }}
                    {{  Form::select('status', array(
                      '1'  => 'Active',
                      '0'  => 'Inactive',
                      
                      ), @$client->status,
                    [
                    'id' => 'status',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
            </div>
            @endif
          </div><!-- /.box-body -->
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
            @if(@$user->id != '')
            <h6><small>Created {{@$user->created }} | Last modified {{ @$user->updated }} </small></h6>
            @endif
          </div>
          {{ Form::close() }}
        </div><!-- /.box -->


      </div>
      



    </div>
  </section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {
    $(document).on('change','#user_type',function(){
      user_type = $(this).val();
      var user_level = $('#user_level');
      user_level.empty();
      if(user_type == ''){
        return;
      }
      $.get("{{ url('api/dropdown/user_level')}}", 
        { option: $(this).val() }, 
        function(data) {
          if(data.length == 0){
            user_level.attr('disabled', true);
            user_level.append("<option value=''>User Level Not Available</option>");
          }
          else{
            user_level.attr('disabled', false);
            user_level.append("<option value=''>Select User Level</option>");
            $.each(data, function(index, element) {
              user_level.append("<option value='"+ element.id +"'>" + element.role + "</option>");
            });
            
          }
        });
    });
  });

</script>

@append