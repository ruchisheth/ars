@extends('app')

@section('content')

<div class="content-wrapper">
        <section class="content">
        	<div class="box">
            <div class="box-header with-border">
              <i class="fa fa-user"></i>
              <h3 class="box-title">SurveyData</h3>
              @include('includes.success')
              @include('includes.errors')
              <div class="box-tools">
               <a href="{{url('/clients-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
             </div>
           </div><!-- /.box-header -->

           <div class="box-body">
            <div class="box-header with-border custom-header"></div>

            <table id="cleint-grid" class="table table-bordered table-hover" width="100%">
            </table>

          </div><!-- /.box-body -->

        </div><!-- /.box -->
      </div>
    </section>

  </div>
  @stop

  @section('custom-script')

  <script type="text/javascript">

    var oTable ='';
    $(document).ready(function(){

      //xhr call to retrieve data
      var xhrcall = $.ajax({
        type: 'POST',
        url: APP_URL+'/survey_data',
      });

      xhrcall.done(renderTable);

    });/* .ready overe*/

    function renderTable(xhrdata) {


      var cols = [];
      
      var exampleRecord = xhrdata.data;

      console.log(exampleRecord);

      var columns = JSON.parse(JSON.stringify(exampleRecord))[0];

      exampleRecord.splice(0, 1);


      //var keys = Object.keys(exampleRecord);
      var keys = Object.keys(JSON.parse(JSON.stringify(exampleRecord))[0]);

      keys.forEach(function(k) {
        cols.push({
          data: eval(k),
          title: columns[k]
          //title: columns[k]
        });
      });


      var oTable = $('#cleint-grid').DataTable({
        columns: cols,
      });

      oTable.rows.add(exampleRecord).draw();
    }
  </script>

  @stop