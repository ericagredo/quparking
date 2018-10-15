@extends('layouts.master')
@section('title', 'QU - Add Promo Codes')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Add Promo Codes
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('promocode/promocodeList') }}">Manage Promo Codes</a></li>
                <li class="active">Add Promo Codes</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Promo Codes</h3>
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
                        <form role="form" id="Promocode_form" method="post" action="{{ url('/promocode/create') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Promo Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Promo Name" type="text" name="promo_name" minlength="3">
                                        </div>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>Discount <span class="asterisk red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control" placeholder="Discount" type="text" name="discount" value="" onkeypress="return isNumber(event)" maxlength="3">
                                                </div>
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
                                    <div style="clear:both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Start Date </label><span class="asterisk red">*</span>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="datepicker" name="promo_start_date">
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
                                                <input type="text" class="form-control pull-right" id="datepicker1" name="promo_end_date">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('promocode/promocodeList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
@endpush