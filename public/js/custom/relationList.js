var relationListObj = null;
$(function() {
    relationListObj = $("#relation-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : getRelationListURL,
        },
        columns: [
            { data: "active_name", name: "active_name"},
            { data: "passive_name", name: "passive_name"},
            { data: "created_by", name: "created_by"},
            { data: "created_at", name: "created_at"},
            { data: "is_approved", name: "is_approved"},
            { data: "editAction", name: "editAction" ,searchable: false}
        ],
        columnDefs: [
            { width: 200, targets: 0 }
        ],
        "fnDrawCallback": function() {
            $(".my-checkbox").bootstrapToggle({'onColor': 'success'});
        },
        "ordering": false,
        "bSort" : false,
        "bLengthChange":false,
        "info": false,
        "bFilter":false,
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>'

    });
});
function callCheck(id) {
    $.ajax({
        url: 'relation-status'+'/'+id,
        data: {},
        dataType : 'json',
        success:function(data) {
            relationListObj.ajax.reload();
        }
    });
}


$.fn.dataTable.ext.errMode = "none";
function relationsSaveSuccess(data) {
    relationListObj.ajax.reload();
    $("#modal-right").modal("toggle");
}

function relationsSaveError(jqXHR, textStatus, errorThrown) {
}
function relationsDeleteSuccess(data) {
    relationListObj.ajax.reload();
}