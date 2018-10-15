<?php $__env->startSection('title', 'Park It - Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php /*echo '<pre>';print_r($data);echo '</pre>'; exit; */?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        </ol>
    </section>
   <!-- /.content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo e($data[0]->spot_count); ?></h3>

                        <p>Registered Spots</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-earth"></i>
                    </div>
                    <a href="/parkingspot/parkingspotList" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green" style="padding: 0; height: auto">
                    <div class="inner">
                        <h3><?php echo e($data[1]->booking_count); ?></h3>

                        <p>Active Bookings</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-radio-button-on"></i>
                    </div>
                    <a href="/booking/managebooking" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo e($data[2]->users_count); ?></h3>

                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="/users/adminUsersList" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo e($data[3]->booking_count); ?></h3>

                        <p>Cancelled Bookings</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="/booking/managebooking" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>

        <!-- BAR CHART -->
        <div class="box box-success hidden">
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                    <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                    <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
                </ul>
                <div class="tab-content no-padding">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>

                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>