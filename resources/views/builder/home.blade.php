@extends('app')
@section('page-title') | {{ (@$template->id) ? 'Survey Template Edit' : 'Survey Template Create' }}  @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div id="column-selector" class="row {{ (@$template->id) ? '' : '' }}">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h6 class="box-title">{{ (@$template->id) ? 'Edit' : 'Create' }} Survey Template</h6>
          </div>
          {{ Form::open(['method'=>'post','id' => 'template_detail_forms', 'class' =>  (@$template->id) ? 'section-template' : 'section-template s_t']) }}
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-danger" style="display: none"></div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-3 {{ (@$template->id) ? 'hide' : '' }}">
                <select name="columns" class="form-control">
                  <option value='1'>1 Column</option>
                  <option value='2'>2 Column</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <input type="text" id="template_name" name="template_name" autofocus='true' class="form-control" value="{{ @$template->template_name }}" placeholder="Template Name">
              </div>

              <div class="action-btns col-md-1 {{ (@$template->id) ? 'hide' : '' }}">
                <button type="button" class="btn btn-primary" onclick="MakeParts()">Go!</button>
              </div>

              <div class="action-btns col-md-1 {{ (@$template->id) ? '' : 'hide' }}">
                <button type="button" class="btn btn-primary" name="save_template_name" id="save_template_name" onclick="javascript:void(0)">Save</button>
                {{-- <button class="btn btn-primary" onclick="MakeParts()">Edit Question!</button> --}}
              </div>
            </div>
          </div><!-- /.box-body- -->
          {{ Form::close() }}
        </div><!-- /.box -->
      </div><!-- /.col-md-12 -->
    </div><!-- /.column-selector -->
    <div id="builder-holder" class="row {{ (@$template->id) ? '' : 'hide' }}">
      <div class="col-md-3 control-holder"><!-- btn-group-vertical -->
        <div class="box no-border">
          <div class="box-body">
            <button type="button" onclick="CreateElement('input')" class="btn btn-block btn-primary"></i>Text Box</button>
            <button type="button" onclick="CreateElement('date')" class="btn btn-block btn-primary"></i>Date</button>
            <button type="button" onclick="CreateElement('textarea')" class="btn btn-block btn-primary">TextArea</button>
            <button type="button" onclick="CreateElement('file')" class="btn btn-block btn-primary">File Input</button>
            <button type="button" onclick="CreateElement('checkbox')" class="btn btn-block btn-primary">Checkbox</button>
            <button type="button" onclick="CreateElement('checkbox-group')" class="btn btn-block btn-primary">Checkbox Group</button>
            <button type="button" onclick="CreateElement('radio')" class="btn btn-block btn-primary">Radio</button>
            <button type="button" onclick="CreateElement('select')" class="btn btn-block btn-primary">Select</button>
            <button type="button" onclick="CreateElement('header')" class="btn btn-block btn-primary">Header</button>
            <button type="button" onclick="CreateElement('service_code')" class="btn btn-block btn-primary service_code_btn">SubmitYourInvoice.com Referance Number</button>
          </div>
        </div>
      </div>
      <div class="col-md-9 template-holder pull-right">
        {{-- <div class="panel panel-default"> --}}
        <div class="box">
          <div class="box-header with-border">
           <h3 class="box-title">{{ (@$template->id) ? 'Edit' : 'Create' }} Template</h3>

         </div>
         <div class="box-body">
          <form id="form-holder">
            @if(!@$template->id)
            <div class="template-section">
              <div class="row sortableRow"></div>
            </div>
            @else
            <input type="hidden" name="template_id" value="{{ @$template->id }}">
            <div class="template-section">
              {!! @$template->template !!}
            </div>
            @endif
          </form>
        </div>
        <div class="box-footer" style="text-align: right">
          {{-- <div class="pull-right"> --}}
          <a href="{{ route('form.builder') }}" class="btn btn-default">Cancel</a>
          @if(!@$template_in_use)
          <button class="btn btn-primary" onclick="$(this).button('loading');ExportTemplate();" data-loading-text="Saving...">Save</button>
          @endif
          <button class="btn btn-success" data-toggle="modal" data-target="#SaveTemplateAs" id="SaveAsBtn" data-loading-text="Saving...">Save As</button>
          {{-- </div> --}}
          {{-- <div class="col-md-1 pull-right"> --}}
          {{-- <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a> --}}
          {{-- </div> --}}
        </div><!-- ./box-footer -->
      </div><!-- ./box -->
      {{-- </div>.panel --}}
    </div>
  </div>
</section>
</div>
<div id="SaveTemplateAs" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Save Template As</h4>
      </div>
      <div class="modal-body">
        <form class=""  id="save_template_as_form" action="" onsubmit="return false">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-danger" style="display: none"></div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <label>Template Name</label>
                <input type="text" class="form-control" id="save_template_as" name="save_template_as">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save_tmeplate_as_btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="EditTemplateControl" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Template control</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal"  id="advFields" action="" onsubmit="return false">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="SaveBtn">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="AddOptionControl" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Option</h4>
      </div>
      <div class="modal-body">
        <form class="" action="" onsubmit="return false">
          <div class="row">
            <div class="col-md-3 col-md-offset-2 text-center"><h4>Displayed Value</h4></div>
            <div class="col-md-4 col-md-offset-1 text-center"><h4>Exported Value</h4></div>
          </div>
          <ul class="option-list todo-list">
          </ul>
        </form>
        <div class="pull-right">
          <a href="javascript:void(0)" onclick="CreateOption()">+ Add Option</a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="SaveOptionBtn">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
@stop
@section('custom-script')
{{ Html::script(AppHelper::ASSETS.'plugins/builder/builder.js') }}

<script type="text/javascript">
  $(document).ready(function(){
    disableInputControl();
    InitSortable();
    initToolTip();

    $(window).scroll(function(){
      var window_top = $(window).scrollTop();
      var div_top = $('.control-holder').offset().top;
//      if($(window).width() < 1280); 
if (window_top >= div_top && window_top >= 200 ) {
  $('.control-holder').addClass('stick');
} else {
  $('.control-holder').removeClass('stick');
}
});

    $(".option-list").sortable({
      cursor: 'move',
      placeholder: "sort-highlight",
      forcePlaceholderSize: true,
      zIndex:9999
    });


    $('#AddOptionControl').on('hide.bs.modal', function (event) 
    {
      $('.option-list').empty();
    });
    //str = 'radio';
    //Edit Mode 
    var from_group = $('#form-holder').find('.form-group');
    $.each(from_group, function (e, t) 
    {
      $(this).addClass('builder-input');
      Input = $(this).find('.builder-control');
      var InputType = (Input).data('input_type');
      switch(InputType)
      {
        case 'text':
        data_type = 'text-input';
        break;
        case 'checkbox':
        data_type = 'checkbox';
        break;
        case 'date':
        data_type = 'date-input';
        break;
        case 'textarea':
        data_type = 'textarea';
        break;
        case 'file':
        data_type = 'file';
        break;
        default:
        data_type = 'header';
        break;
      };

      if($(this).find('.radio-group').length != 0){
        $(this).prepend(
          '<div class="field-actions pull-right">'+
          '<a href="javascript:;" onclick="AddOption(this, \'radio\')" data-type="radio" class="builder-action"><i class="fa fa-plus"></i></a>'+
          '<a href="javascript:;" onclick="EditInput(this)" data-type="radio-group" class="builder-action"><i class="fa fa-pencil"></i></a>'+
          '<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>'+
          '<span class="builder-action handle">'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '</span>'+
          '</div>'
          );
      }else if($(this).find('.checkbox-group').length != 0){
        $(this).prepend(
          '<div class="field-actions pull-right">'+
          '<a href="javascript:;" onclick="AddOption(this, \'checkbox\')" data-type="checkbox" class="builder-action"><i class="fa fa-plus"></i></a>'+
          '<a href="javascript:;" onclick="EditInput(this)" data-type="checkbox-group" class="builder-action"><i class="fa fa-pencil"></i></a>'+
          '<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>'+
          '<span class="builder-action handle">'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '</span>'+
          '</div>'
          );
      }else if($(this).find('select').length != 0){
        $(this).prepend(
          '<div class="field-actions pull-right">'+
          '<a href="javascript:;" onclick="AddOption(this, \'select\')" data-type="select" class="builder-action"><i class="fa fa-plus"></i></a>'+
          '<a href="javascript:;" onclick="EditInput(this)" data-type="select" class="builder-action"><i class="fa fa-pencil"></i></a>'+
          '<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>'+
          '<span class="builder-action handle">'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '</span>'+
          '</div>'
          );
      }else{
        $(this).prepend(
          '<div class="field-actions pull-right">'+
          '<a href="javascript:;" onclick="EditInput(this)" data-type="'+data_type+'" class="builder-action"><i class="fa fa-pencil"></i></a>'+
          '<a href="javascript:;" onclick="DeleteInput(this)" class="builder-action"><i class="fa fa-remove"></i></a>'+
          '<span class="builder-action handle">'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '<i class="fa fa-ellipsis-v"></i>'+
          '</span>'+
          '</div>'
          );
      }
    });
  });
$(document).find('.label.label-primary').addClass('que_no');

$(document).on('click', '.label_text', function(){
  if($(this).find('#attribute').length > 0){
    text = $(this).find('#attribute').val();  
  }else{
    text = $(this).text();
    var input = $('<textarea rows="2" cols="110" id="attribute" class="form-control col-md-12">'+ text + '</textarea>');
    $(this).text('').append(input);
    input.select();
  }
});

$(document).on("keypress", "#template_detail_forms input:text", function (e) {
  if($(this).parents('form').hasClass('s_t')){
    if (e.keyCode == 13){
      e.preventDefault();
      MakeParts();
    }
  }else{
    if (e.keyCode == 13){
      return false;
    }
    else{
      return;
    }
  }
});

function initToolTip(){
  $('.template-holder .label_text').tooltip(
  {
    title: "Click to edit",
    placement: "top",

  }); 
}
</script>
@stop

