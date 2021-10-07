   
   @if($aSurveyQuestion['type'] == 'text')
      <label class="form-control ">{{ $aSurveyQuestion['ans'] }}</label>
   @elseif($aSurveyQuestion['type'] == 'date')
      <div class="input-group date">
         <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
         </div>
         <label class="form-control">{{ $aSurveyQuestion['ans'] }}</label>
      </div>
   @elseif($aSurveyQuestion['type'] == 'textarea')
      <div class="form-control builder-control">{{ $aSurveyQuestion['ans'] }}</div>
   @elseif($aSurveyQuestion['type'] == 'radio')
      @foreach($aSurveyQuestion['options'] as $sOption)
      <div class="col-md-6">
         <i class="fa {{ ($sOption['exported_value']  ==  $aSurveyQuestion['ans']) ? 'fa-dot-circle-o' : 'fa-circle-o'}}"></i> {{ $sOption['exported_value'] }}
      </div>
      @endforeach
   @elseif($aSurveyQuestion['type'] == 'file')
      {{--*/ $aFileNames = explode(",", $aSurveyQuestion['ans']) /*--}}
      @foreach($aFileNames as $sFileName)
         <div class="responsive">
           <div class="gallery">
             <a target="_blank" href="">
               <img src="http://www.alpharepservice.com/public/assets/images/survey/KLP/696/{{ pathinfo($sFileName)['basename'] }}" width="300" height="200" class="" alt="Image not available">
               {{-- <img src="http://www.alpharepservice.com/public/assets/images/survey/KLP/696/{{ pathinfo($sFileName)['basename'] }}" class="survey-image" alt="Image not available" width="300" height="200"> --}}
               {{-- <img src="img_fjords.jpg" alt="Trolltunga Norway" width="300" height="200"> --}}
             </a>
           </div>
         </div>
         {{-- <img src="{{ asset(config('constants.SURVEYFOLDERURL').Auth::user()->client_code.'/'.$oSurvey->id.'/'.pathinfo($sFileName)['basename']) }}"> --}}
      @endforeach
   @elseif($aSurveyQuestion['type'] == 'checkbox')
      @foreach($aSurveyQuestion['options'] as $sOption)
      <div class="col-md-6">
         <i class="fa {{ ($sOption['exported_value']  ==  $aSurveyQuestion['ans']) ? 'fa-check-square-o' : 'fa-square-o'}}"></i> {{ $sOption['exported_value'] }}
      </div>
      @endforeach
   @else
      <span>{{ $aSurveyQuestion['type'] }}</span>
@endif