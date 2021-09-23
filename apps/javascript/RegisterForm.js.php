<?php @header('Content-Type: text/javascript; charset=utf-8');?>
var RegisterForm = function () {
    return {
        //Checkout Form
        initRegisterForm: function () {
	        // Validation
	        $('#sky-form').validate({
	            // Rules for form validation
	            rules:
	            {
	                email:
	                {
	                    required: true,
	                    email: true
	                },
	            	password:
	                {
	                    required: true
	                },
	            	nom_user:
	                {
	                    required: true
	                },
	            	prenom_user:
	                {
	                    required: true
	                },
	            	phone_number:
	                {
	                    required: true
	                },
	            	civilite:
	                {
	                    required: true
	                }
	            },

	            // Messages for form validation
	            messages:
	            {
	                email:
	                {
	                    required: '<?php echo $lang->trl("Please enter your email address");?>',
	                    email: '<?php echo $lang->trl("Please enter a VALID email address");?>'
	                },
	                password:
	                {
	                    required: '<?php echo $lang->trl("Please enter your password");?>'
	                },
	                phone_number:
	                {
	                    required: '<?php echo $lang->trl("Please enter your phone number");?>'
	                },
	                nom_user:
	                {
	                    required: '<?php echo $lang->trl("Please enter your name");?>'
	                },
	                prenom_user:
	                {
	                    required: '<?php echo $lang->trl("Please enter your firstname");?>'
	                },
	            },

	            // Do not change code below
	            errorPlacement: function(error, element)
	            {
	                error.insertAfter(element.parent());
	            }
	        });
        }

    };

}();


