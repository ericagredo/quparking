<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Reset Password Form</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">

        <!-- Scripts -->
        <script>
            window.Laravel = <?php
                echo json_encode([
                    'csrfToken' => csrf_token(),
                ]);
                ?>
        </script>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
            </nav>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Reset Password</div>

                            <div class="panel-body">
                                <form class="form-horizontal" id="resetpasswordForm" role="form" method="POST" action="{{ url('api/changePassword') }}">
                                    <div class="form-group">
                                        <label for="password" class="col-md-4 control-label">New Password</label>
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div><input type="hidden" id="remember_token" name="remember_token" value="{{ $users->remember_token }}"></div>
                                    <div class="form-group">
                                        <label for="confirm_password" class="col-md-4 control-label">Confirm Password</label>
                                        <div class="col-md-6">
                                            <input id="confirm_password" type="password" class="form-control" name="confirm_password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Reset Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        <script src="/js/app.js"></script>
        <script src="{{ asset('js/bootstrapValidator.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#resetpasswordForm')
                        .bootstrapValidator({
                            excluded: [':disabled'],
                            fields: {
                                password: {
                                    validators: {
                                        notEmpty: {
                                            message: 'The password is required'
                                        },
                                        identical: {
                                            field: 'confirm_password',
                                            message: 'The password and its confirm are not the same'
                                        }
                                    }
                                },
                                confirm_password: {
                                    validators: {
                                        notEmpty: {
                                            message: 'The Confirm password is required'
                                        },
                                        identical: {
                                            field: 'password',
                                            message: 'The password and its confirm are not the same'
                                        }
                                    }
                                }

                            }
                        });
            });
        </script>
    </body>
</html>



