@extends('layouts.master')
@section('title', 'QU - Change Password')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Change Password
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">Change Password</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Change Password </h3>
                        </div>

                        <form role="form" id="changepasswordForm" method="post" action="{{ url('/admin/changepwdProfile') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Old Password <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="old password" value=""  type="password" name="old_password" minlength="6">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>New Psssword <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="New Password" value=""  type="password" name="new_password" minlength="6">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Confirm Password <span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Confirm Password" value="" type="password" name="confirm_password" minlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('admin/viewProfile') }}"><button class="btn btn-default pull-right" style="margin:0 0 0 5px" type="button">Cancel</button></a>
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
$(document).ready(function () {
    $('#changepasswordForm')
        .bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                old_password: {
                    validators: {
                        notEmpty: {
                            message: 'The old password is required'
                        }
                    }
                },
                new_password: {
                    validators: {
                        notEmpty: {
                            message: 'The New password is required'
                        }
                    }
                },
                confirm_password: {
                    validators: {
                        notEmpty: {
                            message: 'The Confirm password is required'
                        },
                        identical: {
                            field: 'new_password',
                            message: 'Confirm password does not match with new password'
                        }
                    }
                }
            }
        });
});
</script>
@endpush