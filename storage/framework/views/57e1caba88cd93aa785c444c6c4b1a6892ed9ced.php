<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route("change_user_pass")); ?>">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="user_id" value="<?php echo e($id); ?>">

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" placeholder="Email Address" value="<?php echo e(isset($email) ? $email : old('email')); ?>" required autofocus>

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="Password" name="password" required>

                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                            </div>
                        </div>

                        <div class="form-grcol-md-9 offset-md-3oup row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn submit-btn pull-left">
                                    <?php echo e(__('Reset Password')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>