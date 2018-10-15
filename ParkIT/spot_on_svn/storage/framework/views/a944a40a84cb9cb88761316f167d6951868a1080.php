<?php $__env->startSection('title', 'Park It - Edit App Settings'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                App Settings
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">App Settings</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <?php if(Session::has('success')): ?>
            <p class="alert alert-success"><?php echo e(Session::get('success')); ?></p>
            <?php endif; ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">App Settings</h3>
                        </div>
                        <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <form role="form" id="AppForm" method="post" action="<?php echo e(url('/appsettings/update')); ?>" enctype="multipart/form-data">
                               <?php echo e(csrf_field()); ?>

                               <div class="box-body">
                                   <div class="row">
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>App Name <span class="asterisk red">*</span></label>
                                               <input class="form-control" placeholder="App Name" type="text" name="app_name" minlength="3" value="<?php echo e(!empty($app_setting->app_name) ? $app_setting->app_name : ''); ?>">
                                           </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>App Logo <span class="asterisk red">*</span></label>
                                               <input class="" placeholder="App Name" type="file" name="app_logo">
                                                <?php if($app_setting->app_logo != ''): ?>
                                                <img src="<?php echo e(asset('uploads/app_logo_images/'.$app_setting->app_logo)); ?>" width="100px" height="65px"> 
                                                <?php endif; ?>
                                           </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>Admin User<span class="asterisk red">*</span></label>
                                               <select class="form-control select2" style="width: 100%;" name="admin_users" >
                                                   <option value="">Choose Admin User</option>
                                                   <?php $AdminusersList = Helper::getAdminUserList(); ?>
                                                    <?php $__currentLoopData = $AdminusersList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Adminuser): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                        <?php $selected = '' ?>
                                                        <?php if($app_setting->tbl_adminuser_id == $Adminuser->id): ?> 
                                                            <?php $selected = 'selected=selected' ?>
                                                        <?php endif; ?>   
                                                      <option value="<?php echo e($Adminuser->id); ?>" <?php echo e($selected); ?>><?php echo e($Adminuser->first_name.' '.$Adminuser->last_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?> 
                                               </select>
                                           </div>
                                        </div>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                               <div class="box-footer">
                                   <a href="<?php echo e(url('appsettings/editappsettings')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
                                   <button class="btn btn-primary pull-right" type="submit">Save</button>
                               </div>
                        </form> 
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

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
    // set up select2
    $('#AppForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            app_name: {
                validators: {
                    notEmpty: {
                        message: 'The App Name is required'
                    }
                }
            },
            app_logo: {
                validators: {
                    file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    }
                }
            },
            admin_users: {
                validators: {
                    notEmpty: {
                        message: 'The Admin users is required'
                    }
                }
            },
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>