@extends('layouts.master')
@section('title', 'QU - Add Parking Spot')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Add Parking Spot
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('parkingspot/parkingspotList') }}">Manage Parking Spot</a></li>
                <li class="active">Add Parking Spot</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Parking Spot</h3>
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
                        <form role="form" id="Parking_form" method="post" action="{{ url('/parkingspot/create') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Street Address<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Street Address" type="text" name="address" minlength="3">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Postal Code<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Postal Code" type="text" name="postal_code" minlength="3">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country Name" type="text" name="country_name" id="country_name">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>State/Province/Region Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="State/Province/Region Name" type="text" name="state_name" id="state_name" minlength="3">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>City Name<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="City Name" type="text" name="city_name" minlength="3">
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
                                            <label>Number of Space per spot<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Number of Space per spot" type="text" name="number_of_space_spot" onkeypress="return isNumber(event)">
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
                                            <label>Verification Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="verification_status">
                                                <option value="">Choose Verification Status</option>
                                                <option value="Yes">Verified</option>
                                                <option value="No">Verification Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Access Instruction/Notes <span class="asterisk red">*</span></label>
                                            <textarea class="form-control" placeholder="Access Instruction/Notes" name="description"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('parkingspot/parkingspotList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
                        message: 'The Address is required'
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
            country_name: {
                validators: {
                    notEmpty: {
                        message: 'The Country name is required'
                    }
                }
            },
            state_name: {
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
@endpush