@extends('layouts.master')
@section('title', 'QU - Edit Pricing')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Edit Pricing   
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('pricing/pricingList') }}">Pricing List</a></li>
                <li class="active">Edit Pricing</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Pricing</h3>
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
                        <form role="form" id="PricingForm" method="post" action="{{ url('/pricing/update/'.$pricing->id) }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4" style="width: 33%">
                                        <div class="form-group" style="float: left;width: 35%;">
                                            <label>Hourly Pricing: <span class="asterisk red">*</span></label> <div style="clear:both"></div>
                                            <input id="noofhour" class="form-control" placeholder="Hourly Pricing" data-format="H" data-template="H" id="no_of_hours" name="no_of_hours" value="{{ $pricing->no_of_hours }}" type="text" style="width: 70%;float:left">
                                        </div>
                                        <div class="form-group" style="float: left;width: 65%;">
                                            <label></label> <div style="clear:both"></div>
                                            <div class="input-group" style="margin-top:5px">
                                                <span class="input-group-addon">$</span>
                                                <input class="form-control" placeholder="Hourly Price" type="text" name="hourly_price" onkeypress="return isNumber(event)" value="{{ $pricing->hourly_price }}"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4" style="width: 33%">
                                        <div class="form-group" style="float: left;width: 35%;">
                                            <label>Daily Pricing: <span class="asterisk red">*</span></label><div style="clear:both"></div>
                                            <input id="noofdays" class="Customdaymonthhour" data-format="D" data-template="D" name="no_of_days" value="{{ $pricing->no_of_days }}" type="text" style="width: 70%;float:left">
                                        </div>
                                        <div class="form-group" style="float: left;width: 65%;">
                                            <label></label> <div style="clear:both"></div>
                                            <div class="input-group" style="margin-top:5px">
                                                <span class="input-group-addon">$</span>
                                                <input class="form-control" placeholder="Daily Price" type="text" name="daily_price" onkeypress="return isNumber(event)" value="{{ $pricing->daily_price }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-6 col-md-4" style="width: 33%">
                                        <div class="form-group" style="float: left;width: 35%;">
                                            <label>Monthly Pricing: <span class="asterisk red">*</span></label><div style="clear:both"></div>
                                            <input id="noofmonths" class="Customdaymonthhour" data-format="M" data-template="M" name="no_of_month" value="{{ $pricing->no_of_month }}" type="text" style="width: 20%;float:left">
                                        </div>
                                        <div class="form-group" style="float: left;width: 65%;">
                                            <label></label> <div style="clear:both"></div>
                                            <div class="input-group" style="margin-top:5px">
                                                <span class="input-group-addon">$</span>
                                                <input class="form-control" placeholder="Monthly Price" type="text" name="monthly_price" onkeypress="return isNumber(event)" value="{{ $pricing->monthly_price }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option {{ ($pricing->status == 'Active') ? 'selected=selected' : '' }} value="Active">Active</option>
                                                <option {{ ($pricing->status == 'Inactive') ? 'selected=selected' : '' }}  value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('pricing/pricingList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
    $('#PricingForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            no_of_hours: {
                validators: {
                    notEmpty: {
                        message: 'The hours is required'
                    }
                }
            },
            hourly_price: {
                validators: {
                    notEmpty: {
                        message: 'The hours price is required'
                    }
                }
            },
            no_of_days: {
                validators: {
                    notEmpty: {
                        message: 'The days is required'
                    }
                }
            },
            daily_price: {
                validators: {
                    notEmpty: {
                        message: 'The daily price is required'
                    }
                }
            },
            no_of_month: {
                validators: {
                    notEmpty: {
                        message: 'The month is required'
                    }
                }
            },
            monthly_price: {
                validators: {
                    notEmpty: {
                        message: 'The monthly price is required'
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
   
    $('#PricingForm').find('[name="no_of_hours"]').change(function(e) {
        $('#PricingForm').bootstrapValidator('revalidateField', 'no_of_hours');
    }); 
    
    $('#PricingForm').find('[name="no_of_days"]').change(function(e) {
        $('#PricingForm').bootstrapValidator('revalidateField', 'no_of_days');
    }); 
    
    $('#PricingForm').find('[name="no_of_month"]').change(function(e) {
        $('#PricingForm').bootstrapValidator('revalidateField', 'no_of_month');
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
<script>
$(function(){
    $('#noofhour').combodate({
         customClass: 'Customdaymonthhour',
    });  
    $('#noofdays').combodate({
         customClass: 'Customdaymonthhour'
    });
    $('#noofmonths').combodate({
         customClass: 'Customdaymonthhour'
    });
});
</script>
@endpush