<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
  <style>
            /*.col-md-6{
              width: 50%;
              float: left;
            }*/
            .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
              position: relative;
              min-height: 1px;
              padding-right: 15px;
              padding-left: 15px;
            }
            .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
              float: left;
            }

            .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
              float: left;
            }
            .col-md-12 {
              width: 100%;
            }
            .col-md-11 {
              width: 91.66666667%;
            }
            .col-md-10 {
              width: 83.33333333%;
            }
            .col-md-9 {
              width: 75%;
            }
            .col-md-8 {
              width: 66.66666667%;
            }
            .col-md-7 {
              width: 58.33333333%;
            }
            .col-md-6 {
              width: 50%;
            }
            .col-md-5 {
              width: 41.66666667%;
            }
            .col-md-4 {
              width: 33.33333333%;
            }
            .col-md-3 {
              width: 25%;
            }
            .col-md-2 {
              width: 16.66666667%;
            }
            .col-md-1 {
              width: 8.33333333%;
            }
            .col-md-pull-12 {
              right: 100%;
            }
            .col-md-pull-11 {
              right: 91.66666667%;
            }
            .col-md-pull-10 {
              right: 83.33333333%;
            }
            .col-md-pull-9 {
              right: 75%;
            }
            .col-md-pull-8 {
              right: 66.66666667%;
            }
            .col-md-pull-7 {
              right: 58.33333333%;
            }
            .col-md-pull-6 {
              right: 50%;
            }
            .col-md-pull-5 {
              right: 41.66666667%;
            }
            .col-md-pull-4 {
              right: 33.33333333%;
            }
            .col-md-pull-3 {
              right: 25%;
            }
            .col-md-pull-2 {
              right: 16.66666667%;
            }
            .col-md-pull-1 {
              right: 8.33333333%;
            }
            .col-md-pull-0 {
              right: auto;
            }
            .col-md-push-12 {
              left: 100%;
            }
            .col-md-push-11 {
              left: 91.66666667%;
            }
            .col-md-push-10 {
              left: 83.33333333%;
            }
            .col-md-push-9 {
              left: 75%;
            }
            .col-md-push-8 {
              left: 66.66666667%;
            }
            .col-md-push-7 {
              left: 58.33333333%;
            }
            .col-md-push-6 {
              left: 50%;
            }
            .col-md-push-5 {
              left: 41.66666667%;
            }
            .col-md-push-4 {
              left: 33.33333333%;
            }
            .col-md-push-3 {
              left: 25%;
            }
            .col-md-push-2 {
              left: 16.66666667%;
            }
            .col-md-push-1 {
              left: 8.33333333%;
            }
            .col-md-push-0 {
              left: auto;
            }
            .col-md-offset-12 {
              margin-left: 100%;
            }
            .col-md-offset-11 {
              margin-left: 91.66666667%;
            }
            .col-md-offset-10 {
              margin-left: 83.33333333%;
            }
            .col-md-offset-9 {
              margin-left: 75%;
            }
            .col-md-offset-8 {
              margin-left: 66.66666667%;
            }
            .col-md-offset-7 {
              margin-left: 58.33333333%;
            }
            .col-md-offset-6 {
              margin-left: 50%;
            }
            .col-md-offset-5 {
              margin-left: 41.66666667%;
            }
            .col-md-offset-4 {
              margin-left: 33.33333333%;
            }
            .col-md-offset-3 {
              margin-left: 25%;
            }
            .col-md-offset-2 {
              margin-left: 16.66666667%;
            }
            .col-md-offset-1 {
              margin-left: 8.33333333%;
            }
            .col-md-offset-0 {
              margin-left: 0;
            }
          </style>
        </head>
        {{-- <body onload="window.print();"> --}}
        <div class="wrapper"><!-- Main content -->
          <section class="invoice"><!-- title row -->
            {{-- <div class="box box-default "> --}}
              <div class="col-md-12">
                <h2 class="page-header">
                 Survey Details
               </h2>
             </div><!-- /.col -->
              <div class="box-body">
                <div class="col-md-12">
                  <table class="table no-border no-row-height">
                    <tr>
                      <th>Client</th>
                      <td>{{ @$survey_details->client_name }}</td>
                      <th>Site</th>
                      <td>{{ @$survey_details->site_name }}</td>
                    </tr>
                    <tr>
                      <th>Project</th>
                      <td>{{ @$survey_details->project_name }}</td>
                      <th>FieldRep</th>
                      <td>{{ @$survey_details->fieldrep_name }}</td>
                    </tr>
                    <tr>
                      <th>Round</th>
                      <td>{{ @$survey_details->round_name }}</td>
                      <th>Assignment Code</th>
                      <td>{{ @$survey_details->code }}</td>
                    </tr>
                    <tr>
                      <th>Reported Date</th>
                      <td>{{ (@$oAssignment->reported_at != NULL) ? @$oAssignment->reported_at : 'NA' }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            {{-- </div> --}}
            <div class="row">
              <div class="col-md-12">
                <h2 class="page-header">
                 SURVEY
               </h2>
             </div><!-- /.col -->
           </div>
           <!-- info row -->
           <div class="row invoice-info">
            <div class="col-md-12 controls-holder">
              {!! $oSurvey->filled_surveydata !!}
            </div>
          </section><!-- /.content -->
        </div><!-- ./wrapper -->
        @include('includes.scripts')


        {{ Html::script(AppHelper::ASSETS.'plugins/builder/builder.js') }}

        <script type="text/javascript">
         $(document).ready(function(){
          $('small').remove();
          console.log('testSurvey');
          $('input.file-input').each(function(i, row) {
            var File = $(this).data('selected-image');
            fileIput = $(this).closest('div.file-input');
            fileIput.html('');
            if(typeof File != 'undefined'){
              var src = File.split(',');
              $.each(src, function (e, img_src) {
                image =  _builder.markup('img',null, {'src': img_src, 'width': '100px', 'height': '100px', 'class': 'survey_image'});
                fileIput.append(image);
              });
            }else{
              image = '<div class="survey_image"><center><h5>No Image</h5></center></div>';
              fileIput.append(image);
            }
          });

    // var html = document.getElementsByTagName('html')[0];
    // var htmlText = html.innerHTML;
  });
</script>

