@extends('app')
@section('page-title') | {{  (@$site->id) ? 'Site Edit' : 'Site Add' }} @stop
@section('content')
<div class="content-wrapper">

  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(array('method'=>'post',
          'url' => route('store.site'), 
          'enctype'  =>  "multipart/form-data")) }}
          {{  Form::hidden('id',@$site->id)  }}                
          {{  Form::hidden('url',URL::previous())  }}                
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-cubes"></i>
              <h6 class="box-title">
                {{  (@$site->id) ? 'Site Edit' : 'Site Add' }}
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
                    {{  Form::label('site_code', 'Site Code')}}
                    <small>(leave blank for auto number)</small>
                    {{  Form::text('site_code', @$site->site_code,
                      [
                      'id' => 'site_code',
                      'class' => 'form-control',
                      'placeholder' => 'Site Code',
                      'autofocus' => 'ture',
                      ])
                    }}
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('chain', 'Select Chain',['class' => 'mandatory']) }}
                    {{  Form::select(
                      'chain_id',
                      @$chains,
                      (@$site->chain_id) ? @$site->chain_id : @$chain_id,
                      [
                      'class' =>  'form-control',
                      'autofocus' => 'true',
                      ])
                    }}
                  </div>
                  
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('site_name', 'Site Name',['class' => 'mandatory'])}}
                    {{  Form::text('site_name', @$site->site_name,
                      [
                      'id' => 'site_name',
                      'class' => 'form-control',

                      ])
                    }}
                  </div>
                </div>                                
              </div>  

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('fieldrep', 'Select Fieldrep') }}
                    {{  Form::select(
                      'fieldrep_id',
                      @$fieldreps,
                      (@$site->fieldrep_id) ? @$site->fieldrep_id : @$fieldrep_id,
                      [
                      'class' =>  'form-control',
                      ])
                    }}
                  </div>
                  
                </div>  
                @if(@$site->id)                          
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('status', 'Status') }}
                    {{  Form::select('status', array(                                                      
                      '1'  => 'Open',
                      '0'  => 'Closed',
                      
                      ), @$site->status,
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
                {{  Form::label('distribution_center', 'Distribution Center')}}
                {{  Form::text('distribution_center', @$site->distribution_center,
                  [
                  'id' => 'distribution_center',
                  'class' => 'form-control',
                  ])
                }}
              </div>
            </div>
            
          </div>


          <div class="box-header with-border custom-header">
            <h6 class="box-title">
              <small>LOCATION</small>
            </h6>
          </div>

          <div class="row">
           <div class="col-md-12">
            <div class="form-group">
              {{  Form::label('street', 'Street',['class' => 'mandatory'])}}
              {{  Form::text('street', @$site->street,
                [
                'id' => 'street',
                'class' => 'form-control',
                ])
              }}
            </div>
          </div>   

          <div class="col-md-4">
            <div class="form-group">
              {{  Form::label('city', 'City',['class' => 'mandatory'])}}
              {{  Form::text('city', @$site->city,
                [
                'id' => 'city',
                'class' => 'form-control',
                ])
              }}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              {{  Form::label('state', 'State',['class' => 'mandatory']) }}
              {{  Form::select('state', array(
                ''   => 'Select State',
                'AA' => 'AA', 'AK' => 'AK', 'AL' => 'AL', 'AP' => 'AP',
                'AR' => 'AR', 'AS' => 'AS', 'AZ' => 'AZ', 'CA' => 'CA',
                'CN' => 'CN', 'CO' => 'CO', 'CT' => 'CT', 'DC' => 'DC',
                'DE' => 'DE', 'FL' => 'FL', 'FM' => 'FM', 'GA' => 'GA', 
                'GU' => 'GU', 'HI' => 'HI', 'IA' => 'IA', 'ID' => 'ID', 
                'IL' => 'IL', 'IN' => 'IN', 'KS' => 'KS', 'KY' => 'KY', 
                'LA' => 'LA', 'MA' => 'MA', 'MD' => 'MD', 'ME' => 'ME', 
                'MH' => 'MH', 'MI' => 'MI', 'MN' => 'MN', 'MO' => 'MO', 
                'MP' => 'MP', 'MS' => 'MS', 'MT' => 'MT', 'NC' => 'NC', 
                'ND' => 'ND', 'NE' => 'NE', 'NH' => 'NH', 'NJ' => 'NJ', 
                'NM' => 'NM', 'NV' => 'NV', 'NY' => 'NY', 'OH' => 'OH', 
                'OK' => 'OK', 'OR' => 'OR', 'PA' => 'PA', 'PR' => 'PR', 
                'PW' => 'PW', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 
                'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VA' => 'VA', 
                'VI' => 'VI', 'VT' => 'VT', 'WA' => 'WA', 'WI' => 'WI', 
                'WV' => 'WV', 'WY' => 'WY', 'AB' => 'AB', 'BC' => 'BC', 
                'MB' => 'MB', 'NB' => 'NB', 'NL' => 'NL', 'NS' => 'NS', 
                'NT' => 'NT', 'NU' => 'NU', 'ON' => 'ON', 'PE' => 'PE', 
                'QC' => 'QC', 'SK' => 'SK', 'YT' => 'YT'
                ),@$site->state,
              [
              'id' => 'state',
              'class' => 'form-control',
              ])
            }}
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            {{  Form::label('zip', 'Zip Code',['class' => 'mandatory'])}}
            {{  Form::text('zipcode', @$site->zipcode,
              [
              'id' => 'zipcode',
              'class' => 'form-control',
              ])
            }}
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            {{  Form::label('phone_number', 'Phone Number')}}
            {{  Form::text('phone_number', @$site->phone_number,
              [
              'id' => 'phone_number',
              'class' => 'form-control',
              'data-inputmask' => '"mask": "(999) 999-9999"',
              'data-mask' => '',

              ])
            }}
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            {{  Form::label('fax_number', 'Fax Number')}}
            {{  Form::text('fax_number', @$site->fax_number,
              [
              'id' => 'fax_number',
              'class' => 'form-control',
              'data-inputmask' => '"mask": "(999) 999-9999"',
              'data-mask' => '',

              ])
            }}
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
           {{  Form::label('notes', 'Notes') }}
           {{  Form::textarea('notes',@$site->notes,
            [
            'id' => 'notes',
            'class' => 'form-control',       
            'rows' => 3,
            'cols' => 50
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

    </div>                                                                                             @if(@$site->id != '')
    <h6><small>Created {{ @$site->created }} | Last modified {{ @$site->updated }} </small></h6>
    @endif        
  </div><!-- /.box -->
  {{ Form::close() }}
</div>


</div>
<div class="col-md-6">

  <!-- show contacts -->
  @if(@$site->id != '')

  @include('admin.contacts.contacts',[

    'entity_type'=>$entity_type,  
    'contact_types => $contact_types',
    'reference_id'=>$site->id

    ])
    
    <!-- </div> -->
    <!-- </section> -->

    @endif
    <!-- / show contacts -->
  </div>



</div>
</section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {

       // getTimeZone();

       /* Input Mask */
       $("[data-mask]").inputmask();

       $(".select2").select2();

       $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });

        // function GoogleGeocode() {
        //   geocoder = new google.maps.Geocoder();
        //   this.geocode = function(address, callbackFunction) {
        //       geocoder.geocode( { 'address': address}, function(results, status) {
        //         if (status == google.maps.GeocoderStatus.OK) {
        //           var result = {};
        //           result.latitude = results[0].geometry.location.lat();
        //           result.longitude = results[0].geometry.location.lng();
        //           callbackFunction(result);
        //         } else {
        //           alert("Geocode was not successful for the following reason: " + status);
        //           callbackFunction(null);
        //         }
        //       });
        //   };
        // }


      });

    </script>

    @append
