$(document).ready(function() {
	$('#start_date').datepicker({
		startDate: new Date(),
		autoclose: true
        }).on('changeDate', function (selected) {
        	$('#end_date').val("");
        	$('#start_date').removeClass('error');
		    var startDate = new Date(selected.date.valueOf());
    		$('#end_date').datepicker('setStartDate', startDate);
	});
	$('#end_date').datepicker({
		startDate: new Date(),
		autoclose: true
    }).on('changeDate', function (selected) {
    	$('#end_date').removeClass('error');
    	var endDate = new Date(selected.date.valueOf());
    	// $('#end_date').datepicker('setStartDate', endDate);
	});
	$('.selectpicker').selectpicker({
	 	size: 4
	});
	$(".messageType, .criteriaCategory, .messageCriteria").hide();
	$("#message_category").on("change", function() {
		$(".messageType").hide();
		$("#message_type").removeClass('required');
		$(".criteriaCategory, .messageCriteria").hide();
		$("#message_category, #criteria").removeClass('required');
		if (this.value === "4"){
			$(".messageType").show();
			$("#message_type").addClass('required');
		}
	});
	$("#message_type").on("change", function() {
		$(".criteriaCategory, .messageCriteria").hide();
		$("#message_category, #criteria").removeClass('required');
		if (this.value === "2"){
			$(".criteriaCategory, .messageCriteria").show();
			$("#message_category, #criteria").addClass('required');
		}
	});
	jQuery.validator.setDefaults({
		  // This will ignore all hidden elements alongside `contenteditable` elements
		  // that have no `name` attribute
		  ignore: ":hidden, [contenteditable='true']:not([name])"
		});

		// $("#textMessage").Editor();
		$('#textMessage').summernote({
			dialogsInBody: true,
		});

	$("#message_form").validate({
		 rules: {
	        message_categories_id: {
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
	 //    errorPlacement: function(error, element) {
		//     if(element.hasClass('messageCategory')) {
		//         error.insertAfter(".bootstrap-select");
		//     }
		//     else {
		//         error.insertAfter(element);
		//     }
		// },
	    messages: {
	        message_categories_id: {
	            required: "please select category",
	        },
	        message_type: {
	            required: "please select type",
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
	// summernote validation
});