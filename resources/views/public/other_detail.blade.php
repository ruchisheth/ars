<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('highest_edu', 'Highest Education Level') }}
      {{  Form::select('highest_edu',
        @$highest_edu,
        '',
        [
        'id' => 'highest_edu',
        'class' => 'form-control',
        ])
      }}

    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('internet_browser', 'What type of internet browser do you use?') }}

      {{  Form::select('internet_browser',
        @$internet_browser,
        '',
        [
        'id' => 'internet_browser',
        'class' => 'form-control',
        ])
      }}
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('is_employed', 'Are you currently employed?',
        [
        'class' => 'rb_label',
        ]) 
      }}
      <label>                
       {{ Form::radio('is_employed', '1', false,['class'=>'minimal custom_radio','id'=>'is_employed_yes']) }}
       <span class="rb_span">Yes</span>
     </label>
     <label>
       {{ Form::radio('is_employed', '2', true,
        [
        'class'=>'minimal custom_radio',
        ]) }}
        <span class="rb_span">No</span>
      </label>
    </div>
    <div class="form-group" id="occupations" style="display: none">
      {{  Form::label('occupationn', 'If yes, please indicate your occupation') }}
      {{  Form::text('occupation','',
        [
        'id' => 'occupation',
        'class' => 'form-control',            
        ])
      }}
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('as_merchandiser', 'Have you ever worked as a merchandiser before?',
        [
        'class' => 'rb_label',
        ]) 
      }}
      <label>
        {{ Form::radio('as_merchandiser', '1', false,['class'=>'minimal custom_radio','id'=>'as_merchndiser_yes']) }}
        <span class="rb_span">Yes</span>
      </label>
      <label>
        {{ Form::radio('as_merchandiser', '2', true,
          [
          'class'=>'minimal custom_radio',
          ]) 
        }}
        <span class="rb_span">No</span>
      </label>
    </div>
    <div class="form-group" id="experience" style="display: none">
      {{  Form::label('merchandiser_exp', '   If yes, please tell us about your experience') }}
      {{  Form::textarea('merchandiser_exp','',
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
      {{  Form::label('distance_willing_to_travel', 'How many miles are you willing to travel?') }}

      {{  Form::select('distance_willing_to_travel',
        @$distance_willing_to_travel,
        '',
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
      <label>
        {{  Form::checkbox(
          'can_print',1,
          false,
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
        {{  Form::checkbox(
          'has_camera',1,
          false,
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
          false,
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
        {{  Form::checkbox(
          'has_smartphone',1,
          false,
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
          false,
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
      {{  Form::textarea('experience','',
        [
        'id' => 'experience',
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
      {{  Form::label('cities', 'List the 3 largest cities closest to you.') }}
      {{  Form::text('cities','',
        [
        'id' => 'cities',
        'class' => 'form-control',
        ])
      }}
    </div>
  </div>
</div>

