@extends('layouts.master')
@section('title', 'QU - Add Admin Users')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Add Admin User
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('admin/adminusers') }}">Admin User</a></li>
                <li class="active">Add User</li>
            </ol>
            
            @if ( (Session::has('SUCCESS'))&&(Session::get('SUCCESS')=="TRUE") )
            <p class="alert alert-success" role="alert" style="margin-top: 20px;">
                    <!--strong>Well done!</strong-->
                    @if ( (Session::has('MESSAGE'))&&(Session::get('MESSAGE')!="") )
                        {{Session::get('MESSAGE')}}
                    @endif
                </p>
            @endif
            @if ( (Session::has('SUCCESS'))&&(Session::get('SUCCESS')=="FALSE") )
                <p class="alert alert-danger" role="alert" style="margin-top: 20px;">
                    <!--strong>Oh snap!</strong-->
                    @if ( (Session::has('MESSAGE'))&&(Session::get('MESSAGE')!="") )
                        {{Session::get('MESSAGE')}}

                    @endif
                </p>
            @endif
            <?php Session::forget('SUCCESS'); Session::forget('MESSAGE'); ?>
        </section>
        
        
            
           
        
        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Admin Users</h3>
                        </div>
                           <form role="form" id="FormControl" method="post" action="{{ url('/admin/create') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>First Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="First Name" id="firstname" onkeyup="TrooTech.only('alpha','firstname')" type="text" name="firstname" minlength="3">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Last Name <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Last Name" type="text" name="last_name" id="last_name" onkeyup="TrooTech.only('alpha','firstname')" minlength="3">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Email Address <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Email Address" type="text" name="email_address"  onblur="javascript:CheckAdminEmailExist(this.value, '')">
                                            <div id="error_message_email" class="help-block" style="color:#dd4b39"></div>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Password <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Password" type="password" name="password" minlength="8">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Confirm Password<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Confirm Password" type="password" name="cnfpassword" minlength="8">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose One</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('admin/adminusers') }}"><button class="btn btn-default pull-right" style="margin:0 0 0 5px" type="button">Cancel</button></a>
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
    
    
     function CheckAdminEmailExist(value, id) {
        
        $.ajax({
            type: "POST",
            url: '/admin/CheckAdminEmailExist',
            data: "email_address=" + value + "&id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#error_message_email').html('Email already Exist');
                   setTimeout(function(){ $('#error_message_email').html(''); }, 3000);
                   $('#email_address').val('');
                }
            }
        });
    }
</script>    
@endpush