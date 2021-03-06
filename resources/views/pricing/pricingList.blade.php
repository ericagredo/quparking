@extends('layouts.master')
@section('title', 'QU - Manage Pricing')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pricing 
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Pricing</li>
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
                                        <label class="control-label">Status</label>
                                        <div class="" id="serStatus"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row contentpanel">
            <div class="col-xs-12 text-right margin-bottom">
                <div class="btn-group">
                    <button type="button" class="btn bg-olive">More Action</button>
                    <button type="button" class="btn bg-olive dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">More Action</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Active', '', 'all');">Active</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('InActive', '', 'all');">Inactive</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);"  onclick="deleteAll('all', '');">Delete</a></li>
                        <li class="divider"></li>
                    </ul>
                </div>
                    <a href="{{ url('pricing/createpricing') }}" class="btn btn-primary">Add Pricing</a>
            </div>
        </div>

        <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Pricing List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="PricingList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Number Of Hours</th>
                                        <th>Hourly Price</th>
                                        <th>Number Of Days</th>
                                        <th>Daily Price</th>
                                        <th>Number Of Months</th>
                                        <th>Monthly Price</th>
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
@endsection
@push('scripts')
<!-- /.content-wrapper -->
<script type="text/javascript">
    var selected = [];
    var status = '';

    var addEditSource = '/pricing/editpricing';
    var deleteAjaxSource = '/pricing/deletepricing';
    var activeInactiveAjaxSource = '/pricing/activeInactivepricing';
   
    $(document).ready(function () {
        dTable = $('#PricingList').dataTable({
            responsive: false,
            bJQueryUI: false,
            bProcessing: true,
            bServerSide: true,
            "bAutoWidth": false,
            iDisplayLength: 10,
            sAjaxSource: '/pricing/ajaxpricingList/',
            aoColumns: [
                {"sName": "id", "sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>", "mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},
                {"sName": "no_of_hours"},
                {"sName": "hourly_price"},
                {"sName": "no_of_days"},
                {"sName": "daily_price"},
                {"sName": "no_of_month"},
                {"sName": "monthly_price"},
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

                        if (row[7] == 'Active') {

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
                                html += '<a href="javascript:void(0)" class="fa fa-eye-slash" onclick="activeInactiveAll(\'' + active + '\',' + row[0] + ',\'' + single + '\');" title="Click to InActive Record"></a>&nbsp;&nbsp;';
                            }
                            if (status == 'Inactive') {
                                html += '<a href="javascript:void(0)" class="fa fa-eye" onclick="activeInactiveAll(\'' + inactive + '\',' + row[0] + ',\'' + single + '\')" title="Click to Active Record"></a>&nbsp;&nbsp;';
                            }
                        }
                        if (deleteAjaxSource) {
                            html += '<a href="javascript:void(0)" class="fa fa-trash" onclick="deleteAll(\'' + single + '\',' + row[0] + ')" title="Click to Delete Record"></a>&nbsp;&nbsp;';
                        }
                      
                        html += '</tr>';
                        html += '</table>';
                        return html;
                    },
                    "aTargets": [8]
                },
            ],
            sPaginationType: "full_numbers"});

        // ======== Seprate Search code =========//
        $('#PricingList').dataTable().columnFilter({
            //sPlaceHolder: "head:after",
            aoColumns: [
                null,null,null,null,null,null,null,
                {type: "select", sSelector: "#serStatus", values: ['Active', 'InActive'], ids: ['Active', 'InActive']}
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
        } else {
            selected.pop(chk);
        }
    }

    $('#PricingList_paginate ul li').on('click', function () {
        setTimeout(check_checkbox, 200);
    });

    function check_checkbox()
    {
        for (var i = 0; i < selected.length; i++) {
            $('#chk_' + selected[i]).prop('checked', true);

        }
    }
</script> 

@endpush