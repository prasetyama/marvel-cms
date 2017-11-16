$(document).ready(function(){
	
	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();

	// Form Validation insert company
    $("#company_validate").validate({
		rules:{
			company_name:{required:true},
			company_address:{required:true},
			company_phone:{required:true}
		},
		submitHandler: function(form) {
			$("#btn").hide();
			$("#loading").show();
			var formData = new FormData(form);

			var url = "/company/post";

			if($("#form_type").val() == "add"){
				url = "/company/post";
			}

			if($("#form_type").val() == "edit"){
				url = "/company/update";
			}

         	$.ajax({
            	type: "POST",
            	url: url,
            	data: formData,
            	success: function() {
            		$("#btn").show();
					$("#loading").hide();
            		alert('Yeeah.. You did it!!!');

            		if($("#form_type").val() == "add"){
						$("#company_validate")[0].reset();
					}

					if($("#form_type").val() == "edit"){
						window.location.reload(true);
					}
            		
            	},
            	error: function() {
            		$("#btn").show();
					$("#loading").hide();
            		alert('Urmhh.. You failed. Try again later!!!');
            	},
            	contentType: false,
        		processData: false
          	});
      	},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
});