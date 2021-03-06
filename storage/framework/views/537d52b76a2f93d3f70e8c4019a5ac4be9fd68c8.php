<?php $__env->startSection('title', 'Park It - Edit User Notification'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
              Edit User Notification
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('usernotification/manageusernotification')); ?>">Manage User Notification</a></li>
                <li class="active">Edit User Notification</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit User Notification</h3>
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
                        <form role="form" id="Form_Control" method="post" action="<?php echo e(url('/usernotification/update/'.$notification->id)); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <div class="box-body">
                                
                                <div class="row">
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Notification Title <span class="asterisk red">*</span></label>
                                            <textarea class="form-control" placeholder="Notification Title" type="text" name="notification_title" rows="3"><?php echo e($notification->notification_title); ?></textarea>
                                            
                                            <input type="hidden" id="old_notification_title" name="old_notification_title" value="<?php echo e($notification->notification_title); ?>">
                                            <div id="error_message_notification_title" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                        <div class="form-group">
                                            <label>Status <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose One</option>
                                                <option <?php echo e($notification->status == 'Active' ? 'selected="selected"' : ''); ?> value="Active">Active</option>
                                                <option <?php echo e($notification->status == 'Inactive' ? 'selected="selected"' : ''); ?> value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="clear:both"></div>
                                   
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="<?php echo e(url('usernotification/manageusernotification')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
     $('#Form_Control').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            notification_title: {
                validators: {
                    notEmpty: {
                        message: 'The notification title is required'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }
                }
            }
        }
    });
    
    
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>