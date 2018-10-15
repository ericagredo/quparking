<?php $__env->startSection('title', 'Park It - Edit Surcharge Amount'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Surcharge Amount
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">Surcharge Amount</li>
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
                            <h3 class="box-title">Surcharge Amount Setting</h3>
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
                        <form role="form" id="SurchargeAmountForm" method="post" action="<?php echo e(url('/surchargeamount/update')); ?>" enctype="multipart/form-data">
                               <?php echo e(csrf_field()); ?>

                               <div class="box-body">
                                   <div class="row">
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>Surcharge Amount (Upto 30min) <span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input class="form-control" placeholder="Surcharge Amount (Upto 30min)" type="text" name="amount_before_half_min" value="<?php echo e(!empty($surcharge_amount->amount_before_half_min) ? $surcharge_amount->amount_before_half_min : ''); ?>" onkeypress="return isNumber(event)" maxlength="3">
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                                <label>Surcharge Amount (From 30min to 60min) <span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input class="form-control" placeholder="Surcharge Amount (From 30min to 60min)" type="text" name="amount_after_half_min" value="<?php echo e(!empty($surcharge_amount->amount_after_half_min) ? $surcharge_amount->amount_after_half_min : ''); ?>" onkeypress="return isNumber(event)" maxlength="3">
                                                </div>
                                            </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                               <label>Hour Surcharge Amount<span class="asterisk red">*</span></label>
                                               <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input class="form-control" placeholder="Hour Surcharge Amount" type="text" name="amount_per_hour" value="<?php echo e(!empty($surcharge_amount->amount_per_hour) ? $surcharge_amount->amount_per_hour : ''); ?>" onkeypress="return isNumber(event)" maxlength="3">
                                                </div>
                                            </div>
                                       </div>
                                       <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                   <a href="<?php echo e(url('surchargeamount/editsurchargeamount')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
            amount_before_half_min: {
                validators: {
                    notEmpty: {
                        message: 'The Amount Upto 30min is required'
                    }
                }
            },
            amount_after_half_min: {
                validators: {
                    notEmpty: {
                        message: 'The Amount From 30min to 60min is required'
                    }
                }
            },
            amount_per_hour: {
                validators: {
                    notEmpty: {
                        message: 'The Hour Surcharge Amount is required'
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