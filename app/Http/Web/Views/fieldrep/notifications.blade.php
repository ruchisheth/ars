<!-- filename: notifications.blade.php -->
@if(isset($oRounds))
	@if(count($oRounds) > 0)
		{{--*/ $dCurrentDate = \Carbon::parse(\Carbon::now())  /*--}}
		@foreach($oRounds as $oRound)
			<li>
				{{--*/ $dEndDate = Carbon::parse($oRound->round_end)  /*--}}
				
				{{--*/ $nDays = $dEndDate->diffInDays($dCurrentDate)  /*--}}
				<a href="javascript::void(0)">
					<span class="notification-image"><i class="fa fa-dot-circle-o"></i></span>
					<span class="notification-text">
						@if($nDays == 0)
							{{--*/ $nHour = $dEndDate->diffInHours($dCurrentDate)  /*--}}
							@lang('messages.round_end_in_hour_notification', [ 'hour' =>  $nHour, 'round' => format_code($oRound->round_id).'-'.$oRound->round_name ])
						@elseif($nDays == 1)
							@lang('messages.round_end_tomorrow_notification', [ 'round' => format_code($oRound->round_id).'-'.$oRound->round_name ])
						@else
							@lang('messages.round_end_notification', [ 'days' =>  $nDays, 'round' => format_code($oRound->round_id).'-'.$oRound->round_name ])
						@endif
					</span>
				</a>
			</li>
		@endforeach
	@else
		<li><a href="javascript::void(0)">@lang('messages.no_new_notification')</a></li>
	@endif
@else
	<li><a href="javascript::void(0)">@lang('messages.no_new_notification')</a></li>
@endif