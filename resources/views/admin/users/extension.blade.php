<!-- BEGIN CALL TO MODEL PANEL
==================================================

-->
<div class="modal fade inquiry_view" id="inquiry_view">
    <div class="modal-dialog">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                      <h3 class="profile-username text-center">Real Time Extension</h3>
                                      
                      <ul class="list-group list-group-unbordered">
                          @foreach($extensions as $extension)
                                <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <b>{{$extension->$extension." (".$extension->name.")"}}: </b>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="hidden" class="form-control" id="currentID" />
                                    <input type="checkbox" name="realtime" value="{{$extension->extension}}" class="form-control" style="width:100%" />
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="hidden" class="form-control" id="currentID" />
                                    <input type="checkbox" name="realtime_details"  value="{{$extension->extension}}" class="form-control" style="width:100%" />
                                </div>

                            </div>
                        </li>
                          @endforeach
                      </ul>
                      <div id="userExt"></div>
        
                      <a href="#" class="btn btn-danger pull-right btn-sm" data-dismiss="modal"><b>Close</b></a>
                    </div>
                    <!-- /.box-body -->
                  </div>
            </div>
        </div>
        
    </div>
</div>
<!-- /. end model-->