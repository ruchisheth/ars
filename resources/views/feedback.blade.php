<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
	@include('includes.styles')
</head>
<body class="hold-transition feedback-page login-page">
	<div class="fdbk-master">
		<div class="fdbk-box">
			<div class="fdbk-wrapper">
				<div class="fdbk-qest"> Were you satisfied by the service provided by the FieldRep? </div>
				<div class="fdbk-radio">
					<div class="row">
						<div class="col-xs-12">							
							<div class="btn-group" data-toggle="buttons">
								<label class="btn fdbk-yes active">
									<input type="radio" name='gender2'><i class="fa fa-smile-o fa-2x"></i><span> YES </span>
								</label>
								<label class="btn fdbk-no no-cl">
									<input type="radio" name='gender2' checked><i class="fa fa-frown-o fa-2x"></i> <span> NO </span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="fdbk-frm">
					<form class="form-hor">
  						<div class="form-group">
							<div class="fdbk-cmnt col-md-6 col-md-offset-3"> <textarea class="form-control" placeholder="Say Something..."></textarea> </div>
							<div class="fdbk-snd"> <input type="submit" value="SEND" /> </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
{{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

<!-- Bootstrap 3.3.5 -->
{{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}
</body>
</html>