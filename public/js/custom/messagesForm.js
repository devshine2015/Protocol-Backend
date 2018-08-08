$('#start_date').datepicker({
	minDate:new Date()
});
$('#end_date').datepicker({
	minDate:new Date()
});
// $('.selectpicker').selectpicker({
// 		container: 'panel-body',
//         size: 5
// });

$(document).ready(function() {
	// $("#textMessage").Editor();
});
$("#message_form").validate({
	 rules: {
        message_categories_id: {
            required: true,
        },
        message_type: {
            required: true,
        },
        start_date: {
            required: true,
        },
        end_date: {
            required: true,
        },
        message: {
            required: true,
        }
    },
    messages: {
        message_categories_id: {
            required: "Please select category",
        },
        message_type: {
            required: "Please select message type",
        },
        start_date: {
            required: "please select start date",
        },
        end_date: {
            required: "please select end date",
        },
        message: {
            required: "Please enter message",
        }
    }
});