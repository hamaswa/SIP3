<div class='btn-group'>
    <!--<a href="{{ route('nusers.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>-->
    <a data-remote="{{$id}}" id="addExtension" data-toggle="modal" data-target="#inquiry_view" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-plus"></i>
    </a>
</div>
<!-- BEGIN CALL TO MODEL PANEL
================================================== -->
<div class="modal fade inquiry_view" id="inquiry_view">
    <div class="modal-dialog">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                      <h3 class="profile-username text-center">Add Extension</h3>
                                      
                      <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <b>Extension: </b>
                                </div>
                                <div class="form-group col-md-6">
                                	<input type="hidden" class="form-control" id="currentID" />
                                    <input type="text" class="form-control" id="ext" style="width:100%" />
                                </div>
                                <div class="col-md-2">
                                    <a id="addExtBtn" class='btn btn-default btn-xs'>
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
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