@extends('layouts.master')
@section('title', 'QU - Edit App Settings')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                App Settings
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">App Settings</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            @if(Session::has('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">App Settings</h3>
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
                        <form role="form" id="AppForm" method="post" action="{{ url('/appsettings/update') }}" enctype="multipart/form-data">
                               {{ csrf_field() }}
                               <div class="box-body">
                                   <div class="row">
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>App Name <span class="asterisk red">*</span></label>
                                               <input class="form-control" placeholder="App Name" type="text" name="app_name" minlength="3" value="{{ !empty($app_setting->app_name) ? $app_setting->app_name : '' }}">
                                           </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>App Logo <span class="asterisk red">*</span></label>
                                               <input class="" placeholder="App Name" type="file" name="app_logo">
                                                @if($app_setting->app_logo != '')
                                                <img src="{{ asset('uploads/app_logo_images/'.$app_setting->app_logo) }}" width="100px" height="65px"> 
                                                @endif
                                           </div>
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-4">
                                           <div class="form-group">
                                               <label>Admin User<span class="asterisk red">*</span></label>
                                               <select class="form-control select2" style="width: 100%;" name="admin_users" >
                                                   <option value="">Choose Admin User</option>
                                                   <?php $AdminusersList = Helper::getAdminUserList(); ?>
                                                    @foreach($AdminusersList as $Adminuser)
                                                        <?php $selected = '' ?>
                                                        @if ($app_setting->tbl_adminuser_id == $Adminuser->id) 
                                                            <?php $selected = 'selected=selected' ?>
                                                        @endif   
                                                      <option value="{{$Adminuser->id}}" {{ $selected }}>{{$Adminuser->first_name.' '.$Adminuser->last_name}}</option>
                                                    @endforeach 
                                               </select>
                                           </div>
                                        </div>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                               <div class="box-footer">
                                   <a href="{{ url('appsettings/editappsettings') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
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
    $('#AppForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            app_name: {
                validators: {
                    notEmpty: {
                        message: 'The App Name is required'
                    }
                }
            },
            app_logo: {
                validators: {
                    file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    }
                }
            },
            admin_users: {
                validators: {
                    notEmpty: {
                        message: 'The Admin users is required'
                    }
                }
            },
        }
    });
</script>
@endpush