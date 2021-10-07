<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">
      FieldRep Criteria
      <div class="col-md-6" style="float:right">
        <div class="alert" style="display: none"></div>
      </div>
    </div>
    {{ Form::open(
      [
      'method'=>'post',
      'class'=>'form-horizontal']) }}
      <div class="box-body">
        {{-- <div class="btn-group custom-btn criteriaButton" data-toggle="buttons">
          @if($criteria->approved_for_work == 1)
          <label class="btn btn-default btn-app btn-active active">
            @else
            <label class="btn btn-default btn-app">
              @endif
              {{  Form::checkbox(
                'approved_for_work',1,
                (@$criteria->approved_for_work == 1) ? true : false)
              }}
              <i class="fa fa-check"></i>
              <div class="criteria_text">Approved</div>
            </label>
          </div> --}}

          <div class="btn-group custom-btn criteriaButton" data-toggle="buttons">
            @if($criteria->has_camera == 1)
            <label class="btn btn-default btn-app btn-active active">
              @else
              <label class="btn btn-default btn-app">
                @endif
                {{  Form::checkbox(
                  'has_camera',1,
                  (@$criteria->has_camera == 1) ? true : false)
                }}
                <i class="fa fa-camera"></i>
                <div class="criteria_text">Camera</div> 
              </label>
            </div>

            <div class="btn-group custom-btn criteriaButton" data-toggle="buttons">
              @if($criteria->has_internet == 1)
              <label class="btn btn-default btn-app btn-active active">
                @else
                <label class="btn btn-default btn-app">
                  @endif
                  {{  Form::checkbox(
                    'has_internet',1,
                    (@$criteria->has_internet == 1) ? true : false)
                  }}
                  <i class="fa fa-globe"></i>
                  Internet
                </label>
              </div>

              <div class="btn-group custom-btn criteriaButton" data-toggle="buttons">
                @if($criteria->exp_match_project_type == 1)
                <label class="btn btn-default btn-app btn-active active">
                  @else
                  <label class="btn btn-default btn-app">
                    @endif
                    {{  Form::checkbox(
                      'exp_match_project_type',1,
                      (@$criteria->exp_match_project_type == 1) ? true : false)
                    }} 
                    <i class="fa fa-certificate"></i>
                    Experience
                  </label>
                </div>

                <div class="btn-group custom-btn criteriaButton miles">
                  @if($criteria->distance != NULL && $criteria->distance != '')
                  <button type="button" class="btn btn-default distance-selected btn-active active btn-app" name="distance"><i class="fa fa-street-view"></i><span>{{ $criteria->distance }}</span></button>
                  <button type="button" class="btn btn-default dropdown-toggle btn-active acrive" data-toggle="dropdown">
                    @else
                    <button type="button" class="btn btn-default distance-selected btn-app" name="distance"><i class="fa fa-street-view"></i> <span>Distance</span></button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      @endif
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a data-miles='' href="javascript:void(0)">Distance</a></li>
                      <li><a data-miles='02' href="javascript:void(0)">02</a></li>                      
                      <li><a data-miles='05' href="javascript:void(0)">05</a></li>
                      <li><a data-miles='10' href="javascript:void(0)">10</a></li>
                      <li><a data-miles='15' href="javascript:void(0)">15</a></li>
                      <li><a data-miles='20' href="javascript:void(0)">20</a></li>
                      <li><a data-miles='50' href="javascript:void(0)">50</a></li>
                      <li><a data-miles='100' href="javascript:void(0)">100</a></li>
                      <li><a data-miles='250' href="javascript:void(0)">250</a></li>
                      <li><a data-miles='500' href="javascript:void(0)">500</a></li>
                    </ul>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <div class="btn-group custom-btn criteriaButton" data-toggle="buttons">
                        @if(str_contains($criteria->allowable_days,'sun'))
                        <label class="btn btn-default btn-lg btn-active active">
                          @else
                          <label class="btn btn-default btn-lg">
                            @endif
                            {{  Form::checkbox(
                              'allowable_days[]','sunday',
                              (@str_contains($criteria->allowable_days,'sunday')) ? true : false)
                            }}
                            Sun
                          </label>

                          @if(str_contains($criteria->allowable_days,'monday'))
                          <label class="btn btn-default btn-lg btn-active active">
                            @else
                            <label class="btn btn-default btn-lg">
                              @endif
                              {{  Form::checkbox(
                                'allowable_days[]','monday',
                                (@str_contains($criteria->allowable_days,'monday')) ? true : false)
                              }}Mon
                            </label>

                            @if(str_contains($criteria->allowable_days,'tuesday'))
                            <label class="btn btn-default btn-lg btn-active active">
                              @else
                              <label class="btn btn-default btn-lg">
                                @endif

                                {{  Form::checkbox(
                                  'allowable_days[]','tuesday',
                                  (@str_contains($criteria->allowable_days,'tuesday')) ? true : false)
                                }}Tue
                              </label>

                              @if(str_contains($criteria->allowable_days,'wednesday'))
                              <label class="btn btn-default btn-lg btn-active active">
                                @else
                                <label class="btn btn-default btn-lg">
                                  @endif

                                  {{  Form::checkbox(
                                    'allowable_days[]','wednesday',
                                    (@str_contains($criteria->allowable_days,'wednesday')) ? true : false)
                                  }}Wed
                                </label>

                                @if(str_contains($criteria->allowable_days,'thursday'))
                                <label class="btn btn-default btn-lg btn-active active">
                                  @else
                                  <label class="btn btn-default btn-lg">
                                    @endif

                                    {{  Form::checkbox(
                                      'allowable_days[]','thursday',
                                      (@str_contains($criteria->allowable_days,'thursday')) ? true : false)
                                    }}Thu
                                  </label>

                                  @if(str_contains($criteria->allowable_days,'friday'))
                                  <label class="btn btn-default btn-lg btn-active active">
                                    @else
                                    <label class="btn btn-default btn-lg">
                                      @endif

                                      {{  Form::checkbox(
                                        'allowable_days[]','friday',
                                        (@str_contains($criteria->allowable_days,'friday')) ? true : false)
                                      }}Fri
                                    </label>

                                    @if(str_contains($criteria->allowable_days,'saturday'))
                                    <label class="btn btn-default btn-lg btn-active active">
                                      @else
                                      <label class="btn btn-default btn-lg">
                                        @endif

                                        {{  Form::checkbox(
                                          'allowable_days[]','saturday',
                                          (@str_contains($criteria->allowable_days,'saturday')) ? true : false)
                                        }}Sat
                                      </label>
                                    </div>
                                  </div>
                                </div>

                                {{-- <div class="form-group">
                                 <div class="col-sm-12">
                                  <div class="btn-group custom-btn criteriaButton miles">
                                    @if($criteria->distance != NULL && $criteria->distance != '')
                                    <button type="button" class="btn btn-default distance-selected btn-active active" name="distance"><i class="fa fa-street-view"></i> <span>{{ $criteria->distance }} </span></button>
                                    <button type="button" class="btn btn-default dropdown-toggle btn-active acrive" data-toggle="dropdown">
                                      @else
                                      <button type="button" class="btn btn-default distance-selected" name="distance"><i class="fa fa-street-view"></i><span>Distance</span></button>
                                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        @endif
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                      </button>
                                      <ul class="dropdown-menu" role="menu">
                                        <li><a data-miles='' href="javascript:void(0)">Distance</a></li>
                                        <li><a data-miles='02' href="javascript:void(0)">02</a></li>
                                        <li><a data-miles='05' href="javascript:void(0)">05</a></li>
                                        <li><a data-miles='10' href="javascript:void(0)">10</a></li>
                                        <li><a data-miles='15' href="javascript:void(0)">15</a></li>
                                        <li><a data-miles='20' href="javascript:void(0)">20</a></li>
                                        <li><a data-miles='50' href="javascript:void(0)">50</a></li>
                                        <li><a data-miles='100' href="javascript:void(0)">100</a></li>
                                        <li><a data-miles='250' href="javascript:void(0)">250</a></li>
                                        <li><a data-miles='500' href="javascript:void(0)">500</a></li>
                                      </ul>
                                    </div>
                                  </div>
                                </div> --}}
                              </div><!-- /.box-body -->
                              {{ Form::close() }}
                            </div><!-- /.box -->


                            @section('custom-script')

                            <script type="text/javascript">
                             $(document).ready(function(){
                              var round_id = '{{ Route::current()->getParameter("id") }}';
                              $(".criteriaButton :input").change(function(e) {
                                e.preventDefault();

                                var ele = $(this);
                                var name = ele.attr('name');

                                var values = [];

                                $("input[name='"+name+"']:checked").each(function(){
                                  values.push($(this).val());
                                });

                                if(name != 'gender'){
                                  for (var i = 0; i < ele.length; i++) {
                                    var inp=ele[i];
                                    if($(inp).is(":checked")){
                                      $(this).closest('.btn-default').addClass('btn-active');
                                    }else{
                                      $(this).closest('.btn-default').removeClass('btn-active');
                                    }
                                  }
                                }else{
                                  var icon = $(this).next('i');
                                  icon.removeClass('fa');
                                  className = $(this).next('i').attr('class');
                                  icon.removeClass(className);

                                  new_className = {"fa-users":'fa-male', "fa-male":'fa-female',"fa-female":'fa-users'};
                                  new_val = { "fa-male":'male', "fa-female":'female',"fa-users":''};
                                  new_text = { "fa-male":'Male', "fa-female":'Female',"fa-users":'Gender'};

                                  new_class = new_className[className];
                                  values = [];
                                  values.push(new_val[new_class]);

                                  icon.addClass(new_class);
                                  icon.addClass('fa');
                                  $(icon).after();
                                  $(icon).next('.criteria_text').html(new_text[new_class]);

                                  if(values.length == 0 || values[0] == null || values[0] == "")
                                  {
                                    $(this).closest('.btn-default').removeClass('btn-active');
                                  }else{
                                    $(this).closest('.btn-default').addClass('btn-active');
                                  }
                                }
                                changeCriteria(round_id, name, values);
                              });

                              $(".criteriaButton a").click(function(e) {
                                name = 'distance';
                                var values = [];
                                values.push($(this).data('miles'));
                                changeCriteria(round_id, name, values);
                                if(values.length == 0 || values[0] == null || values[0] == "")
                                {
                                  $('.distance-selected').removeClass('btn-active');
                                  $('.distance-selected').next('.dropdown-toggle').removeClass('btn-active');
                                  $('.distance-selected > span').text('Distance');
                                }else{
                                  $('.distance-selected').addClass('btn-active');
                                  $('.distance-selected').next('.dropdown-toggle').addClass('btn-active');
                                  $('.distance-selected > span').text(values);
                                }


                              });

                              function changeCriteria(round_id, name, values){

                                var type = 'POST';
                                var url = APP_URL+'/fieldrep-set-criteria';
                                var formData =  { round_id: round_id, name: name,values: values  };
                                var dataType = 'json';

                                $.ajax({
                                  type: type,
                                  url: url,
                                  data: formData,
                                  dataType: dataType,
                                  success: function (data) {
                                    return true;
                                  },
                                  error: function (data) {
                                    return false;
                                  }
                                });
                              }
                            });
                          </script>
                          @endsection