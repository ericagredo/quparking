<?php $__env->startSection('title', 'Park It - Manage Users'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Users
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Users</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Search -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Search</h3>
                    </div>
                    <form role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">User Name</label>
                                        <div class="" id="serusername">  </div>
                                    </div>
                                </div>
                                <!--<div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">First Name</label>
                                        <div class="" id="serfirst_name">  </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Last Name</label>
                                        <div class="" id="serlast_name">  </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Username</label>
                                        <div class="" id="serusername">  </div>
                                    </div>
                                </div>-->
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Email</label>
                                        <div class="" id="seremail"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Location</label>
                                        <div class="" id="serlocation">  </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Status</label>
                                        <div class="" id="serStatus"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Contact Number</label>
                                        <div class="" id="sercontactnumber"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if(Session::has('message')): ?>
        <p class="alert alert-danger"><?php echo e(Session::get('message')); ?></p>
        <?php endif; ?>
        <?php if(Session::has('success')): ?>
        <p class="alert alert-success"><?php echo e(Session::get('success')); ?></p>
        <?php endif; ?>
        <div class="row contentpanel">
            <div class="col-xs-12 text-right margin-bottom">
                <div class="btn-group">
                    <button type="button" class="btn bg-olive">More Action</button>
                    <button type="button" class="btn bg-olive dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">More Action</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="min-width: 125px !important;">
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Active', '', 'all');">Active</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('InActive', '', 'all');">Inactive</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);"  onclick="deleteAll('all', '');">Delete</a></li>
                        <li class="divider"></li>
                    </ul>
                </div>
                <a href="<?php echo e(url('users/createadminUsers')); ?>" class="btn btn-primary custom_hide">Add Users</a>
            </div>
        </div>

        <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Users List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="UsersList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>User Name</th>
                                        <!--<th>First Name</th>-->
                                        <!--<th>Last Name</th>-->
                                        <!--<th>Username</th>-->
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Contact Number</th>
                                        <th>Profile Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var selected = [];
    var status = '';

    var addEditSource = '/users/editadminUsers';
    var deleteAjaxSource = '/users/deleteadminUsers';
    var activeInactiveAjaxSource = '/users/activeInactiveadminUsers';


    $(document).ready(function () {
        dTable = $('#UsersList').dataTable({
            responsive: false,
            bJQueryUI: false,
            bProcessing: true,
            bServerSide: true,
            "bAutoWidth": false,
            iDisplayLength: 10,
            sAjaxSource: '/users/ajaxadminUsersList/',
            aoColumns: [
                {"sName": "id", "sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>", "mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},
                /*{"sName": "firstname"},
                {"sName": "lastname"},*/
                {"sName": "username"},
                {"sName": "email"},
                {"sName": "location"},
                {"sName": "status"},
                {"sName": "contact_number"},
                {"sName": "profile_image"},
                /* {"sName": "location"}, */
                {"sName": "id", "bSortable": false, "bSearchable": false}
            ],
            aoColumnDefs: [
                {
                    "mRender": function (data, type, full) {
                        return '<input type="checkbox" name="usercheck" class="checkbox case" onClick="checked_chkbx(' + data[0] + ')" value="' + data[0] + '" id="chk_' + data[0] + '"> ';
                    },
                    "aTargets": [0]
                },
                {
                    "mRender": function (data, type, row) {

                        if (row[4] == 'Active') {

                            status = 'Inactive';
                        } else {
                            status = 'Active';
                        }

                        var html = '';

                        html += '<table border="0" style="width:150px;">';
                        html += '<tr>';
                        if (addEditSource) {
                            html += '<a href="' + addEditSource + '/' + row[0] + '" class="fa fa-edit" title="Edit"></a>&nbsp;&nbsp;';
                        }

                        if (activeInactiveAjaxSource) {
                            var active = 'Active';
                            var inactive = 'Inactive';
                            var single = 'single';
                            if (status == 'Active') {
                                html += '<a href="javascript:void(0)" class="fa fa-eye-slash" onclick="activeInactiveAll(\'' + active + '\',' + row[0] + ',\'' + single + '\');" title="Click_to_InActive_Record"></a>&nbsp;&nbsp;';
                            }
                            if (status == 'Inactive') {
                                html += '<a href="javascript:void(0)" class="fa fa-eye" onclick="activeInactiveAll(\'' + inactive + '\',' + row[0] + ',\'' + single + '\')" title="Click_to_Active_Record"></a>&nbsp;&nbsp;';
                            }
                        }
                        if (deleteAjaxSource) { 
                            html += '<a href="javascript:void(0)" class="fa fa-trash" onclick="deleteAll(\'' + single + '\',' + row[0] + ')" title="Click_to_Delete_Record"></a>&nbsp;&nbsp;';
                        }

                        html += '</tr>';
                        html += '</table>';
                        return html;
                    },
                    "aTargets": [7]
                },
                {
                    "mRender": function (data, type, row) {
                        return '<img src="' + data + '" width="50px"/>';
                    },
                    "aTargets": [6]
                }
            ],
            sPaginationType: "full_numbers"});

        // ======== Seprate Search code =========//
        $('#UsersList').dataTable().columnFilter({
            //sPlaceHolder: "head:after",
            aoColumns: [
                null,
                /*{type: "text", sSelector: "#serfirst_name"},
                {type: "text", sSelector: "#serlast_name"},*/
                {type: "text", sSelector: "#serusername"},
                {type: "text", sSelector: "#seremail"},
                {type: "text", sSelector: "#serlocation"},    
                {type: "select", sSelector: "#serStatus", values: ['Active', 'InActive'], ids: ['Active', 'InActive']},
                {type: "text", sSelector: "#sercontactnumber"},
                null,
                ]
        });

        $("#checkall").click(function () {
            $(".case").prop('checked', $(this).prop('checked'));
        });

        $(document).on("click", ".case", function (t) {
            if ($(".case").length == $(".case:checked").length) {
                $("#checkall").prop("checked", true);
            } else {
                $("#checkall").prop("checked", false);
            }
        });


    });

    function checked_chkbx(chk)
    {
        if ($('#chk_' + chk).is(':checked')) {
            selected.push(chk);
        } else
        {
            selected.pop(chk);
        }
    }

    $('#UsersList_paginate ul li').on('click', function () {
        setTimeout(check_checkbox, 200);
    });

    function check_checkbox()
    {
        for (var i = 0; i < selected.length; i++) {
            $('#chk_' + selected[i]).prop('checked', true);

        }
    }


</script> 

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>