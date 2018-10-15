<?php $__env->startSection('title', 'Park It - Edit General Settings'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                General Settings
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">General Settings</li>
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
                            <h3 class="box-title">General Settings</h3>
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
                        <form role="form" id="SurchargeAmountForm" method="post" action="<?php echo e(url('/generalsettings/update')); ?>" enctype="multipart/form-data">
                               <?php echo e(csrf_field()); ?>

                               <div class="box-body">
                                   <div class="row">
                                       <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                           <div class="form-group">
                                               <label>Cancellation Fee<span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input class="form-control" placeholder="Cancellation Fee" type="text" name="cancellation_fee" maxlength="3" value="<?php echo e(!empty($general_settings->cancellation_fee) ? $general_settings->cancellation_fee : ''); ?>" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4 ">
                                           <div class="form-group">
                                                <label>Admin Commission in percentage<span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control" placeholder="Commission Amount" type="text" name="commission_amount" maxlength="3" value="<?php echo e(!empty($general_settings->commission_amount) ? $general_settings->commission_amount : ''); ?>" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                                <label>Distance of miles<span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Km</span>
                                                    <input class="form-control" placeholder="Distance of miles" type="text" name="distance_of_miles" maxlength="3" value="<?php echo e(!empty($general_settings->distance_of_miles) ? $general_settings->distance_of_miles : ''); ?>" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                       </div>
                                       <div style="clear:both"></div>
                                       <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                           <div class="form-group">
                                                <label>Discount Amount<span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control" placeholder="Discount Amount" type="text" name="discount_amount" maxlength="3" value="<?php echo e(!empty($general_settings->discount_amount) ? $general_settings->discount_amount : ''); ?>" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                           <div class="form-group">
                                                <label>Penalty Amount<span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control" placeholder="Penalty Amount" type="text" name="penalty_amount" maxlength="3" value="<?php echo e(!empty($general_settings->penalty_amount) ? $general_settings->penalty_amount : ''); ?>" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                   <a href="<?php echo e(url('generalsettings/editgeneralsettings')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
    $('#SurchargeAmountForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            cancellation_fee: {
                validators: {
                    notEmpty: {
                        message: 'The cancellation fee is required'
                    }
                }
            },
            commission_amount: {
                validators: {
                    notEmpty: {
                        message: 'The commission amount is required'
                    }
                }
            },
        }
    });
    
    function isNumber(evt) {
         evt = (evt) ? evt : window.event;
         var charCode = (evt.which) ? evt.which : evt.keyCode;
         if (charCode != 8 && charCode != 0 && charCode != 46 && (charCode < 48 || charCode > 57) ) {
         return false;
         }
         return true; 
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>