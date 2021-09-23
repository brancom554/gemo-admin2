<?php @header('Content-Type: text/javascript; charset=utf-8');?>
var LoginForm = function () {
    return {
        //Checkout Form
        initLoginForm: function () {
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
	                }
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

function showEr(m){
	if($.trim(m)){
		$("#sp").css('color', 'red').html(m);
	}else{
		$("#sp").css('color', 'red').html("");
	}
}

/* check all errors on form */
function checkSubmit(){
	eC = 0; var mess="";

	if(!$.trim($("#email").val()) || !$.trim($("#password").val())) {
		m="<?php echo $lang->trl('Please enter your email address and your password for your authentication');?>";
		eC = 1;
	}
	if(eC==0){
		$("#sp").css('color', 'green').html("");
		return true;
	}
	else {
		$("#s5Login").html("<?php echo $lang->trl('Login Now');?> <i class='fa fa-user'></i>")
		.attr("disabled", false);
		showEr(m);
		return false;
	}
}

function checkSubmitPassword(){
	eC = 0; var mess="";

	if(!$.trim($("#email").val()) ) {
		m="<?php echo $lang->trl('Please enter your email address in order to reset your password');?>";
		eC = 1;
	}
	if(eC==0){
		$("#sp").css('color', 'green').html("");
		return true;
	}
	else {
		$("#pReset").html("<?php echo $lang->trl('Forgot Password');?> <i class='fa fa-user'></i>")
		.attr("disabled", false);
		showEr(m);
		return false;
	}
}


function loginUser(){
	var d1= encodeURIComponent($("input[name='email']").val());
	var d2= encodeURIComponent($("input[name='password']").val());
	$.ajax({
		type: "POST",
		url: "/customer/cLogin/",
		data: { email : d1 ,password : d2 },
		success:function( msg ) { loginHandle(msg) }
	});
}

function resetUserPassword(){
	var d1= encodeURIComponent($("input[name='email']").val());
	$.ajax({
		type: "POST",
		url: "/customer/pReset/",
		data: { email : d1 },
		success:function( msg ) { passwordHandle(msg) }
	});
}

function passwordHandle(result){
	var val=result.split('||');
	if( val[0]=="false") {
		$("#sp").css('color', 'red').html(val[1]);
		$("#pReset").attr("disabled", false)
		.html("<?php echo $lang->trl('Forgot Password');?> <i class='fa fa-unlock'></i>");
		$("#pReset").removeAttr("disabled");
	}
	else if(val[0]=="true") {
		$("#pReset").attr("disabled", false)
		.html("<?php echo $lang->trl('Forgot Password');?> <i class='fa fa-unlock'></i>");
		$("#sp").css('color', 'green').html(val[1]);
	}
}

function loginHandle(result){
	var val=result.split('||');
	if( val[0]=="false") {
		$("#sp").css('color', 'red').html(val[1]);
		$("#s5Login").attr("disabled", false)
		.html("<?php echo $lang->trl('Login Now');?> <i class='fa fa-user'></i>");
		$("#s5Login").removeAttr("disabled");
	}
	else if( val[0]=="true") {
		$("#s5Login").attr("disabled", false)
		.html("<?php echo $lang->trl('Login Now');?> <i class='fa fa-user'></i>");
		rnd = Math.random();
		window.location.href = '/<?php echo $iniObj->serviceName;?>/'+rnd;
	}
}





$(document).ready(function(){
	$("#s5Login").click(function(){
			if(checkSubmit()){
			$("#s5Login").html("<?php echo $lang->trl('Checking your credentials. Please wait...');?> <i class='fa fa-spinner fa-spin'></i>")
			.attr("disabled", true);
			loginUser();
		}
		});

		$("#pReset").click(function(){
			if(checkSubmitPassword()){
			$("#pReset").html("<?php echo $lang->trl('Checking your e-mail. Please wait...');?> <i class='fa fa-spinner fa-spin'></i>")
			.attr("disabled", true);
			resetUserPassword();
		}
		});
});


