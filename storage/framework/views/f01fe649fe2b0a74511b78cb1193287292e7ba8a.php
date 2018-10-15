<?php $__env->startSection('title', 'Park It - Manage Admin Notification'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Admin Notification
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Admin Notification</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
       <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div id="success_message" style="color:#00A65A;font-weight: bolder;"></div>
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Admin Notification List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="staticpageList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Notification Title</th>
                                        <th>Notification Mode</th>
                                    </tr>
                                </thead>
                                <?php $__currentLoopData = $admin_notification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <tr>
                                    <td><?php echo e($notification->notification_title); ?></td>
                                    <td>
                                        <label class="switch">
                                            <?php 
                                                $checked = '';
                                                if($notification->notification_mode == 'ON'){
                                                    $checked = 'Checked="checked"';
                                                }
                                            ?>
                                            <input type="checkbox" name="notification_mode" value="<?php echo e(($notification->notification_mode)); ?>" <?php echo e($checked); ?> id="notification_mode_<?php echo e($notification->id); ?>" onclick="javascript:Change_notification_mode(<?php echo e($notification->id); ?>, this.value)">
                                            <div class="slider round"></div>
                                        </label>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </table>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
    function Change_notification_mode(id, value){
        $.ajax({
            type: "POST",
            url: '/adminnotification/updatenotification',
            data: "id=" + id + "&mode=" + value,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#success_message').html('Notification mode change successfully.');
                   setTimeout(function(){ $('#success_message').html(''); }, 3000);
                   $('#success_message').val('');
                }
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>