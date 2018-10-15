<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>QU | Login</title>
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo e(asset('css/ionicons.min.css')); ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo e(asset('css/AdminLTE.css')); ?>">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo e(asset('plugins/iCheck/square/blue.css')); ?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo e(url('/admin')); ?>"><b>QU</b></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <?php if(Session::has('message')): ?>
                <p class="alert alert-danger"><?php echo e(Session::get('message')); ?></p>
                <?php endif; ?>
                <?php if(Session::has('success')): ?>
                <p class="alert alert-success"><?php echo e(Session::get('success')); ?></p>
                <?php endif; ?>
                <form action="<?php echo e(url('/admin/login')); ?>" method="post" id="loginForm">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group has-feedback">
                       <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo e(!empty(Cookie::get('username')) ? Cookie::get('username') : ''); ?>"> 
                      <!--  <input type="email" class="form-control" placeholder="Email" name="email" value=""> -->
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember_me" id="remember_me" value="1"> Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <a href="#" data-toggle="modal" data-target="#forgotModal">I forgot my password</a><br>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <div class="modal modal-primary fade " id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
                    </div>
                    <div class="modal-body">
                        <form  id="forgotPasswordForm" action="<?php echo e(url('/admin/resetpassword')); ?>">
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group has-feedback">
                                <input type="email" class="form-control" placeholder="Email" name="email">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div> 
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- jQuery 2.2.3 -->
<script src="<?php echo e(asset('plugins/jQuery/jquery-2.2.3.min.js')); ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
<!-- iCheck -->
<script src="<?php echo e(asset('plugins/iCheck/icheck.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrapValidator.min.js')); ?>"></script>
<script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
$(document).ready(function () {
    $('#loginForm')
            .bootstrapValidator({
                excluded: [':disabled'],
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            }
                        }
                    }

                }
            });

    $('#forgotPasswordForm')
            .bootstrapValidator({
                excluded: [':disabled'],
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required'
                            }
                        }
                    }
                }
            });
            
          
});
</script>
</body>
</html>
