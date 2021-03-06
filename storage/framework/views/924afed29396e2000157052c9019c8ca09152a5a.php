<?php $__env->startSection('title', 'Park It - Edit Promo Codes'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Edit Promo Codes
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('promocode/promocodeList')); ?>">Manage Promo Codes</a></li>
                <li class="active">Edit Promo Codes</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Promo Codes</h3>
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
                        <?php if(Session::has('message')): ?>
                        <p class="alert alert-danger"><?php echo e(Session::get('message')); ?></p>
                        <?php endif; ?>
                        <form role="form" id="Promocode_form" method="post" action="<?php echo e(url('/promocode/update/'.$promocode->id)); ?>">
                            <?php echo e(csrf_field()); ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Promo Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Promo Name" type="text" name="promo_name" minlength="3" value="<?php echo e($promocode->promo_name); ?>">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>Discount <span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control" placeholder="Discount" type="text" name="discount" onkeypress="return isNumber(event)" maxlength="3" value="<?php echo e($promocode->discount); ?>">
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option <?php echo e(($promocode->status == 'Active') ? 'selected="selected"' : ''); ?> value="Active">Active</option>
                                                <option <?php echo e(($promocode->status == 'Inactive') ? 'selected="selected"' : ''); ?> value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Start Date </label><span class="asterisk red">*</span>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="datepicker" name="promo_start_date" value="<?php echo e(($promocode->promo_start_date != '0000-00-00') ? date('m/d/Y', strtotime($promocode->promo_start_date)) : ''); ?>">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>End Date</label><span class="asterisk red">*</span>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="datepicker1" name="promo_end_date" value="<?php echo e(($promocode->promo_end_date != '0000-00-00') ? date('m/d/Y', strtotime($promocode->promo_end_date)) : ''); ?>">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="<?php echo e(url('promocode/promocodeList')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
    var date1 = new Date();
    date1.setDate(date1.getDate());
    $('#datepicker').datepicker({
        startDate: date1,
        autoclose: true
    });
    $('#datepicker1').datepicker({
        startDate: date1,
        autoclose: true
    });
    $('#Promocode_form').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            promo_name: {
                validators: {
                    notEmpty: {
                        message: 'The promo name is required'
                    }
                }
            }, 
            discount: {
                validators: {
                    notEmpty: {
                        message: 'The discount is required'
                    }
                }
            },
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }
                }
            },
            promo_start_date: {
                validators: {
                    notEmpty: {
                        message: 'The Start Date is required'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        max: 'promo_end_date',
                        message: 'The format is MM/DD/YYYY and Start date is Lessthan End date'
                    }
                }
            },
            promo_end_date: {
                validators: {
                    notEmpty: {
                        message: 'The End Date is required'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        min: 'promo_start_date',
                        message: 'The format is MM/DD/YYYY and End date is Greaterthan Start date'
                         }
                }
            },
        }
    });
    
    $('#datepicker1').on('changeDate show', function (e) {
        $('#Promocode_form').bootstrapValidator('revalidateField', 'promo_end_date');
    });
    $('#datepicker').on('changeDate show', function (e) {
        $('#Promocode_form').bootstrapValidator('revalidateField', 'promo_start_date');
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