var shareList = null;
$(function() {
    shareList = $("#inboxData").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : shareInboxUrl,
        },
        columns: [
            { data: "name", name: "name"},
        ],
        createdRow: function( row, data, dataIndex ) {
        // Set the data-status attribute, and add a class
        if(data.is_read == 0){
          $( row ).find('td:eq(0)')
            .addClass('addNotificationclr');
          }
        },
        "ordering": false,
        "bSort" : false,
        "bLengthChange":false,
        "info": false,
        "bFilter":false,
        "paging": false

    });
});