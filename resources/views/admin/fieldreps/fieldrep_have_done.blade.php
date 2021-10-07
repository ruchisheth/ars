{{ Form::open(array('method'=>'post',
    'enctype'  =>  'multipart/form-data',
    'url' => route('store.store_interestedin'), 
    )) }}

    {{  Form::hidden('id',@$fieldrep->id)  }}
    {{  Form::hidden('type','admin')  }}
    <div class="box collapsed-box">
        <div class="box-header with-border">
            <h6 class="box-title">Project Type </h6>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
            <div class="col-md-6" style="float:right">
                <div class="alert" style="display: none"></div>
            </div>
        </div>


        <div class="box-body">
            <table id="round-grid" class="table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Have Done</th>
                        <th>Interested In</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project_types as $pType_id => $project_type)
                    <tr>
                        <td>{{ @$project_type }}</td>
                        <td>
                            <label>
                               {{  Form::checkbox(
                               "have_done[$pType_id]",1,
                               (@str_contains($fieldrep->have_done,$pType_id)) ? true : false,
                               [
                               'class' => 'minimal',
                               ])
                           }}
                       </label>
                   </td>
                   <td>
                    <label>
                        {{  Form::checkbox(
                        "interested_in[$pType_id]",1,
                        (@str_contains($fieldrep->interested_in,$pType_id)) ? true : false,
                        [
                        'class' => 'minimal ',
                        ])
                    }}
                </label>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div><!-- /.box-body -->
<div class="box-footer">

    <div class="pull-right">
        <div class="pull-right">
            {{  Form::submit('Save',
            [
            'id' => 'create',
            'class' => 'btn btn-primary pull-right',
            (!@$fieldrep->id) ? 'disabled' : ''
            ])
        }}

    </div>
    <div class="col-md-1 pull-right">
        <a href="{{ route('show.fieldreps.get') }}" id="cancel" class="btn btn-default pull-right">Cancel</a>
    </div>

</div>                                                                                                    
</div>
</div><!-- /.box -->
{{ Form::close() }}