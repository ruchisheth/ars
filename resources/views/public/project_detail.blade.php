
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
