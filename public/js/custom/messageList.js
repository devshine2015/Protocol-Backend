var messageListObj = null;
$(function() {
    messageListObj = $("#message-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : getMessageListURL,
        },
        columns: [
            { data: "message_categories_id", name: "message_categories_id"},
            { data: "message_type", name: "message_type"},
            { data: "start_date", name: "start_date"},
            { data: "end_date", name: "end_date"},
            { data: "language_type", name: "language_type"},
            { data: "message_criteria_id", name: "message_criteria_id"},
            { data: "criteria", name: "criteria"},
            { data: "editAction", name: "editAction" ,searchable: false}
        ],
        "ordering": false,
        "bSort" : false,
        "bLengthChange":false,
        "info": false,
        "bFilter":false,
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>'

    });
});
function messageSaveSuccess(data) {
    messageListObj.ajax.reload();
    $("#modal-right").modal("toggle");
}

function messageSaveError(jqXHR, textStatus, errorThrown) {
}
function messageDeleteSuccess(data) {
    messageListObj.ajax.reload();
}