<?php @header('Content-Type: text/javascript; charset=utf-8');?>
function scrollTop(){
	$("html, body").animate({
		scrollTop:0
	},"slow");
}


function searchButton(){
	$("#searchBtn").click(function(){
		if($('#mag_key').val()){
			if($('#keyword').val()){
				goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase()+'/search/' + $('#keyword').val().toLowerCase();	
			}
			else{
				goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase();
				
			}
			window.location = goUrl;
			return false;  // Prevent the default form behaviour -->
		}
    	else{
			goUrl = '<?php echo $iniObj->siteUrl;?>search/' + $('#keyword').val().toLowerCase();
			window.location = goUrl;
			return false;  // Prevent the default form behaviour -->
		}
	}
	);

// enter key
	$('input').keydown(function(e) {
	    if (e.keyCode == 13) {
			if($('#mag_key').val()){
				if($('#keyword').val()){
					goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase()+'/search/' + $('#keyword').val().toLowerCase();	
				}
				else{
					goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase();
				}
				window.location = goUrl;
				return false;  // Prevent the default form behaviour -->
			}
			else{
				goUrl = '<?php echo $iniObj->siteUrl;?>search/' + $('#keyword').val().toLowerCase();
	        	window.location = goUrl;
	        	return false;  // Prevent the default form behaviour -->
			}
	    }
	});

}

function searchMagasinButton(){
	$("#searchBtnMag").click(function(){
    	goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#store').val().toLowerCase()+'/search/' + $('#keyword').val().toLowerCase();
        window.location = goUrl;
        return false;  // Prevent the default form behaviour -->
	}
	);

	// enter key
	$('input').keydown(function(e) {
    if (e.keyCode == 13) {
    	goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#store').val().toLowerCase()+'/search/' + $('#keyword').val().toLowerCase();
        window.location = goUrl;

    }
});

}


function selectMagasin(){
	$("#mag_key").change(function(){
		if($('#mag_key').val() && !$('#keyword').val()){
			goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase();			
		}else 
		if($('#mag_key').val() && $('#keyword').val()){
			goUrl = '<?php echo $iniObj->siteUrl;?>magasin_' + $('#mag_key').val().toLowerCase()+'/search/' + $('#keyword').val().toLowerCase();	
		}
		window.location = goUrl;
		return false;  // Prevent the default form behaviour -->
	});
}

$(document).ready(function(){
	searchButton();
	searchMagasinButton();
	selectMagasin();

});