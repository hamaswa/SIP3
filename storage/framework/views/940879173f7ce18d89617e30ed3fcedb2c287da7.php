<?php $__env->startSection('content-header'); ?>
<h1>
	Combined call detail report
</h1>
<ol class="breadcrumb">
	<li><a href="<?php echo e(URL::asset('/')); ?>cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
	<li class="active">Combined call detail report</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Combined call detail report (Per User)</h3>
            <div class="box-tools">
               <!--<div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>-->
            </div>
            <hr/>
            <?php echo Form::open(['method'=>'get','id'=>"iocallreportfrm"]); ?>

             <input name="type" type="hidden" value="" id="type">
             <div class="row">
            	<div class="col-sm-3 form-group">
                	<label for="exampleInputEmail1">Date range</label>
                    <button type="button" class="btn btn-default form-control" id="daterange-btn">
                        <span class="pull-left">
                        	<?php if(Session::get('dateFrom')!=NULL): ?>
                            	<i class="fa fa-calendar"></i> <?php echo e(Session::get('dateFrom')); ?> - <?php echo e(Session::get('dateTo')); ?>

                            <?php else: ?>
                          		<i class="fa fa-calendar"></i> Date range picker
                            <?php endif; ?>
                        </span>
                        <i class="fa fa-caret-down pull-right"></i>
                    </button>
                    <input type="hidden" name="dateFrom" id="dateFrom" value="<?php echo e(Session::get('dateFrom')); ?>" />
                    <input type="hidden" name="dateTo" id ="dateTo" value="<?php echo e(Session::get('dateTo')); ?>" />
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">User</label>
                    <?php echo Form::text('calling_from', null, ['class' => 'form-control']); ?>

                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">&nbsp;</label>
                    <div class="input-group">
                        <!--<input class="form-control" id="search"
                               value="<?php echo e(request('search')); ?>"
                               placeholder="Search name" name="search"
                               type="text" id="search"/>-->
                        <div class="input-group-btn">
                            <button id="btnsubmit" class="btn btn-primary"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

         </div>
         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
             <?php if(request()->user()->can("download_combined")): ?>
             <div class="pull-right">

                 <div class="col-sm-12">
                     <a href="#" class="download" id="xls">Download Excel xls</a> |

                     <a href="#" class="download" id="xlsx">Download Excel xlsx</a> |

                     <a href="#" class="download" id="csv">Download CSV</a>
                 </div>

             </div>
             <?php endif; ?>
            <table class="table table-hover">
               <tbody>
                  <tr>
                    <th>User</th>
                    <th>Total</th>
                    <th>Incoming</th>
                    <th>Outgoing</th>
                    <th>Answered</th>
                    <th>Unanswered</th>
                    <th>Duration</th>
                    <th>Avg Duration</th>
                  </tr>
                  <?php $__currentLoopData = $ioReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <tr>
                        <td><?php echo e($data->caller_id_number); ?></td>
                        <td><?php echo e($data->Total); ?></td>
                        <td><?php echo e($data->Inbound); ?></td>
                        <td><?php echo e($data->Outbound); ?></td>
                        <td><?php echo e($data->Completed); ?></td>
                        <td><?php echo e($data->Missed); ?></td>
                        <td><?php echo e(gmdate("H:i:s", (int)$data->Duration)); ?></td>
                        <td><?php echo e(gmdate("H:i:s", (int)round($data->Duration/$data->Total))); ?></td>
                    </tr>
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </tbody>
            </table>
            <nav>
                <ul class="pagination pagination-sm no-margin pull-right">
                    <?php echo e($ioReport->links('vendor.pagination.bootstrap-4')); ?>

                </ul>
            </nav>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script type="text/javascript">
        $(function () {
            $("#btnsubmit").click(function (e) {
                $("#type").val("");
                $( "#iocallreportfrm").submit();

            });

            $(".download").click(function () {
                $("#type").val($(this).attr('id'));
                $( "#iocallreportfrm").submit();
            })
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>