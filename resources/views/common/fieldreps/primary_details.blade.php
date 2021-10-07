{{ Form::open(['method'=>'post', 'enctype'  =>  'multipart/form-data', 'url' => route('store.fieldrep') ]) }}
{{  Form::hidden('id',@$fieldrep->id)  }}
{{  Form::hidden('url',URL::previous())  }}
{{  Form::hidden('type','admin')  }}
<div class="box">
  <div class="box-header with-border">
    @if(Auth::user()->hasrole('admin'))
    <i class="fa fa-group"></i>
    <h6 class="box-title">
      {{  (@$fieldrep->id) ? 'Field Rep Edit' : 'Field Rep Add' }}
    </h6>
    @elseif(Auth::user()->hasrole('fieldrep'))
    <i class="fa fa-user"></i>
    <h6 class="box-title">Profile Edit</h6>
    @endif
  </div>

  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        @include('includes.errors')
      </div>
    </div>
    <div class="row">  
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('fieldrep_code', 'FieldRep Code')}}
          {{  Form::text('fieldrep_code', @$fieldrep->fieldrep_code != 0 || @$fieldrep->fieldrep_code != null ? @$fieldrep->fieldrep_code : '',
            [
            'id' => 'fieldrep_code',
            'class' => 'form-control',
            'placeholder' => 'FieldRep Code',
            'autofocus' => true,
            ])
          }}                    
        </div>
      </div>
    </div>

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

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">

          {{  Form::label('organization_name', 'Organization Name') }}
          {{  Form::select(
            'organization_name', 
            @$organizations,
            (@$fieldrep->organization_name) ? @$fieldrep->organization_name : '',
            [
            'id' => 'organization_name',
            'class' => 'form-control',
            ])
          }}
        </div>
      </div>
    </div>

    @if(!@$fieldrep->id)
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('password', 'Password',['class'=>'mandatory']) }}
          {{  Form::password('password', 
            [
            'class' => 'form-control',
            ])
          }}                  
        </div>                                    
      </div>
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('password_confirmation', 'Confirm Password',['class'=>'mandatory']) }}
          {{  Form::password('password_confirmation', 
            [
            'class' => 'form-control',
            ])
          }}                  
        </div>                                    
      </div>
    </div>           
    @endif
    @if(Auth::user()->hasrole('admin'))
    <div class="row">
      @if(!@$fieldrep->is_pending)
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('approved_for_work', 'This Rep is',
            [
            'class' => 'rb_label',
            ]) 
          }}
          <label>
            {{ Form::radio('approved_for_work', '1', (@$fieldrep->approved_for_work == '1') ? true : false,
              [
              'id'    =>  'is_approve_yes',
              'class' =>  'minimal custom_radio',
              (!@$fieldrep || @$fieldrep->approved_for_work == '1') ? 'checked' : ''
              ]) 
            }}
            <span class="rb_span">Approved For Work
            </span>
          </label>
          <label>  
            {{ Form::radio('approved_for_work', '0', (@$fieldrep->approved_for_work == '0') ? true : false,
              [
                'id'    =>  'is_approve_no',
                'class' =>  'minimal custom_radio',
                (@$fieldrep->approved_for_work == '0') ? 'checked' : false
              ])
            }}
            <span class="rb_span">
              Not Approved For Work
            </span>
          </label>
        </div>
      </div>
      @endif
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('classification', 'Classification',
            [
            'class' => 'rb_label',
            ]) }}
            <label>
             {{ Form::radio('classification', '1',  (@$fieldrep->classification == '1' ? true : false),
               [
               'class'=>'minimal custom_radio',
               'id' => "IC",
               (!@$fieldrep || @$fieldrep->classification == '1') ? 'checked' : ''
               ]) }}
               <span class="rb_span">
                Independent Contractor
              </span>
            </label>
            <label>
             {{ Form::radio('classification', '2',  (@$fieldrep->classification == '2' ? true : false),
              [
              'class'=>'minimal custom_radio',
              'id'  => 'employee',
              ]) }}
              <span class="rb_span">
                Employee
              </span>
            </label>
          </div>
        </div>
      </div>

      <div class="row">
        @if(!@$fieldrep->is_pending)
        <div class="col-md-6">
          <div class="form-group">
            {{  Form::label('initial_status', 'Initial Status') }}
            {{  Form::select('initial_status',
              [
              '1'  => 'Active',
              '0'  => 'Inactive',
              '2'  => 'Hold',
              '3'  => 'Terminated'
              ], 
              @$fieldrep->initial_status, 
              [
              'id' => 'initial_status',
              'class' => 'form-control',
              ])
            }}
          </div>
        </div>
        @endif
        <div class="col-md-6">
          <div class="form-group">
            <label class="{{ (@$fieldrep->is_pending) ?:'inline_chk'  }}">
              {{  Form::checkbox(
                'paperwork_received',1,
                (@$fieldrep->paperwork_received == 1) ? true : false,
                [
                'class' => 'minimal custom_radio',
                ])
              }}
              <span class="chk_label">
                Paperwork Received
              </span>
            </label>
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
            'class' => 'btn btn-primary pull-right',

            ])
          }}

        </div>
        <div class="col-md-1 pull-right">
          <a href="{{ route('show.fieldreps.get') }}" id="cancel" class="btn btn-default pull-right">Cancel</a>
        </div>
      </div>                  
      @if(@$fieldrep->id != '')
      <h6><small>Created {{ @$fieldrep->created }} | Last modified {{ @$fieldrep->updated }} </small></h6>
      @endif    
    </div><!-- /.box-footer -->
  </div>{{-- /. boxeee --}}
  {{ Form::close() }}

  @section('custom-script')
  <script type="text/javascript">
    var initial_status = $('#initial_status').val();
    $(document).ready(function () {

      $('#is_employed_yes').on('ifChanged ifCreated', function(event){
        var checked = event.currentTarget.checked;
        if(checked){
          $('#occupations').slideDown('slow');
          $('#occupation').attr('disabled', false);
        }else{
          $('#occupations').slideUp('slow');
          $('#occupation').attr('disabled', true);
        }
      });
      
      $(document).on('change', '#initial_status', function(e){
          
      
      
      if($(this).val() == 1){
          $('#is_approve_yes').iCheck('check');
      }else{
          $('#is_approve_no').iCheck('check');
      }
      });

      $('#is_approve_no').on('ifChanged ifCreated', function(event){
        var checked = event.currentTarget.checked;
        if(checked){
          if(initial_status){
            $('#initial_status').val('0');
          }
        }else{
          $('#initial_status').val(initial_status);
        }
      });
    });
  </script>
  @endsection