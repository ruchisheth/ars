<div class="row">
<div class="col-md-12">

<table id="table-grid" class="col-md-12">
 @if(@$assign_instruction)
 @foreach($assign_instruction as $instruct)
 <tr id="instruction-{{ @$instruct['instruction_id'] }}"><td><h3>{{ @$instruct['instruction_name'] }}</h3></td></tr>
 <tr><td id="instruction" class="td-inst">{{ @$instruct['instruction'] }}</td></tr>
 @if(isset($instruct['attachments']))
 <tr>  
  <td id="" >
    @foreach($instruct['attachments'] as $index => $attachment)
    <div class="custom-file-input custom-size">
      <div class="file-preview-frame" id="preview-1478160911940-1" data-fileindex="1" data-template="other" style="width:160px;height:160px;">
        <div class="kv-file-content">
          <div class="kv-preview-data file-preview-other-frame">
            <div class="file-preview-other">
              <span class="file-other-icon">
                @if($attachment['fileType'] == 'image')
                <a href="{{ $attachment['filepath'] }}" target="_blank">
                  <img src="{{ $attachment['filepath'] }}" class="inst-img" style="height: 100% !important">
                </a>
                @elseif($attachment['fileType'] == 'pdf')                
                <a href="{{ $attachment['filepath'] }}" target="_blank">
                  <i class="{{ $attachment['previewIcon'] }}"></i>
                </a>
                @else
                <i class="{{ $attachment['previewIcon'] }}"></i>
                @endif
              </span>
            </div>
          </div>
        </div>
        <div class="file-thumbnail-footer">
         <div class="file-actions">
          <div class="file-footer-buttons">
            <a href="{{ $attachment['filepath'] }}" target="_blank" class="kv-file-remove btn btn-xs btn-default" title="Download file" data-url="{{ $attachment['filepath'] }}" download><i class="fa fa-download"></i></a>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
    </div>
    @endforeach
  </td>
</tr>
<tr>
  <td>

  </td>
</tr>
@endif
@endforeach
@endif
</table>
</div>
</div>