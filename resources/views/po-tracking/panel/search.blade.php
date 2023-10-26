<div class="well" style="overflow: auto;">
    <form method="post" action="{{url($link_search ?? '#')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <fieldset>
            <div class="col-md-1">
                <div class="control-group ">
                    <div class="controls">
                        <div class="input-prepend input-group">
                            <label style="MARGIN-TOP: 10px;">PO DATE</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="control-group ">
                    <div class="controls">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="tanggal1" class="form-control datepicker" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="control-group ">
                    <div class="controls">
                        <div class="input-prepend input-group">
                            <label for="" style="MARGIN-TOP: 10px;">TO DATE</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="control-group ">
                    <div class="controls">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="tanggal2" class="form-control datepicker" autocomplete="off" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center ">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                <a href="{{ $link_reset ?? '#'}}" type="submit" class="btn btn-sm btn-danger ml-1">Reset</a>
            </div>
        </fieldset>
    </form>
</div>
