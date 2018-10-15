<?php $__env->startSection('title', 'Park It - Manage Email Template'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Email Templates
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('/admin/dashboard')); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Email Templates</li>
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
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group nomargin">
                                    <label class="control-label">Subject</label>
                                    <div class="" id="sersubject"></div>
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
                </div>
            </div>
        </div>
        <div class="row hidden">
            <div class="col-xs-12 text-right margin-bottom">
                <div class="btn-group">
                    <button type="button" class="btn bg-olive">More Action</button>
                    <button type="button" class="btn bg-olive dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="min-width: 123px !important;">
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('Active', '', 'all');">Active</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" onclick="activeInactiveAll('InActive', '', 'all');">Inactive</a></li>
                        <li class="divider"></li>
                        <li class="custom_hide"><a href="javascript:void(0);"  onclick="deleteAll('all', '');">Delete</a></li>
                        <li class="divider custom_hide"></li>
                    </ul>
                </div>
                <a href="<?php echo e(url('/emailtemplate/createemailtemplate')); ?>" class="btn btn-primary custom_hide">Add Email Template</a> 
            </div>
        </div>
        <div class="contentpanel"></div>
        <!--Data Table-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Email Templates List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="emailTemplateList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <th></th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th width="50">Action</th>
                                </tr>
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
<script type="text/javascript">
    var selected = [];
    var status = '';
    var addEditSource = '/emailtemplate/editemailtemplate';
    var deleteAjaxSource = '/emailtemplate/delete';
    var activeInactiveAjaxSource = '/emailtemplate/activeInactiveStatus';
    var AjaxSource = '/emailtemplate/ajaxemailtemplateList';

    $(document).ready(function () {
        dTable  =  $('#emailTemplateList').dataTable({ 
                    responsive: false,
                    bJQueryUI: false,
                    bProcessing: true,
                    bServerSide: true,
                    "bAutoWidth": false,
                    //multipleSelection: true,
                    iDisplayLength: 10,
                    sAjaxSource: AjaxSource,
                    aoColumns: [
                            {"sName": "id","sTitle": "<input type='checkbox' id='checkall' name='checkall'></input>","mDataProp": null, "sWidth": "20px", "sDefaultContent": "<input type='checkbox' ></input>", "bSortable": false, "bSearchable": false},

                            {"sName": "subject"},
                            {"sName": "description"},   
                            {"sName": "status"},                        
                            {"sName": "id", "bSearchable": false, "bSortable": false}           
                               ],
                                    aoColumnDefs: [
                                    {
                                    "mRender": function ( data, type, full ) {
                                            return '<input type="checkbox" class="check" onClick="checked_chkbx(' + data[0] + ' )" value="'+ data[0] +'" id="chk_'+ data[0] +'"> ';
                                                                    },
                                    "aTargets": [ 0 ]
                                    },
                                    {
                                        "mRender": function ( data, type, row ) {
                                                if(row[3] == 'Active'){
                                                        status = 'Inactive';
                                                }else{
                                                        status = 'Active';
                                                } 

                                                var html = '';
                                                html += '<table border="0" style="width:150px;">';
                                                        html += '<tr>';
                                                                if(addEditSource){
                                                                        html += '<a href="'+addEditSource+'/'+row[0]+'" class="fa fa-edit" title="Edit"></a>&nbsp;&nbsp;';
                                                                }                            				
                                                                if (activeInactiveAjaxSource) {
                                                                    var active = 'Active';
                                                                    var inactive = 'Inactive';
                                                                    var single = 'single';
                                                                    /*if (status == 'Active') {
                                                                        html += '<a href="javascript:void(0)" class="fa fa-eye-slash" onclick="activeInactiveAll(\'' + active + '\',' + row[0] + ',\'' + single + '\');" title="Click to Active Record"></a>&nbsp;&nbsp;';
                                                                    }
                                                                    if (status == 'Inactive') {
                                                                        html += '<a href="javascript:void(0)" class="fa fa-eye" onclick="activeInactiveAll(\'' + inactive + '\',' + row[0] + ',\'' + single + '\')" title="Click to InActive Record"></a>&nbsp;&nbsp;';
                                                                    }*/
                                                                }  
                                                                if (deleteAjaxSource) {
                                                                    html += '<a href="javascript:void(0)" class="fa fa-trash-o custom_hide" onclick="deleteAll(\'' + single + '\',' + row[0] + ')" title="Click to Delete Record"></a>&nbsp;&nbsp;';
                                                                }
                                                    html += '</tr>';
                                                html += '</table>';
                                        return html;
                                        },
                                    "aTargets": [ 4 ]
                                    }
                                    ],           
                    sPaginationType: "full_numbers"
        });

        $('#emailTemplateList').dataTable().columnFilter({
            aoColumns: [
                null,
                {type: "text", sSelector: "#sersubject"},
                null,
                {type: "select", sSelector: "#serStatus", values: ['Active', 'Inactive'], ids: ['Active', 'Inactive']}
          ]
        });

        $("#checkall").click(function () {
            $(".check").prop('checked', $(this).prop('checked'));
        });

        $(document).on("click", ".check", function(t) {
                if($(".check").length == $(".check:checked").length) {
                     $("#checkall").prop("checked", true);
                }else {
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

    $('#emailTemplateList_paginate ul li').on('click', function () {
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