@extends('layouts.master')
@section('title', 'QU - Edit Country')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
              Edit Country
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('country/managecountry') }}">Manage Country</a></li>
                <li class="active">Edit Country</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Country</h3>
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
                        <form role="form" id="Form_Control" method="post" action="{{ url('/country/update/'.$country->id) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box-body">
                                
                                <div class="row">
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country Name" type="text" name="country_name" id="country_name" minlength="2" maxlength="20" value="{{ $country->country_name }}" onblur="javascript:CheckCountryNameExist(this.value, {{$country->id}})">
                                            <input type="hidden" id="old_country_name" name="old_country_name" value="{{ $country->country_name }}">
                                            <div id="error_message_country_name" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Country Code <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Country Code" type="text" name="country_code" id="country_code" minlength="1" maxlength="10" value="{{ $country->country_code }}" onblur="javascript:CheckCountryCodeExist(this.value, {{$country->id}})">
                                            <input type="hidden" id="old_country_code" name="old_country_code" value="{{ $country->country_code }}">
                                            <div id="error_message_country_code" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose One</option>
                                                <option {{ $country->status == 'Active' ? 'selected="selected"' : '' }} value="Active">Active</option>
                                                <option {{ $country->status == 'Inactive' ? 'selected="selected"' : '' }} value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="clear:both"></div>
                                   
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('country/managecountry') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
            country_code: {
                validators: {
                    notEmpty: {
                        message: 'The country code is required'
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
    
    
    function CheckCountryNameExist(value, id) {
        $.ajax({
            type: "POST",
            url: '/country/CheckCountryNameExist',
            data: "country_name=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message_country_name').html('Country Name already Exist.');
                   setTimeout(function(){ $('#error_message_country_name').html(''); }, 3000);
                   var old_country_name = $('#old_country_name').val();
                   $('#country_name').val(old_country_name);
                }
            }
        });
    }
    
    function CheckCountryCodeExist(value, id) {
        $.ajax({
           type: "POST",
            url: '/country/CheckCountryCodeExist',
            data: "country_code=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message_country_code').html('Country Code already Exist');
                   setTimeout(function(){ $('#error_message_country_code').html(''); }, 3000);
                   var old_country_code = $('#old_country_code').val();
                   $('#country_code').val(old_country_code);
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
@endpush