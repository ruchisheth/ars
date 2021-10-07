@extends('layouts.web.main_layout')
@section('page-title') | {{ trans('messages.account_setting') }}  @stop
@section('content')
<section id="" class="content profile_page">
  <div class="fill-survey container">
    <div class="row">
      <div class="survey-info">
        <div class="info-header">
          <div class="icon-image img-150">
            {{-- {{ Html::image(AppHelper::USER_IMAGE.@$profile->profile_pic, "", ['class'=>'user-image']) }} --}}
            @if($oProfile->profile_pic != '')
            <img src="{{ getImageURL(config('constants.USERIMAGEFOLDER').'/'.$oProfile->profile_pic) }}">
            @else
            {!! getTextImage($oFieldRep->first_name, $oFieldRep->last_name) !!}
            @endif
          </div>
          <div class="entity-name"> 
            {{ '['.$oFieldRep->fieldrep_code.']'.' '.$oFieldRep->full_name }}
          </div>
        </div>
        <div class="survey-details">
          <strong><i class="fa fa-envelope margin-r-5"></i> {{ trans('messages.email') }}</strong>
          <p class="text-muted">{{ Auth::user()->email }}</p>
        </div>
        @if($oFieldRepOrg != NULL)
        <div class="survey-details">
          <strong><i class="fa fa-institution margin-r-5"></i> {{ trans('messages.organization') }}</strong>
          <p class="text-muted">{{ $oFieldRepOrg->fieldrep_org_name }}</p>
        </div>
        @endif
      </div>

      <div class="col-md-10 profile-section" id='js_profile_section'>
        @include('WebView::fieldrep.profile.profile_project_types')
        @include('WebView::fieldrep.profile.profile_availability')
      </div>
    </div>
  </section>
</div>
@stop