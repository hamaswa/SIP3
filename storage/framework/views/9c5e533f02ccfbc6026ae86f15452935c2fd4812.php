<?php $__env->startSection('content-header'); ?>
<h1>
	Real Time
</h1>
<ol class="breadcrumb">
	<li><a href="<?php echo e(URL::asset('/')); ?>cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Realtime report</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">RealTime report</h3>

         </div>

         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
             <?php echo $dataTable->with('interface',$interface)->table(['width' => '100%']); ?>

         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
    <?php echo $__env->make('admin.layouts.datatables_css', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('admin.layouts.datatables_js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $dataTable->scripts(); ?>

<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>