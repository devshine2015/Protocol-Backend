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
		$("#message_criteria, #criteria").removeClass('required');
		if (this.value === "4"){
			$(".messageType").show();
			$("#message_type").addClass('required');
		}
	});
	$("#message_type").on("change", function() {
		$(".criteriaCategory, .messageCriteria").hide();
		$("#message_criteria, #criteria").removeClass('required');
		if (this.value === "2"){
			$(".criteriaCategory, .messageCriteria").show();
			$("#message_criteria, #criteria").addClass('required');
		}
	});
	

	// $("#textMessage").Editor();
	$('#textMessage').summernote({
		dialogsInBody: true,
	});
	var summernoteElement = $('#textMessage');
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
        ignore: ':hidden:not(#textMessage),[contenteditable="true"]:not([name]),.note-editable.card-block',
	    errorPlacement: function(error, element) {
		    if(element.prop('id') == 'message_category'){
		    	error.insertAfter(".messageCategory .dropdown-toggle");
		    }else if(element.prop('id') == 'message_type'){
		    	error.insertAfter(".messageType .dropdown-toggle");
		    }
		    else if(element.prop('id') == 'message_criteria'){
		    	error.insertAfter(".criteriaCategory .dropdown-toggle");
		    }else if (element.prop("type") === "checkbox") {
                error.insertAfter(element.siblings("label"));
            }else if (element.hasClass("summernote")) {
                error.insertAfter(element.siblings(".note-editor"));
		    }else{
		    	error.insertAfter(element);
		    }
		},
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
	        },
	        message_criteria_id: {
	            required: "Please select criteria",
	        }
	    }
	});
	// summernote validation
	summernoteElement.summernote({
        height: 300,
        callbacks: {
            onChange: function (contents, $editable) {
                // Note that at this point, the value of the `textarea` is not the same as the one
                // you entered into the summernote editor, so you have to set it yourself to make
                // the validation consistent and in sync with the value.
                summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);

                // You should re-validate your element after change, because the plugin will have
                // no way to know that the value of your `textarea` has been changed if the change
                // was done programmatically.
                summernoteValidator.element(summernoteElement);
            }
        }
    });
});