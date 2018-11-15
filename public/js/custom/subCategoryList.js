var categoryListObj = null;
$(function() {
    categoryListObj = $("#category-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : getCategoryListURL,
        },
        columns: [
            { data: "category_id", name: "category_id"},
            { data: "name", name: "message_type"},
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
        url: 'change-status'+'/'+id,
        data: {},
        dataType : 'json',
        success:function(data) {
            categoryListObj.ajax.reload();
        }
    });
}


$.fn.dataTable.ext.errMode = "none";
function subCategoriesSaveSuccess(data) {
    categoryListObj.ajax.reload();
    $("#modal-right").modal("toggle");
}

function subCategoriesSaveError(jqXHR, textStatus, errorThrown) {
}
function subCategoriesDeleteSuccess(data) {
    categoryListObj.ajax.reload();
}