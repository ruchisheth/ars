@extends('app')
@section('page-title') | {{  (@$project->id) ? 'Project Edit' : 'Project Add' }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          array(
            'url'  => route('store.project'),
            'method'  => 'post',
            'enctype'  =>  "multipart/form-data",
            )) 
          }}

          {{  Form::hidden('project_id',@$project->id) }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-th"></i>
              <h6 class="box-title">
                {{  (@$project->id) ? 'Project Edit' : 'Project Add' }}
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
                    {{  Form::label('project_code', 'Project Code')}}
                    {{  Form::text(
                      'project_code', (@$project->id) ?  @$project->id : @$project_id,
                      [
                      'id' => 'project_code',
                      'readonly' => 'true',
                      'class' => 'form-control',
                      'placeholder' => 'Project Code',
                      ])
                    }}
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('chains', 'Select Chain',['class'=>'mandatory']) }}
                    {{  Form::select(
                      'chain_id',
                      @$chains,
                      (@$project->chain_id) ? @$project->chain_id : @$chain_id,
                      [
                      "id" => 'chain_id',
                      "class"=>'form-control',
                      'data-placeholder'=>"Select a Chain",
                      'onchange'=>'changeChain()',
                      ]) 
                    }}
                  </div>
                </div>
              </div> 

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('project_name', 'Project Name',['class'=>'mandatory'])}}
                    {{  Form::text(
                      'project_name', @$project->project_name,
                      [
                      'id' => 'project_name',
                      'class' => 'form-control',
                      'autofocus' => true,
                      ])
                    }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('project_type', 'Project Type',['class'=>'mandatory']) }}
                    {{  Form::select('project_type',
                      @$project_types, @$project->project_type,
                      [
                      'id' => 'project_type',
                      'class' => 'form-control',
                      ])
                    }}

                  </div>
                </div>
              </div>

              <div class="row">                    

                @if(@$project->id)                          
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('status', 'Status') }}
                    {{ Form::select('status', array(                   
                      '1'  => 'Active',
                      '0'  => 'Inactive',                            
                      ), @$project->status,
                    [
                    'id' => 'status',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>   
              @endif

            </div>


            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>
                    {{  Form::checkbox(
                      'can_schedule',1,
                      (@$project->can_schedule == 1) ? true : false,
                      [
                      'class' => 'minimal',
                      ])
                    }}
                    <span class="chk_label">
                      Rep. can Schedule?
                    </span>
                  </label>

                </div>
              </div>

            </div>
            <div class="box-header with-border custom-header">
              <h6 class="box-title">
                <small>CONTACTS</small>
              </h6>
            </div>
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('primary_contact', 'Primary Contact', ['class'=>'mandatory'])}}
                  {{  Form::select('primary_contact',
                    $contacts, @$project->primary_contact,
                    [
                    'id' => 'primary_contact',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('billing_contact', 'Billing Contact', ['class'=>'mandatory'])}}
                  {{  Form::select('billing_contact', $contacts, @$project->billing_contact,
                    [
                    'id' => 'billing_contact',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
            </div>
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
            </div>                                                                                           @if(@$project->id != '')
            <h6><small>Created {{ @$project->created }} | Last modified {{ @$project->updated }} </small></h6>
            @endif         
          </div><!-- /.box -->
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {

    var project_id = '{{ @$project->id }}';

    $(".select2").select2();

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    changeChain();
  });

  function changeChain(){
    var chain_id = $('select[name=chain_id]').val();
    if(chain_id == ""){
      $('#primary_contact, #billing_contact').find('option').remove();
      $('#primary_contact, #billing_contact').html("<option value=''>Select Contact</option>");
      $('#primary_contact, #billing_contact').attr('disabled', true);
      return;
    }
    var url = APP_URL + '/chain/' + chain_id + '/get-contact';
    $.ajax({
      type: 'POST',
      url: url,                  
      data:{'chain_id': chain_id},
      success: function (res) {
        $('#primary_contact, #billing_contact').find('option').remove();
        if(res.contacts != ''){
          $('#primary_contact, #billing_contact').html("<option value=''>Select Contact</option>");
          $.each(res.contacts, function(index, contacts){
            var HTML = "<option value='"+index+"'>"+contacts+"</option>";
            $('#primary_contact, #billing_contact').append(HTML);
          });
          $('#primary_contact, #billing_contact').attr('disabled', false);
          if( chain_id == '{{  (@$project->chain_id) }}'){
            $('#primary_contact').val('{{ @$project->primary_contact }}').change();
            $('#billing_contact').val('{{ @$project->billing_contact }}').change();
          }
        }
        else{
          var HTML = "<option value=''>No Contact</option>";
          $('#primary_contact, #billing_contact').html(HTML);
          $('#primary_contact, #billing_contact').val('').change();
          $('#primary_contact, #billing_contact').attr('disabled', true);
        }
      }
    });
  }

</script>

@append