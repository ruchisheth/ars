{{ Form::open([
  'method'=>'post',
  'enctype'  =>  'multipart/form-data',
  'url' => route('store.fieldrep_otherdetails'),
  ]) 
}}

{{  Form::hidden('id',@$fieldrep->id)  }}
{{  Form::hidden('type','fieldrep')  }}

<div class="box collapsed-box">
  <div class="box-header with-border">
    <h6 class="box-title">
      Other Details
    </h6>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
    </div>
  </div>

  <div class="box-body" style="display: none;">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('highest_edu', 'Highest Education Level') }}
          {{  Form::select('highest_edu',
            @$highest_edu,
            (@$fieldrep->highest_edu) ? @$fieldrep->highest_edu : '',
            [
            'id' => 'highest_edu',
            'class' => 'form-control',
            ])
          }}

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('internet_browser', 'What type of internet browser do you use?') }}
          {{  Form::select('internet_browser',
            @$internet_browser,
            (@$fieldrep->internet_browser) ? @$fieldrep->internet_browser : '',
            [
            'id' => 'internet_browser',
            'class' => 'form-control',
            ])
          }}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('distance_willing_to_travel', 'How many miles are you willing to travel?') }}
          {{  Form::select('distance_willing_to_travel',
            @$distance_willing_to_travel,
            (@$fieldrep->distance_willing_to_travel) ? @$fieldrep->distance_willing_to_travel : '',
            [
            'id' => 'distance_willing_to_travel',
            'class' => 'form-control',
            ])
          }}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('is_employed', 'Are you currently employed?',
            [
            'class' => 'rb_label',
            ]) }}
            <label>                
             {{ Form::radio('is_employed', '1', (@$fieldrep->is_employed == '1' ? true : false),['class'=>'minimal custom_radio','id'=>'is_employed_yes']) }}
             <span class="rb_span">Yes</span>
           </label>
           <label>
             {{ Form::radio('is_employed', '2', (@$fieldrep->is_employed == '2' ? true : false),['class'=>'minimal custom_radio']) }}
             <span class="rb_span">No</span>
           </label>
         </div>
       </div>
     </div>
     <div class="row" id="occupations">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('occupationn', 'If Yes,Please Indicate Your Occupation') }}
          {{  Form::text('occupation',@$fieldrep->occupation,
            [
            'id' => 'occupation',
            'class' => 'form-control',            
            ])
          }}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('as_merchandiser', 'Have you ever worked as a merchandiser before?',
            [
            'class' => 'rb_label',
            ]) }}
            <label>

              {{ Form::radio('as_merchandiser', '1', (@$fieldrep->as_merchandiser == '1' ? true : false),['class'=>'minimal custom_radio','id'=>'as_merchndiser_yes']) }}
              <span class="rb_span">Yes</span>
            </label>
            <label>

             {{ Form::radio('as_merchandiser', '2', (@$fieldrep->as_merchandiser == '2' ? true : false),['class'=>'minimal custom_radio']) }}
             <span class="rb_span">No</span>
           </label>
         </div>
       </div>
     </div>
     <div class="row" id="experience">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('merchandiser_exp', '   If yes, please tell us about your experience') }}
          {{  Form::textarea('merchandiser_exp',@$fieldrep->merchandiser_exp,
            [
            'id' => 'merchandiser_exp',
            'class' => 'form-control',
            'rows' => '3,'
            ])
          }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>
            <!--<input type="checkbox" name="can_print" class="minimal custom_radio" >-->
            {{  Form::checkbox(
              'can_print',1,
              (@$fieldrep->can_print == 1) ? true : false,
              [
              'class' => 'minimal custom_radio',
              ])
            }}
            <span class="chk_label">
              Able to print from your computer?
            </span>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>
            <!--<input type="checkbox" name="has_camera" class="minimal custom_radio" >-->
            {{  Form::checkbox(
              'has_camera',1,
              (@$fieldrep->has_camera == 1) ? true : false,
              [
              'class' => 'minimal custom_radio',
              ])
            }}
            <span class="chk_label">
              Own a digital camera?
            </span>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>
            <!--<input type="checkbox" name="has_computer" class="minimal custom_radio" >-->
            {{  Form::checkbox(
              'has_computer',1,
              (@$fieldrep->has_computer == 1) ? true : false,
              [
              'class' => 'minimal custom_radio',
              ])
            }}
            <span class="chk_label">
              Own a computer?
            </span>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>
            <!--<input type="checkbox" name="has_smartphone" class="minimal custom_radio" >-->
            {{  Form::checkbox(
              'has_smartphone',1,
              (@$fieldrep->has_smartphone == 1) ? true : false,
              [
              'class' => 'minimal custom_radio',
              ])
            }}
            <span class="chk_label">
              Own a smart phone?
            </span>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>
            {{  Form::checkbox(
              'has_internet',1,
              (@$fieldrep->has_internet == 1) ? true : false,
              [
              'class' => 'minimal custom_radio',
              ])
            }}
            <span class="chk_label">
              Have 24/7 internet access?
            </span>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {{  Form::label('experience', 
            'Please give your years of experience and also whether it was in grocery, drug, department stores, mass merchandisers or home improvement.'
            ) }}
          {{  Form::textarea('experience',@$fieldrep->experience,
            [
            'id' => 'experience',
            'class' => 'form-control',
            'rows' => '3,'
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
          'class' => 'btn btn-primary pull-right',
          (@$fieldrep->id) ?  "" : 'disabled'
          ])

        }}

      </div>
      <div class="col-md-1 pull-right">
        <a href="{{ route('show.fieldreps.get') }}" id="cancel" class="btn btn-default pull-right">Cancel</a>
      </div>

    </div>                                                                                                    
  </div>
</div>
{{ Form::close() }}

@section('custom-script')
<script type="text/javascript">
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

    $('#as_merchndiser_yes').on('ifChanged ifCreated', function(event){
      var checked = event.currentTarget.checked;
      if(checked){
        $('#experience').slideDown('slow');
        $('#merchandiser_exp').attr('disabled', false);
      }else{
        $('#experience').slideUp('slow');
        $('#merchandiser_exp').attr('disabled', true);
      }
    });
  });
</script>
@append