<?php $__env->startSection('title', 'Park It - Report Receipt'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Booking Report Receipt
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Booking Report Receipt</li>
        </ol>
    </section>
    <div class="row" style="margin-top: 15px">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <form role="form" id="ReceiptForm" method="post" action="<?php echo e(url('/reports/Savereceiptreports/'.$admin_fund_managment_id)); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Booking Report Receipt</label>
                                    <input class="" placeholder="Booking Report Receipt" type="file" name="uploaded_receipt[]" multiple>
                                </div>
                            </div>
                            <div style="margin: 20px">  
                                <a href="<?php echo e(url('reports/reportListAdmin')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
                                <button class="btn btn-primary pull-right" type="submit">Add Receipt</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="box box-primary box-solid">
        <div class="row">
            <div class="col-xs-12">
                    <?php if(count($receipt) > 0): ?>
                    <?php $__currentLoopData = $receipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rece): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <div id="" style="margin: 10px;border: 1px solid #ccc;float: left;position:relative;">
                        <a href="javascript:void(0)" onclick="javasript:deleterecord(<?php echo e($rece->id); ?>)">
                            <span class="fa-stack fa-sm" style="position: absolute; right: -10px; top: -10px;">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                            </span>
                        <a>    
                        <img src="<?php echo e(asset('uploads/bank_receipt/'.$rece->uploaded_receipt)); ?>" width="200px" height="200px" style="border:5px solid #999999;">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    <?php else: ?>
                        <div id="" style="float: left;position:relative;font-size: 16px;margin-left: 10px;">
                           Receipt not available yet.
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </div>    
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
 $('#ParkingSpotForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'uploaded_image[]': {
                validators: {
                    notEmpty: {
                        message: 'Please select an image'
                    },
                    file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    }
                }
            }
        }
    });    
    
var DeleteImageAjaxSource = '/reports/deletereceiptGallery';
function deleterecord(id) {
    if (confirm("Are you sure you want to delete?")) {
       $.ajax({
           type: "POST",
           url: DeleteImageAjaxSource,
           data: "id=" + id,
           headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           success: function(data) {
               if (data == 1) {
                   window.location.href = window.location.href;
               }
           }
       });
       }
 }
 
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>