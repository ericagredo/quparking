@extends('layouts.master')
@section('title', 'QU - Manage Admin Notification')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Admin Notification
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Admin Notification</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
       <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div id="success_message" style="color:#00A65A;font-weight: bolder;"></div>
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Admin Notification List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="staticpageList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Notification Title</th>
                                        <th>Notification Mode</th>
                                    </tr>
                                </thead>
                                @foreach($admin_notification as $notification)
                                <tr>
                                    <td>{{ $notification->notification_title }}</td>
                                    <td>
                                        <label class="switch">
                                            <?php 
                                                $checked = '';
                                                if($notification->notification_mode == 'ON'){
                                                    $checked = 'Checked="checked"';
                                                }
                                            ?>
                                            <input type="checkbox" name="notification_mode" value="{{ ($notification->notification_mode) }}" {{$checked}} id="notification_mode_{{$notification->id}}" onclick="javascript:Change_notification_mode({{ $notification->id}}, this.value)">
                                            <div class="slider round"></div>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection
@push('scripts')
<script type="text/javascript">
    function Change_notification_mode(id, value){
        $.ajax({
            type: "POST",
            url: '/adminnotification/updatenotification',
            data: "id=" + id + "&mode=" + value,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                   $('#success_message').html('Notification mode change successfully.');
                   setTimeout(function(){ $('#success_message').html(''); }, 3000);
                   $('#success_message').val('');
                }
            }
        });
    }
</script>
@endpush