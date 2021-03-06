<?php $__env->startSection('title', 'Park It - Edit Users'); ?>
<?php $__env->startSection('content'); ?>

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
              Edit Users
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo e(url('users/adminUsersList')); ?>">Manage Users</a></li>
                <li class="active">Edit Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Users</h3>
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
                        <form role="form" id="Users_form" method="post" action="<?php echo e(url('/users/update/'.$user->id)); ?>" enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>First Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="First Name" type="text" name="first_name" minlength="3" value="<?php echo e($user->firstname); ?>">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Last Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Last Name" type="text" name="last_name" minlength="3" value="<?php echo e($user->lastname); ?>">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Email<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Email" type="text" name="email" id="email" value="<?php echo e($user->email); ?>" onblur="javascript:CheckEmailExist(this.value, <?php echo e($user->id); ?>)">
                                            <input type="hidden" id="old_email" name="old_email" value="<?php echo e($user->email); ?>">
                                            <div id="error_message" class="help-block" style="color:#dd4b39;"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4 hidden">
                                        <div class="form-group">
                                            <label>Username<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Username" type="text" name="username" id="username" value="<?php echo e($user->username); ?>" minlength="3" onblur="javascript:CheckUsernameExist(this.value, <?php echo e($user->id); ?>)">
                                            <input type="hidden" id="old_username" name="old_username" value="<?php echo e($user->username); ?>">
                                            <div id="error_message_username" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option <?php echo e($user->status == 'Active' ? 'selected="selected"' : ''); ?> value="Active">Active</option>
                                                <option <?php echo e($user->status == 'Inactive' ? 'selected="selected"' : ''); ?> value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Contact number<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Contact number" type="Contact_number" name="contact_number" value="<?php echo e($user->contact_number); ?>" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Location</label>
                                            <input class="form-control" placeholder="Location" type="text" name="location" id="location" value="<?php echo e($user->location); ?>">
                                            <input class="form-control" type="hidden" name="latitude" id="latitude" value="<?php echo e($user->latitude); ?>">
                                            <input class="form-control" type="hidden" name="longitude" id="longitude" value="<?php echo e($user->longitude); ?>">
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <!--<div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country name" type="text" name="country_name" id="country_name" value="<?php echo e($user->country_id); ?>">
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
                                                <option <?php echo e($user->country_id == $single->id ? 'selected="selected"' : ''); ?> value="<?php echo e($single->id); ?>"><?php echo e($single->country_name); ?>&nbsp;-&nbsp;<?php echo e($single->country_code); ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Zip/Postal Code<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="zipcode" type="text" name="zipcode" id="zipcode" value="<?php echo e($user->zipcode); ?>">
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>User Profile image<span class="asterisk red">*</span></label>
                                            <input class="" type="file" name="profile_image" id="profile_image"> 
                                            <?php if($user->profile_image != ''): ?>
                                            <img src="<?php echo e(asset('uploads/user_profile_image/'.$user->profile_image)); ?>" width="100px" height="65px"> 
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4 custom_hide" >
                                        <div class="form-group">
                                            <label style="margin-right: 5px">Gender:  </label><div style="clear:both"></div>
                                            <input type="radio" <?php echo e($user->gender == 'Male' ? 'checked' : ''); ?> class="" id="male" name="gender" value="Male"> Male
                                            <input type="radio" <?php echo e($user->gender == 'Female' ? 'checked' : ''); ?> class="" id="female" name="gender" value="Female"> Female
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="<?php echo e(url('users/adminUsersList')); ?>" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
<!--https://maps.googleapis.com/maps/api/js?key=AIzaSyCglD6_9k33kyMOdh3KnSuVUwUye1JEIHs&libraries=places-->

<script type="text/javascript">
    var date1 = new Date();
    date1.setDate(date1.getDate());
    
    $('#datepicker3').datepicker({
        endDate: date1,
        autoclose: true
    });
    
    // set up select2
     $('#Users_form').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The first name is required'
                    }
                }
            }, 
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'The last name is required'
                    }
                }
            },
            /*username: {
                validators: {
                    notEmpty: {
                        message: 'The username is required'
                    }
                }
            },*/
            email: {
                validators: {
                    notEmpty: {
                        message: 'The Email is required'
                    }, emailAddress: {
                        message: 'Please Supply a valid email'
                    }
                }
            }, 
            profile_image: {
                validators: {
                    file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },
                }
            },
            cnfpassword: {
                validators: {
                    notEmpty: {
                        message: 'The Confirm password is required'
                    },
                    identical: {
                        field: 'password',
                        message: 'Password and Confirm passwors does not same'
                    },
                }
            }, 
            contact_number: {
                validators: {
                    notEmpty: {
                        message: 'The contact number is required'
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
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }
                }
            },
            country_id: {
                validators: {
                    notEmpty: {
                        message: 'The country is required'
                    }
                }
            },
        }
    });
    
    $('#datepicker3').on('changeDate show', function (e) {
        $('#Users_form').bootstrapValidator('revalidateField', 'date_of_birth');
    });
    
    function isNumber(evt) {
         evt = (evt) ? evt : window.event;
         var charCode = (evt.which) ? evt.which : evt.keyCode;
         if (charCode != 8 && charCode != 0 && charCode != 46 && (charCode < 48 || charCode > 57) ) {
         return false;
         }
         return true; 
    }
    
    function CheckEmailExist(value, id) {
        $.ajax({
            type: "POST",
            url: '/users/checkUserEmailExist',
            data: "email=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message').html('Email already exist.');
                   setTimeout(function(){ $('#error_message').html(''); }, 3000);
                   var old_email = $('#old_email').val();
                   $('#email').val(old_email);
                }
            }
        });
    }

    function CheckUsernameExist(value, id) {
        $.ajax({
            type: "POST",
            url: '/users/checkUsernameExist',
            data: "username=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message_username').html('Username already exist.');
                   setTimeout(function(){ $('#error_message_username').html(''); }, 3000);
                   var old_username = $('#old_username').val();
                   $('#username').val(old_username);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    google.maps.event.addDomListener(window, 'load', function () {
        var places = new google.maps.places.Autocomplete(document.getElementById('location'));

        google.maps.event.addListener(places, 'place_changed', function () {

            var place1 = places.getPlace();
            var address = place1.formatted_address;
            var latitude = place1.geometry.location.lat();
            var longitude = place1.geometry.location.lng();
            var mesg = "Address: " + address;
            mesg += "\nLatitude: " + latitude;
            mesg += "\nLongitude: " + longitude;

            $("#latitude").val(latitude);
            $("#longitude").val(longitude);
        });
    });                                              
</script>
<script type="text/javascript">
/*google.maps.event.addDomListener(window, 'load', function () {
    var places = new google.maps.places.Autocomplete(document.getElementById('country_name'));
});  */                                            
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>