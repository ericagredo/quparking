<?php $__env->startSection('title', 'Park It - Manage Country'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Country
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Country</li>
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
                                        <label class="control-label">Country Name</label>
                                        <div class="" id="sercountryname"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Country Code</label>
                                        <div class="" id="sercountrycode"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Status </label>
                                        <div class="" id="serStatus"> </div>
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
                <a href="<?php echo e(url('country/createNewCountry')); ?>" class="btn btn-primary">Add Country</a>
            </div>
        </div>
        <!--Data Table-->
        <div id="success_message" style="color:#00A65A;font-weight: bolder;"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Country List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <table id="CountryList" class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Country Name</th>
                                        <th>Country Code</th>
                                        <th>Status</th>
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

    var addEditSource = '/country/editCountry';
    var deleteAjaxSource = '/country/deletecountry';
    var activeInactiveAjaxSource = '/country/activeInactivecountry';

    $(document).ready(function () {
       
        dTable = $('#CountryList').dataTable({
            responsive: false,
            bJQueryUI: false,
            bProcessing: true,
            bServerSide: true,
            "bAutoWidth": false,
            iDisplayLength: 10,
            sAjaxSource: '/country/AjaxCountryList/',
            aoColumns: [
                {"sName": "id", "sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>", "mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},
                {"sName": "country_name"},
                {"sName": "country_code"},
                {"sName": "status"},
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
                        if (row[3] == 'Active') {

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

                        var single = 'single';
                        if (deleteAjaxSource) { 
                            html += '<a href="javascript:void(0)" class="fa fa-trash" onclick="deleteAll(\'' + single + '\',' + row[0] + ')" title="Click_to_Delete_Record"></a>&nbsp;&nbsp;';
                        }

                        html += '</tr>';
                        html += '</table>';
                        return html;
                    },
                    "aTargets": [4]
                },
            ],
            sPaginationType: "full_numbers"});

        // ======== Seprate Search code =========//
        $('#CountryList').dataTable().columnFilter({
            //sPlaceHolder: "head:after",
            aoColumns: [
                null,
                {type: "text", sSelector: "#sercountryname"},
                {type: "text", sSelector: "#sercountrycode"},
                {type: "select", sSelector: "#serStatus", values: ['Active', 'Inactive'], ids: ['Active', 'Inactive']}
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

    $('#ContactUsList_paginate ul li').on('click', function () {
        setTimeout(check_checkbox, 200);
    });

    function check_checkbox()
    {
        for (var i = 0; i < selected.length; i++) {
            $('#chk_' + selected[i]).prop('checked', true);

        }
    }
    
    function Replytouser(id) {
        $('#ContactUsList_processing').show();
        $.ajax({
            type: "POST",
            url: '/contactus/replytousercontactus',
            data: "id=" + id,
            async: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#ContactUsList_processing').hide();
                if (data == 1) {
                   $('#success_message').html('Reply to user send Successfully.');
                   setTimeout(function(){ $('#success_message').html(''); }, 3000);
                   $('#success_message').val('');
                   
                }
            }
        });
    }
</script> 
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>