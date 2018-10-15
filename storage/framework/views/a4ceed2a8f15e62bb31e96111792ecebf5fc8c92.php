<?php $__env->startSection('title', 'Park It - Manage Monthly Reports'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Monthly Reports
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Manage Monthly Reports</li>
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
                                        <label class="control-label">Name</label>
                                        <div class="" id="sername">  </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Contact Number</label>
                                        <div class="" id="sercontactnumber"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Payment Status</label>
                                        <div class="" id="serpaymentstatus"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Month</label>
                                        <div class="" id="sermonth"> </div>
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
            <div class="col-xs-12 text-right margin-bottom hidden">
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
                <a href="<?php echo e(url('parkingspot/createparkingspot')); ?>" class="btn btn-primary custom_hide">Add Parking Spot</a>
            </div>
        </div>

        <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Host List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="ParkingSpotList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Host Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Bank Name</th>
                                        <th>Bank Account Number</th>
                                        <th>Bank Routing Number</th>
                                        <th>Total Booking Amount</th>
                                        <th>Total Cancellation Fee</th>
                                        <th>Total Renter Cancellation Fee</th>
                                        <th>Total Additional Amount</th>
                                        <th>Total Refund Amount</th>
                                        <th>Total Commission Amount</th>
                                        <th>Total Amount</th>
                                        <th>Payment Status</th>
                                        <th>Month</th>
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
    var pay_amount_status = '';

    var addEditSource = '/parkingspot/editparkingspot';

    var ManageReceipt = '/reports/receiptGallery';
    var PayAmountStatusAjaxSource = '/reports/PayAmountStatusAjaxSource';


    $(document).ready(function () {
        dTable = $('#ParkingSpotList').dataTable({
            responsive: false,
            bJQueryUI: false,
            bProcessing: true,
            bServerSide: true,
            "bAutoWidth": false,
            iDisplayLength: 10,
            sAjaxSource: '/reports/AjaxReportsList/',
            aoColumns: [
                {"sName": "id", "sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>", "mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},
                {"sName": "name"},
                {"sName": "contact_number"},
                {"sName": "email"},
                {"sName": "bank_name"},
                {"sName": "bank_account_number"},
                {"sName": "bank_routing_number"},
                {"sName": "total_booking_amount"},
                {"sName": "cancellation_fee_by_host"},
                {"sName": "cancellation_fee_by_renter"},
                {"sName": "surcharge_amount"},
                {"sName": "refunded_amount"},
                {"sName": "admin_commission_amount"},
                {"sName": "total_amount"},
                {"sName": "payment_status"},
                {"sName": "report_by_month"},
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
                        if (row[14] == 'Pending') {
                            pay_amount_status = 'Funded';
                        } else {
                            pay_amount_status = 'Pending';
                        }

                        var html = '';

                        html += '<table border="0" style="width:150px;">';
                        html += '<tr>';

                        if (PayAmountStatusAjaxSource) {
                            var Funded = 'Funded';
                            var Pending = 'Pending';
                            var single = 'single';
                            if (pay_amount_status == 'Funded') {
                                html += '<a href="javascript:void(0)" class="fa fa-times" style="color:#D81421" onclick="pay_amount_status_update(\'' + Funded + '\',' + row[0] + ');" title="Click to update refund amount status"></a>&nbsp;&nbsp;';
                            }
                            if (pay_amount_status == 'Pending') {
                                html += '<a href="javascript:void(0)" class="fa fa-check"  style="color:#327E5C" onclick="pay_amount_status_update(\'' + Pending + '\',' + row[0] + ')" title="Click to update refund amount status"></a>&nbsp;&nbsp;';
                            }
                        }

                        if(ManageReceipt){
                            html += '<a href="'+ ManageReceipt +'/'+ row[0] +'" class="" title="Click to upload bank receipt">Upload Receipt</a>&nbsp;&nbsp;';
                        }

                        /*if (addEditSource) {
                            html += '<a href="' + addEditSource + '/' + row[0] + '" class="" title="Edit">&nbsp;View</a>&nbsp;&nbsp;';
                        }*/


                        html += '</tr>';
                        html += '</table>';
                        return html;
                    },
                    "aTargets": [16]
                },
            ],
            sPaginationType: "full_numbers"});

        // ======== Seprate Search code =========//
        $('#ParkingSpotList').dataTable().columnFilter({
            //sPlaceHolder: "head:after",
            aoColumns: [
                null,
                {type: "text", sSelector: "#sername"},
                {type: "text", sSelector: "#sercontactnumber"},
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                {type: "select", sSelector: "#serpaymentstatus", values: ['Pending', 'Funded'], ids: ['Pending', 'Funded']},
                {type: "datepicker", sSelector: "#sermonth"}

            ]
        });

        $('#ParkingSpotList_range_from_15').click(function () {
            $('.datepicker-orient-left').hide()
        });

        $('#ParkingSpotList_range_from_15').datetimepicker({
            minViewMode: 'months',
            viewMode: 'months',
            autoclose: true,
            pickTime: false,
            format: 'MMM-YYYY'
        });
        /*$('#sermonth').on('change', function() {
            //alert($("#sermonth input").val());
            //dTable.fnDraw(true);
        } );*/
        //$('#ParkingSpotList_range_from_15').Monthpicker();




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



  /*   */

    function checked_chkbx(chk)
    {
        if ($('#chk_' + chk).is(':checked')) {
            selected.push(chk);
        } else
        {
            selected.pop(chk);
        }
    }

    $('#ParkingSpotList_paginate ul li').on('click', function () {
        setTimeout(check_checkbox, 200);
    });

    function check_checkbox()
    {
        for (var i = 0; i < selected.length; i++) {
            $('#chk_' + selected[i]).prop('checked', true);

        }
    }

    function pay_amount_status_update(status, id){
        $.ajax({
            type: "POST",
            url: PayAmountStatusAjaxSource,
            data: "payment_status=" + status + "&id=" + id,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data == 1) {
                    VerificationstatusMessage();
                    dTable.fnDraw(true);
                }else {
                    dTable.fnDraw(true);
                }
            }
        });
    }
    
    function VerificationstatusMessage() {
        var successmsg = '<div class="alert alert-success">';
        successmsg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
        successmsg += 'Verification Code send mail to user successfully.';
        successmsg += '</div>';
        $("div.alert-success").remove();
        $("div.contentpanel").prepend(successmsg);
        setTimeout(function(){ $('.alert-success').remove(); }, 3000);
    }
</script> 

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>