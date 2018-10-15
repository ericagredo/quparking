<?php $__env->startSection('title', 'Park It - Edit State'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
              Edit State
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('state/managestate')); ?>">Manage State</a></li>
                <li class="active">Edit State</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit State</h3>
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
                        <form role="form" id="Form_Control" method="post" action="<?php echo e(url('/state/update/'.$state->id)); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <div class="box-body">
                                
                                <div class="row">
                                    <?php /*echo '<pre>';
                                            echo $state->country_id; exit;*/ ?>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="country_name">
                                                <option value="">Choose One</option>
                                                <?php
                                                    if(isset($country) && !empty($country)){ 
                                                        foreach($country as $single){ 
                                                ?>
                                                <option <?php echo e($state->country_id == $single->id ? 'selected="selected"' : ''); ?> value="<?php echo e($single->id); ?>"><?php echo e($single->country_name); ?>&nbsp;-&nbsp;<?php echo e($single->country_code); ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>State Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="State Name" type="text" name="state_name" id="state_name" minlength="2" maxlength="20" value="<?php echo e($state->state_name); ?>" onblur="javascript:CheckStateNameExist(this.value, <?php echo e($state->id); ?>)">
                                            <input type="hidden" id="old_state_name" name="old_state_name" value="<?php echo e($state->state_name); ?>">
                                            <div id="error_message_state_name" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose One</option>
                                                <option <?php echo e($state->status == 'Active' ? 'selected="selected"' : ''); ?> value="Active">Active</option>
                                                <option <?php echo e($state->status == 'Inactive' ? 'selected="selected"' : ''); ?> value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="clear:both"></div>
                                   
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="<?php echo e(url('state/managestate')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
            country_name: {
                validators: {
                    notEmpty: {
                        message: 'The country name is required'
                    }
                }
            }, 
            state_name: {
                validators: {
                    notEmpty: {
                        message: 'The state name is required'
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
    
    function CheckStateNameExist(value, id) {
        
        $.ajax({
            type: "POST",
            url: '/state/CheckStateNameExist',
            data: "state_name=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message_state_name').html('State Name already Exist');
                   setTimeout(function(){ $('#error_message_state_name').html(''); }, 3000);
                   var old_state_name = $('#old_state_name').val();
                   $('#state_name').val(old_state_name);
                }
            }
        });
    }
    
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>