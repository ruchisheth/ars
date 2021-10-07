@extends('.app')
@section('page-title') | {{ trans('messages.export_fieldrep') }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
            'method'  => 'POST',
            'url'     => route('export.fieldrep.data')
            ]) }}
            {{  Form::hidden('url', URL::previous())  }}
            <div class="box">
              <div class="box-header with-border">
                <i class="fa fa-group"></i>
                <h6 class="box-title text-muted">
                 {{ trans('messages.export_fieldrep') }}
               </h6>
             </div><!-- /.box header -->
             <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  @include('includes.success')
                  @include('includes.errors')
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('client', trans('messages.select_classification')) }}
                    {{  Form::select(
                      'classification',
                      [
                        '' => 'Select Classification',
                        1 => 'Independent Contractor',
                        2 => 'Employee'
                      ],'',
                      [
                        'class' =>  'form-control',
                        'id'  => 'classification',
                      ])
                    }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('project', 'Select Status') }}
                    {{  Form::select(
                      'initial_status',
                      [
                        '' => 'Select Status',
                        1 =>  'Active',
                        0 =>  'Inactive'
                      ],'',
                      [
                        'class' =>  'form-control',
                        'id'    =>  'projects',
                      ])
                    }}
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="pull-right">
                <div class="pull-right">
                  {{  Form::submit('Export',
                    [
                      'id' => 'create',
                      'class' => 'btn btn-primary pull-right'
                    ])
                  }}
                </div>
                <div class="col-md-1 pull-right">                    
                  <a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">Cancel</a>                      
                </div>
              </div>
            </div><!-- /.box -->
            {{  Form::close() }}
          </div><!-- main col-md-6 -->
        </div>
      </section>
    </div>
    @stop

    @section('custom-script')

    <script type="text/javascript">
      $(document).ready(function () {

      });/* document ready over */

    </script>

    @append