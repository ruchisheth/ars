{{ Form::open(array('method'=>'post',
  'enctype'  =>  'multipart/form-data',
  'url' => route('store.store_availability'), 
  )) }}
  {{  Form::hidden('id',@$fieldrep->id)  }}
  {{  Form::hidden('type','admin')  }}
  <div class="box collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title">
        General Availability To Perform Jobs
        <small>(check all that apply)</small>
      </h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>

      </div>
      <div class="col-md-6" style="float:right">
        <div class="alert" style="display: none"></div>
      </div>
    </div>


    <div class="box-body" >
     <div class="table-responsive">
      <table id="round-grid" class="table">
        <thead>
          <tr>
            <th>Days</th>
            <th>Mornings</th>
            <th>Afternoons</th>
            <th>Evenings</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Monday</td>
            <td>
              <label>
                <!--<input type="checkbox" class="minimal" name="availability_monday[]">-->
                {{  Form::checkbox(
                  'availability_monday[a]',1,
                  (@$fieldrep->availability_monday['a'] == 1) ? true : false,
                  [
                  'class' => 'minimal ',
                  ])
                }}

              </label>
            </td>
            <td>
              <label>

               {{  Form::checkbox(
                 'availability_monday[b]',1,
                 (@$fieldrep->availability_monday['b'] == 1) ? true : false,
                 [
                 'class' => 'minimal ',
                 ])
               }}
             </label>
           </td>
           <td>
            <label>
              {{  Form::checkbox(
                'availability_monday[c]',1,
                (@$fieldrep->availability_monday['c'] == 1) ? true : false,
                [
                'class' => 'minimal ',
                ])
              }}
            </label>
          </td>
        </tr>
        <tr>
          <td>Tuesday</td>
          <td>
            <label>

             {{  Form::checkbox(
               'availability_tuesday[a]',1,
               (@$fieldrep->availability_tuesday['a'] == 1) ? true : false,
               [
               'class' => 'minimal ',
               ])
             }}
           </label>
         </td>
         <td>
          <label>
           {{  Form::checkbox(
             'availability_tuesday[b]',1,
             (@$fieldrep->availability_tuesday['b'] == 1) ? true : false,
             [
             'class' => 'minimal ',
             ])
           }}
         </label>
       </td>
       <td>
        <label>
         {{  Form::checkbox(
           'availability_tuesday[c]',1,
           (@$fieldrep->availability_tuesday['c'] == 1) ? true : false,
           [
           'class' => 'minimal ',
           ])
         }}
       </label>
     </td>
   </tr>
   <tr>
    <td>Wednesday</td>
    <td>
      <label>
       {{  Form::checkbox(
         'availability_wednesday[a]',1,
         (@$fieldrep->availability_wednesday['a'] == 1) ? true : false,
         [
         'class' => 'minimal ',
         ])
       }}
     </label>
   </td>
   <td>
    <label>
      {{  Form::checkbox(
        'availability_wednesday[b]',1,
        (@$fieldrep->availability_wednesday['b'] == 1) ? true : false,
        [
        'class' => 'minimal ',
        ])
      }}
    </label>
  </td>
  <td>
    <label>
      {{  Form::checkbox(
        'availability_wednesday[c]',1,
        (@$fieldrep->availability_wednesday['c'] == 1) ? true : false,
        [
        'class' => 'minimal ',
        ])
      }}
    </label>
  </td>
</tr>
<tr>
  <td>Thursday</td>
  <td>
    <label>
      {{  Form::checkbox(
        'availability_thursday[a]',1,
        (@$fieldrep->availability_thursday['a'] == 1) ? true : false,
        [
        'class' => 'minimal ',
        ])
      }}
    </label>
  </td>
  <td>
    <label>
     {{  Form::checkbox(
       'availability_thursday[b]',1,
       (@$fieldrep->availability_thursday['b'] == 1) ? true : false,
       [
       'class' => 'minimal ',
       ])
     }}
   </label>
 </td>
 <td>
  <label>
    {{  Form::checkbox(
      'availability_thursday[c]',1,
      (@$fieldrep->availability_thursday['c'] == 1) ? true : false,
      [
      'class' => 'minimal ',
      ])
    }}
  </label>
</td>
</tr>
<tr>
  <td>Friday</td>
  <td>
    <label>

     {{  Form::checkbox(
       'availability_friday[a]',1,
       (@$fieldrep->availability_friday['a'] == 1) ? true : false,
       [
       'class' => 'minimal ',
       ])
     }}
   </label>
 </td>
 <td>
  <label>
   {{  Form::checkbox(
     'availability_friday[b]',1,
     (@$fieldrep->availability_friday['b'] == 1) ? true : false,
     [
     'class' => 'minimal ',
     ])
   }}
 </label>
</td>
<td>
  <label>
    {{  Form::checkbox(
      'availability_friday[c]',1,
      (@$fieldrep->availability_friday['c'] == 1) ? true : false,
      [
      'class' => 'minimal ',
      ])
    }}
  </label>
</td>
</tr>
<tr>
  <td>Saturday</td>
  <td>
    <label>

      {{  Form::checkbox(
        'availability_saturday[a]',1,
        (@$fieldrep->availability_saturday['a'] == 1) ? true : false,
        [
        'class' => 'minimal ',
        ])
      }}
    </label>
  </td>
  <td>
    <label>
     {{  Form::checkbox(
       'availability_saturday[b]',1,
       (@$fieldrep->availability_saturday['b'] == 1) ? true : false,
       [
       'class' => 'minimal ',
       ])
     }}
   </label>
 </td>
 <td>
  <label>
    {{  Form::checkbox(
      'availability_saturday[c]',1,
      (@$fieldrep->availability_saturday['c'] == 1) ? true : false,
      [
      'class' => 'minimal ',
      ])
    }}
  </label>
</td>
</tr>
<tr>
  <td>Sunday</td>
  <td>
    <label>

      {{  Form::checkbox(
        'availability_sunday[a]',1,
        (@$fieldrep->availability_sunday['a'] == 1) ? true : false,
        [
        'class' => 'minimal ',
        ])
      }}
    </label>
  </td>
  <td>
    <label>

     {{  Form::checkbox(
       'availability_sunday[b]',1,
       (@$fieldrep->availability_sunday['b'] == 1) ? true : false,
       [
       'class' => 'minimal ',
       ])
     }}
   </label>
 </td>
 <td>
  <label>
    {{  Form::checkbox(
      'availability_sunday[c]',1,
      (@$fieldrep->availability_sunday['c'] == 1) ? true : false,
      [
      'class' => 'minimal ',
      ])
    }}
  </label>
</td>
</tr>
</tbody>
</table>
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
</div><!-- /.box -->
{{ Form::close() }}