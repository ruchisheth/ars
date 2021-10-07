<div class="box collapsed-box">
        <div class="box-header with-border">
            <h6 class="box-title">
                Current Status 
                <span class="label label-success">Active</span>
            </h6>
            <div class="box-tools">                
                <button class="btn btn-box-tool" data-target="" data-toggle="modal" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
            </div>

        </div>
        <div class="box-body">
            <table id="round-grid" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>&nbsp;</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hired - IC</td>
                        <td>9 Sep 2013</td>
                        <td>marcia</td>
                        <td>
                            <button class="btn btn-box-tool" type="submit" name="remove_client" data-id="'.$fieldrep->id.'" value="delete" >
                                <span class="fa fa-edit"></span>
                            </button>

                            <button class="btn btn-box-tool" type="submit" name="remove_client" data-id="'.$fieldrep->id.'" value="delete" >
                                <span class="fa fa-trash"></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>