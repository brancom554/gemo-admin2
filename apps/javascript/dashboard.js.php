<?php @header('Content-Type: text/javascript; charset=utf-8');?>
function scrollTop(){
	$("html, body").animate({
		scrollTop:0
	},"slow");
}

function showTab(tab){
	$('.nav-pills a[href="#' + tab + '"]').tab('show');
	scrollTop();
};

function loadUserProducts(divId,type,search){
	var url =  "/json/prList/<?php echo $_SESSION['customer']['contact_id'];?>";
	if($.trim(type)>"") url= url+"||"+$.trim(type);
	if($.trim(search)>'') url= url+"||"+$.trim(search)+"/";
	else url= url+"||/";
	$.ajax({
		cache: false,
		url: url,
		dataType: "json",
		success: function(data) {
			listUserProducts(data,divId);
		}
	});
}
function loadUserPayment(divId,type,search){
	var url =  "/json/paList/<?php echo $_SESSION['customer']['contact_id'];?>";
	if($.trim(type)>"") url= url+"||"+$.trim(type);
	if($.trim(search)>'') url= url+"||"+$.trim(search)+"/";
	else url= url+"||/";
	$.ajax({
		cache: false,
		url: url,
		dataType: "json",
		success: function(data) {
			listUserPayment(data,divId);
		}
	});
}

function listUserProducts(data,divId){
	$(divId).css('color', 'black');
	var html = [];  html.push( '' );  var nb =data.rows;
	if (nb >0) {
		var vls = data.data;
		for( var v, i = -1;  v = vls[++i]; ) {
			html.push(
				// '<tr border=0><td><a href="" class="prod" data-id="',v.productId,'" data-name="',v.libelle,'">',v.contractNum,' <i class="fa fa-search-plus"></i></a></td>'
			   '<tr border=0><td>',v.contractNum,'</td>'
				,'<td>',v.articleRef,'</td>'
				,'<td>',v.qty,'</td>'
			    ,'<td>',v.libelle,'</td>'
				,'<td class="text-right">',v.initialPrice,' &euro;</td>'
				,'</tr>'
				);
		}
	}
	else{
		html.push("<tr><td colspan='9'><?php echo $lang->trl('No result found for your search');?></td></tr>");
		$(divId).css('color', 'red');
	}
	html.push( '' );
	$(divId).empty().append(html.join('') ).show();
}

function listUserPayment(data,divId){
	$(divId).css('color', 'black');
	var html = [];  html.push( '' );  var nb =data.rows;
	var total =0;
	if (nb >0) {
		var vls = data.data;
		for( var v, i = -1;  v = vls[++i]; ) {
			html.push(
				// '<tr border=0><td><a href="" class="prod" data-id="',v.productId,'" data-name="',v.libelle,'">',v.contractNum,' <i class="fa fa-search-plus"></i></a></td>'
				'<tr border=0><td>',v.contractNum,'</td>'
				,'<td>',v.articleRef,'</td>'
				,'<td>',v.nbVente,'</td>'
				,'<td>',v.description,'</td>'
				,'<td>',v.salesDate,'</td>'
				,'<td>',v.paymentDate,'</td>'
				// ,'<td class="text-right">',v.paymentAmount,' &euro;</td>'
				,'</tr>'
				);
				// total+=parseInt( v.paymentAmount);
		}
	}
	else{
		html.push("<tr><td colspan='9'><?php echo $lang->trl('No result found for your search');?></td></tr>");
		$(divId).css('color', 'red');
	}
	html.push( '' );
	// console.log("total = "+total);
	$(divId).empty()
	// .append("Total : "+total)
	.append(html.join('') ).show();
}

function loadNavigationAction(){
	<?php if ($_SESSION['customer']['stock_active']=='O') { ?>
	loadUserProducts("tbody","P","");

	$('.nav a[href="#s1Tab"]').click(function (e) {
		loadUserProducts("tbody","P","");
		scrollTop();
	});

	$('.nav a[href="#s2Tab"]').click(function (e) {
		loadUserPayment("tbody","S","");
		scrollTop();
	});

<?php }
else{ ?>
		loadUserPayment("tbody","S","");

	$('.nav a[href="#s2Tab"]').click(function (e) {
		loadUserPayment("tbody","S","");
		scrollTop();
	});

<?php } ?>
	$('.nav a[href="#s2Tab"]').click(function (e) {
		loadUserPayment("tbody","S","");
		scrollTop();
	});


	$('.body').on('click', '.prod', function(e){
		e.preventDefault();
		var name = $(this).data('name');
	    var id = $(this).data('id');
		var url =  "/article_"+id+"_"+name;
		window.open(url, '_blank');

	});

}

$(document).ready(function(){
	loadNavigationAction();
});