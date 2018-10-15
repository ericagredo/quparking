<?php $__env->startSection('title', 'Park It - View Profile'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                View Profile
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('admin/viewProfile')); ?>">View Profile</a></li>

            </ol>
        </section>
        
        <!-- Main content --> 
        <section class="content">
            <div class="row contentpanel">
                <div class="col-xs-12 text-right margin-bottom">
                    <a href="<?php echo e(url('/admin/editProfile')); ?>"><button class="btn btn-primary" type="button">Edit Profile</button></a>
                    <a href="<?php echo e(url('/admin/profilechangePassword')); ?>"><button class="btn btn-primary" type="button">Change Password</button></a>
                </div>
            </div> 
            <!--Form controls-->
            <?php if(Session::has('message')): ?>
            <p class="alert alert-danger"><?php echo e(Session::get('message')); ?></p>
            <?php endif; ?>
            <?php if(Session::has('success')): ?>
            <p class="alert alert-success"><?php echo e(Session::get('success')); ?></p>
            <?php endif; ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"> View Profile</h3>
                        </div>
                        <?php echo e(csrf_field()); ?>

                        <div class="box-body">
                            <div class="row">

                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>First Name</label><br>
                                        <?php echo e($user->first_name); ?>

                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Last Name</label><br>
                                        <?php echo e($user->last_name); ?>

                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Email Address </label><br>
                                        <?php echo e($user->email_address); ?>

                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Status </label><br>
                                        <?php echo e($user->status); ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>