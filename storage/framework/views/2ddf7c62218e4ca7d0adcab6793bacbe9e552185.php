<?php $__env->startSection('title', 'Park It - Edit Parking Spot'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Edit Parking Spot
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('parkingspot/parkingspotList')); ?>">Manage Parking Spot</a></li>
                <li class="active">Edit Parking Spot</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Parking Spot</h3>
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
                        <form role="form" id="Parking_form" method="post" action="<?php echo e(url('/parkingspot/update/'.$parking_spot->id)); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Street Address<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Street Address" type="text" name="address" minlength="3" value="<?php echo e(!empty($parking_spot->address) ? $parking_spot->address : ''); ?>">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Postal Code<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Postal Code" type="text" name="postal_code" minlength="3" value="<?php echo e(!empty($parking_spot->postal_code) ? $parking_spot->postal_code : ''); ?>">
                                        </div>
                                    </div> 
                                    <!--<div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country Name" type="text" name="country_name" id="country_name" value="<?php echo e(!empty($parking_spot->country_name) ? $parking_spot->country_name : ''); ?>">
                                        </div>
                                    </div>-->
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="country_id" id="country_id">
                                                <option value="">Choose One</option>
                                                <?php
                                                    if(isset($country) && !empty($country)){ 
                                                        foreach($country as $single){ 
                                                ?>
                                                <option <?php echo e($parking_spot->country_id == $single->id ? 'selected="selected"' : ''); ?> value="<?php echo e($single->id); ?>"><?php echo e($single->country_name); ?>&nbsp;-&nbsp;<?php echo e($single->country_code); ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!--<div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>State/Province/Region Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="State/Province/Region Name" type="text" name="state_name" id="state_name" minlength="3" value="<?php echo e(!empty($parking_spot->state_name) ? $parking_spot->state_name : ''); ?>"> 
                                        </div>
                                    </div>-->
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>State Name <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="state_id" id="state_id">
                                                <option value="">Choose One</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>City Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="City Name" type="text" name="city_name" minlength="3" value="<?php echo e(!empty($parking_spot->city_name) ? $parking_spot->city_name : ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Location <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Location" type="text" name="location" id="location" value="<?php echo e(!empty($parking_spot->location) ? $parking_spot->location : ''); ?>">
                                            <input class="form-control" type="hidden" name="latitude" id="latitude" value="<?php echo e(!empty($parking_spot->latitude) ? $parking_spot->latitude : ''); ?>">
                                            <input class="form-control" type="hidden" name="longitude" id="longitude" value="<?php echo e(!empty($parking_spot->longitude) ? $parking_spot->longitude : ''); ?>">
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                        <div class="form-group">
                                            <label>Number of Space per spot<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Number of Space per spot" type="text" name="number_of_space_spot" onkeypress="return isNumber(event)" value="<?php echo e(!empty($parking_spot->number_of_space_spot) ? $parking_spot->number_of_space_spot : ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option <?php echo e(($parking_spot->status == 'Active') ? 'selected="selected"' : ''); ?> value="active">Active</option>
                                                <option <?php echo e(($parking_spot->status == 'Inactive') ? 'selected="selected"' : ''); ?> value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Verification Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="verification_status">
                                                <option value="">Choose Verification Status</option>
                                                <option <?php echo e(($parking_spot->verification_status == 'Yes') ? 'selected="selected"' : ''); ?> value="Yes">Verified</option>
                                                <option <?php echo e(($parking_spot->verification_status == 'No') ? 'selected="selected"' : ''); ?> value="No">Verification Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Access Instruction/Notes <span class="asterisk red">*</span></label>
                                            <textarea class="form-control" placeholder="Access Instruction/Notes" name="description"><?php echo e(!empty($parking_spot->description) ? $parking_spot->description : ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>

                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="<?php echo e(url('parkingspot/parkingspotList')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
    var state_id_for_check = '<?php echo $parking_spot->state_id; ?>';
    var country_id_for_check = '<?php echo $parking_spot->country_id; ?>';
    if(state_id_for_check && country_id_for_check){
        ajaxCallForState(country_id_for_check);
    }
    
    $("#country_id").on('change',function (){
        var country_id = $(this).val();
            ajaxCallForState(country_id);
     });
    function ajaxCallForState(country_id){
        if(country_id != '' && country_id != null && country_id != undefined){
            $.ajax({
                type: "POST",
                url: '/state/get_all_state_by_country_id',
                data: "country_id=" + country_id,
                async: false, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    var state_list = response.data;
                        var state_id = '<?php echo $parking_spot->state_id; ?>';
                        var tbody_data = '';
                        tbody_data += '<option value="">Choose One</option>';
                        $.each(state_list, function (key, value) {
                            var select = '';
                            if(state_id == value['id']){
                                var select = 'selected="selected"';
                            }
                            tbody_data += '<option '+select+' value="'+value['id']+'">'+value['state_name']+'</option>';
                        })
                        $("#state_id").html(tbody_data);
                }
            });
            }
    }
    
</script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>

<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', function () {
    var places = new google.maps.places.Autocomplete(document.getElementById('location'));

    google.maps.event.addListener(places, 'place_changed', function () {

        var place1 = places.getPlace();
        var address = place1.formatted_address;
        var latitude = place1.geometry.location.lat();
        var longitude = place1.geometry.location.lng();
        var mesg = "Editress: " + address;
        mesg += "\nLatitude: " + latitude;
        mesg += "\nLongitude: " + longitude;

        $("#latitude").val(latitude);
        $("#longitude").val(longitude);

    });
});
</script>
<script type="text/javascript">
    // set up select2
    $('#Parking_form').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            address: {
                validators: {
                    notEmpty: {
                        message: 'The Editress is required'
                    }
                }
            }, 
            postal_code: {
                validators: {
                    notEmpty: {
                        message: 'The Postal code is required'
                    }
                }
            },
            country_id: {
                validators: {
                    notEmpty: {
                        message: 'The Country name is required'
                    }
                }
            },
            state_id: {
                validators: {
                    notEmpty: {
                        message: 'The State name is required'
                    },
                }
            },
            city_name: {
                validators: {
                    notEmpty: {
                        message: 'The city name is required'
                    }
                }
            },
           /* location: {
                validators: {
                    notEmpty: {
                        message: 'The location is required'
                    }
                }
            }, */
            verification_status: {
                validators: {
                    notEmpty: {
                        message: 'The Verification status is required'
                    }
                }
            },
            number_of_space_spot: {
                validators: {
                    notEmpty: {
                        message: 'The number of space is required'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required'
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