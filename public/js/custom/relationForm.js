$(document).ready(function() {
	$('.selectpicker').selectpicker({
	 	size: 6
	});
	 $('.my-checkbox').bootstrapToggle();
	$("#category_form").validate({
		 rules: {
	        active_name: {
	            required: true,
	        },
	        passive_name: {
	            required: true,
	        }
	    },
	    messages: {
	    	active_name: {
	            required: "please enter active name",
	        },
	        passive_name: {
	            required: "Please enter passive name",
	        }
	    }
	});
});