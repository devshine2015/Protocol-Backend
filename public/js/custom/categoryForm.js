$(document).ready(function() {
	$('.selectpicker').selectpicker({
	 	size: 6
	});
	 $('.my-checkbox').bootstrapToggle();
	$("#category_form").validate({
		 rules: {
	        category_id: {
	            required: true,
	        },
	        name: {
	            required: true,
	        }
	    },
	    messages: {
	    	category_id: {
	            required: "please select category",
	        },
	        name: {
	            required: "Please enter name",
	        }
	    }
	});
});