@extends('.app')
@section('page-title') | Export @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        {{ Form::open(
          [
          'method'=>'post',
          'url' => route('export.survey.data')
          ]) }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-star-half-o"></i>
              <h6 class="box-title text-muted">
               Export Survey
             </h6>
           </div><!-- /.box header -->
           <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                @include('includes.success')
                @include('includes.errors')
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('client', 'Select Client',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'client_id',@$client_list,'',
                    [
                    'class' =>  'form-control',
                    'id'  => 'clients',
                    ])
                  }}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('project', 'Select Project',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'project_id',['' => 'Select Project'],'',
                    [
                    'class' =>  'form-control',
                    'id'    =>  'projects',
                    ])
                  }}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('round', 'Select Round',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'round_id',['' => 'Select Round'],'',
                    [
                    'class' =>  'form-control',
                    'id'    =>  'rounds',
                    ])
                  }}
                </div>
              </div>
            </div>
            {{-- <div class="row"></div>
            <div class="row"></div> --}}
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  {{  Form::label('questions', 'Select Question',['class'=>'mandatory']) }}
                  {{  Form::select(
                    'question_id[]',
                    ['' => 'Select Question'],'',
                    [
                    'class' =>  'form-control custom-select-control',
                    'id'    =>  'questions',
                    'multiple' => 'multiple'
                    ])
                  }}
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  {{  Form::label('approved_survey', 'Filter Survey', ['class' => ' row col-xs-12']) }}
                  <label>
                    {{ Form::checkbox('approved_survey', '1', false,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">Approved Surveys Only</span>
                  </label>
                  <label>
                    {{ Form::checkbox('unexported_survey', '1', false,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">Unexported Surveys Only</span>
                  </label>
                </div>


                {{-- <div class="form-group">
                  {{  Form::label('approved_survey', 'Filter Survey', ['class' => ' row col-xs-12']) }}
                  <label>
                    {{ Form::radio('approved_survey', '1', true,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">All Survey</span>
                  </label>
                  <label>
                    {{ Form::radio('approved_survey', '0', false,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">Approved Surveys Only</span>
                  </label>
                </div>
                <div class="form-group">
                  <label>
                    {{ Form::radio('all_survey', '1', true,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">All Survey</span>
                  </label>
                  <label>
                    {{ Form::radio('all_survey', '0', false,
                      ['class'=>'minimal custom_radio']) 
                    }}
                    <span class="rb_span">Unexported Surveys Only</span>
                  </label>
                </div> --}}
              </div>
            </div><!-- 1st row over -->
          </div>
          <div class="box-footer">
            <div class="pull-right">
              <div class="pull-right">
                {{  Form::submit('Export',
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
          </div><!-- /.box -->
          {{  Form::close() }}
        </div><!-- main col-md-6 -->
      </div>
    </section>
  </div>
  @stop

  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {

      // var client = $("#clients").val();
      // console.log(client );
      // if(client != ""){
      //   alert('test');
      //   $("#clients").trigger('change');
      // }

      $("#projects").depdrop({
        url: "{{ url('api/dropdown/projects')}}",
        depends: ['clients'],
        initDepends: ['clients'],
      });

      $("#rounds").depdrop({
        url: "{{ url('api/dropdown/rounds')}}",
        depends: ['projects'],
        initDepends: ['projects']
      });

      $("#questions").depdrop({
        url: "{{ url('api/dropdown/questions')}}",
        depends: ['rounds'],
        initDepends: ['rounds']
      });

    // $(document).on('change','#clients',function(){
    //   client = $(this).val();
    //   var projects = $('#projects');
    //   var rounds = $('#rounds');
    //   var questions = $('#questions');
    //   projects.empty();
    //   rounds.empty();
    //   questions.empty();
    //   if(client == ''){
    //     return;
    //   }

    //   $.get("{{-- url('api/dropdown/projects')--}}", 
    //     { option: $(this).val() }, 
    //     function(data) {
    //       if(data.length == 0){
    //         projects.append("<option value=''>No Project</option>");
    //       }
    //       else{
    //         projects.append("<option value=''>Select Project</option>");
    //         $.each(data, function(index, element) {
    //           projects.append("<option value='"+ element.id +"'>" + element.project_name + "</option>");
    //         });

    //       }
    //     });
    // });

    // $(document).on('change','#projects',function(){
    //   project = $(this).val();
    //   var rounds = $('#rounds');
    //   rounds.empty();
    //   if(project == ''){
    //     return;
    //   }
    //   $.get("{{-- url('api/dropdown/rounds')--}}", 
    //     { option: $(this).val() }, 
    //     function(data) {
    //       if(data.length == 0){
    //         rounds.append("<option value=''>No Rounds</option>");
    //       }
    //       else{
    //         rounds.append("<option value=''>Select Round</option>");
    //         $.each(data, function(index, element) {
    //           rounds.append("<option value='"+ element.id +"'>" + element.round_name + "</option>");
    //         });

    //       }
    //     });
    // });
  });/* . dccument ready over*/

</script>

@append