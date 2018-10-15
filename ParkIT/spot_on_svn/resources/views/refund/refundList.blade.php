@extends('layouts.master')
@section('title', 'QU - Manage Booking Refund')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Booking Refund
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Booking Refund</li>
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
                                        <div class="" id="serfirst_name">  </div>

                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Booking Id</label>
                                        <div class="" id="serbookingid"> </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group nomargin">
                                        <label class="control-label">Amount Status</label>
                                        <div class="" id="seramountstatus"> </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if(Session::has('message'))
        <p class="alert alert-danger">{{ Session::get('message') }}</p>
        @endif
        @if(Session::has('success'))
        <p class="alert alert-success">{{ Session::get('success') }}</p>
        @endif
        <div class="row contentpanel">
            <div class="col-xs-12 text-right margin-bottom">
                <div class="btn-group">
                    <button type="button" class="btn bg-olive">Bank Receipt Status</button>
                    <button type="button" class="btn bg-olive dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Bank Receipt Status</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="min-width: 125px !important;">
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Pending', '', 'all');">Pending</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Accepted', '', 'all');">Accepted</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Rejected', '', 'all');">Rejected</a></li>
                        <!--<li class="divider"></li>
                        <li><a href="javascript:void(0);"  onclick="deleteAll('all', '');">Delete</a></li>
                        <li class="divider"></li>-->
                    </ul>
                </div>
                <a href="{{ url('parkingspot/createparkingspot') }}" class="btn btn-primary custom_hide">Add Parking Spot</a>
            </div>
        </div>

        <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Cancelled Booking List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="refundList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Bank Name</th>
                                        <th>Bank Account Number</th>
                                        <th>Bank Routing Number</th>
                                        <th>Booking Id</th>
                                        <th>Booking Date</th>
                                        <th>Refund Amount</th>
                                        <th>Amount Status</th>
                                        <th>Bank Receipt Status</th>
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
@endsection

@push('scripts')
<!-- /.content-wrapper -->
<script type="text/javascript">
    var selected = [];
    var status = '';
    var refund_amount_status = '';

    var activeInactiveAjaxSource = '/refund/AjaxBankReceiptStatus';
    var ManageReceipt = '/refund/receiptGallery';
    var RefundAmountStatusAjaxSource = '/refund/RefundAmountStatusAjaxSource';


    $(document).ready(function () {
        dTable = $('#refundList').dataTable({
            responsive: false,
            bJQueryUI: false,
            bProcessing: true,
            bServerSide: true,
            "bAutoWidth": false,
            iDisplayLength: 10,
            sAjaxSource: '/refund/AjaxRefundsList/',
            aoColumns: [
                {"sName": "id", "sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>", "mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},
                {"sName": "name"},
                {"sName": "bank_name"},
                {"sName": "bank_account_number"},
                {"sName": "bank_routing_number"},
                {"sName": "generated_booking_id"},
                {"sName": "booking_date"},
                {"sName": "refund_amount"},
                {"sName": "refund_amount_status"},
                {"sName": "upload_bank_receipt_status"},
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
                        if (row[8] == 'Pending') {
                            refund_amount_status = 'Funded';
                        } else {
                            refund_amount_status = 'Pending';
                        }

                        var html = '';

                        html += '<table border="0" style="width:150px;">';
                        html += '<tr>';

                        if (activeInactiveAjaxSource) {
                            var active = 'Active';
                            var inactive = 'Inactive';
                            var single = 'single';
                            if (status == 'Active') {
                                html += '<a href="javascript:void(0)" class="fa fa-eye-slash" onclick="activeInactiveAll(\'' + active + '\',' + row[0] + ',\'' + single + '\');" title="Click to InActive Record"></a>&nbsp;&nbsp;';
                            }
                            if (status == 'Inactive') {
                                html += '<a href="javascript:void(0)" class="fa fa-eye" onclick="activeInactiveAll(\'' + inactive + '\',' + row[0] + ',\'' + single + '\')" title="Click to Active Record"></a>&nbsp;&nbsp;';
                            }
                        }


                        if (RefundAmountStatusAjaxSource) {
                            var Funded = 'Funded';
                            var Pending = 'Pending';
                            var single = 'single';
                            if (refund_amount_status == 'Funded') {
                                html += '<a href="javascript:void(0)" class="fa fa-times" style="color:#D81421" onclick="refund_amount_status_update(\'' + Funded + '\',' + row[0] + ');" title="Click to update refund amount status"></a>&nbsp;&nbsp;';
                            }
                            if (refund_amount_status == 'Pending') {
                                html += '<a href="javascript:void(0)" class="fa fa-check"  style="color:#327E5C" onclick="refund_amount_status_update(\'' + Pending + '\',' + row[0] + ')" title="Click to update refund amount status"></a>&nbsp;&nbsp;';
                            }
                        }

                        if(ManageReceipt){
                            html += '<a href="'+ ManageReceipt +'/'+ row[0] +'" class="" title="Click to upload bank receipt">Upload Receipt</a>&nbsp;&nbsp;';
                        }

                        html += '</tr>';
                        html += '</table>';
                        return html;
                    },
                    "aTargets": [10]
                },
            ],
            sPaginationType: "full_numbers"});

        // ======== Seprate Search code =========//
        $('#refundList').dataTable().columnFilter({
            //sPlaceHolder: "head:after",
            aoColumns: [
                null,
                {type: "text", sSelector: "#serfirst_name"},
                null,
                null,
                null,
                {type: "text", sSelector: "#serbookingid"},
                null,
                null,
                {type: "select", sSelector: "#seramountstatus", values: ['Pending', 'Funded'], ids: ['Pending', 'Funded']},
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

    $('#ParkingSpotList_paginate ul li').on('click', function () {
        setTimeout(check_checkbox, 200);
    });

    function check_checkbox()
    {
        for (var i = 0; i < selected.length; i++) {
            $('#chk_' + selected[i]).prop('checked', true);

        }
    }

    function refund_amount_status_update(status, id){
     $.ajax({
            type: "POST",
            url: RefundAmountStatusAjaxSource,
            data: "refund_amount_status=" + status + "&id=" + id,
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

@endpush