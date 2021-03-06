@extends('layouts.master')
@section('title', 'QU - Add Users')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Add Users
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('users/adminUsersList') }}">Manage Users</a></li>
                <li class="active">Add Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Users</h3>
                        </div>
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if(Session::has('message'))
                        <p class="alert alert-danger">{{ Session::get('message') }}</p>
                        @endif
                        <form role="form" id="Users_form" method="post" action="{{ url('/users/create') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>First Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="First Name" type="text" name="first_name" minlength="3">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Last Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Last Name" type="text" name="last_name" minlength="3">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Email<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Email" type="text" name="email" id="email" onblur="javascript:CheckEmailExist(this.value, '')">
                                            <div id="error_message" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Username<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Username" type="text" name="username" id="username" minlength="3" onblur="javascript:CheckUsernameExist(this.value, '')">
                                            <div id="error_message_username" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Password<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Password" type="password" name="password" minlength="8">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Confirm Password<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Confirm Password" type="password" name="cnfpassword" minlength="8">
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Contact number<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Contact number" type="Contact_number" name="contact_number" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Location <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Location" type="text" name="location" id="location" value="">
                                            <input class="form-control" type="hidden" name="latitude" id="latitude" value="">
                                            <input class="form-control" type="hidden" name="longitude" id="longitude" value="">
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country name" type="text" name="country_name" id="country_name" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Zip/Postal Code<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="zipcode" type="text" name="zipcode" id="zipcode" value="">
                                        </div>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>User Profile image<span class="asterisk red">*</span></label>
                                             <input class="" type="file" name="profile_image" id="profile_image">  
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label style="margin-right: 5px">Gender:  </label><div style="clear:both"></div>
                                            <input type="radio" class="" id="male" name="gender" checked value="Male">Male
                                            <input type="radio" class="" id="female" name="gender" value="Female">Female
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('users/adminUsersList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
@endsection

@push('scripts')
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>

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
google.maps.event.addDomListener(window, 'load', function () {
    var places = new google.maps.places.Autocomplete(document.getElementById('country_name'));
});                                              
</script>
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
            username: {
                validators: {
                    notEmpty: {
                        message: 'The username is required'
                    }
                }
            },
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
                    notEmpty: {
                        message: 'Please select an image'
                    },
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
            country_name: {
                validators: {
                    notEmpty: {
                        message: 'The country_name is required'
                    }
                }
            }, 
            zipcode: {
                validators: {
                    notEmpty: {
                        message: 'The zipcode is required'
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
                   $('#error_message').html('Email already Exist');
                   setTimeout(function(){ $('#error_message').html(''); }, 3000);
                   $('#email').val('');
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
                   $('#error_message_username').html('Username already Exist');
                   setTimeout(function(){ $('#error_message_username').html(''); }, 3000);
                   $('#username').val('');
                }
            }
        });
    }
</script>
@endpush