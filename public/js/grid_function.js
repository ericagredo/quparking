$(document).ready(function () {
     $("#checkall").click(function () {
     var selected = new Array();
     $(dTable.fnGetNodes()).find(':checkbox').each(function () {
     $this = $(this);
     if ($('#checkall').is(':checked')) {
     $this.attr('checked', 'checked');
     selected.push($this.val());
     } else {
     $this.removeAttr('checked');
     selected.pop($this.val());
     }
     });
     // convert to a string
     var mystring = selected.join();
     //alert(mystring);
     }); 

    
});


function activeInactiveAll(status, ids, mode) {
    var selected = new Array();
    $(dTable.fnGetNodes()).find(':checkbox').each(function () {
        $this = $(this);
        if ($(this).is(':checked')) {
            selected.push($this.val());
        }
    });

    if (mode == 'all') {
        var ids = selected.join();
    }

    if (ids != '') {
        $.ajax({
            type: "POST",
            url: activeInactiveAjaxSource,
            // data: "id=" + ids + "&status=" + status + "&mode=" + mode + "&_token=<?php echo csrf_token(); ?>",
            data: "id=" + ids + "&status=" + status + "&mode=" + mode,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                statusMessage();

                dTable.fnDraw(true);
                $('#checkall').prop('checked', false);

            }
        });
    } else {
        alert('Please select at least one record.');
    }
}


function deleteAll(mode, ids) {
    var selected = new Array();
    $(dTable.fnGetNodes()).find(':checkbox').each(function () {
        $this = $(this);
        if ($(this).is(':checked')) {
            selected.push($this.val());
        }
    });
    if (mode == 'all') {
        var ids = selected.join();
    }
    if (ids != '') {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                type: "POST",
                url: deleteAjaxSource,
                data: {"id": ids},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data == 1) {
                        deleteMessage();
                        $('#checkall').prop('checked', false);
                    }
                    dTable.fnDraw(true);
                }
            });
        }
        dTable.fnDraw();
    } else {
        alert('Please select at least one record.');
    }
}
/*function deleterecord(id) {
 
 if (confirm("Are you sure you want to delete?")) {
 $.ajax({
 type: "POST",
 url: deleteAjaxSource,
 data: "id=" + id,
 success: function(data) {
 if (data == 1) {
 dTable.fnDraw(true);
 deleteMessage();
 $('#checkall').prop('checked', false);
 }
 }
 });
 }
 }*/

/*function activerecord(id, status) {
 $.ajax({
 type: "POST",
 url: activeAjaxSource,
 data: "id=" + id + "&status=" + status,
 success: function(data) {
 if (data == 1) {
 dTable.fnDraw(true);
 statusMessage();
 $('#checkall').prop('checked', false);
 }
 }
 });
 }*/

function deleteMessage() {
    var delmsg = '<div class="alert alert-success">';
    delmsg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
    delmsg += 'Data has been deleted successfully.';
    delmsg += '</div>';
    $("div.alert-success").remove();
    $("div.contentpanel").prepend(delmsg);
}

function statusMessage() {
    var delmsg = '<div class="alert alert-success">';
    delmsg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
    delmsg += 'Status has been changed successfully.';
    delmsg += '</div>';
    $("div.alert-success").remove();
    $("div.contentpanel").prepend(delmsg);
}

function activeInactiveCMPAll(status) {
    var selected = new Array();
    $(dTable.fnGetNodes()).find(':checkbox').each(function () {
        $this = $(this);
        if ($(this).is(':checked')) {
            selected.push($this.val());
        }
    });
    var ids = selected.join();
    if (ids != '') {
        $.ajax({
            type: "POST",
            url: activeAjaxSource,
            data: "id=" + ids + "&status=" + status + "&mode=all",
            success: function (data) {
                if (data == 1) {
                    statusMessage();

                    dTable.fnDraw(true);
                    $('#checkall').prop('checked', false);
                } else {
                    alert('Please upload contract copy.');
                }
            }
        });
    } else {
        alert('Please select at least one record.');
    }
}