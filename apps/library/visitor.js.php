<?php @header('Content-Type: text/javascript; charset=utf-8');?>
var LANG="<?php echo $_SESSION['LANG'];?>";
function shows(w){ window.status=w;return true;}
function clear(){window.status='';}//Clear windows status

// Change the cursor for this object
function pointer( c ) {c.style.cursor = 'pointer';}
// Visit the URL provided in Parameter
function lUrl( url ) {    window.location = url; }

function adslash( str ) {
    // source http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_addslashes/
    return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}
//  check for valid numeric strings
function IsNumeric(v){
   var str = "0123456789.-"; var s; var blnR = true;
   if (v.length == 0) {return false;}
   //  test strString consists of valid characters listed above
   var vL=v.length;
   for (i = 0; i < vL && blnR == true; i++){
      s = v.charAt(i);
      if (str.indexOf(s) == -1) {blnR = false;}
   }
   return blnR;
}

// Custom jQuey plugins
// debug variable  / source : http://docs.jquery.com/Plugins/Authoring

 jQuery.fn.debug = function() {
  return this.each(function(){
   alert(this);
  });
};

jQuery.log = function(message) {
  if(window.console) {console.debug(message);}
  else { alert(message);}
};



customRange = function(inp) {
  return {minDate: (inp.id == 'dTo' ? getDate($('#start_date').val()) : null),
    maxDate: (inp.id == 'dFrom' ? getDate($('#end_date').val()) : null)};
};


// navigate between multiple divs
pagePN=function(c,p){
    var a = $("."+c); // Current class selected
    var aId = $("."+c).attr('id'); // Current ID selected
     if (a.next()){ var nId=a.next().attr('id');} // Next div ID
     if (a.prev()){  var pId= a.prev().attr('id');}// previous div ID
    if ((p=='n') && nId){ //next page requested
        $("#"+aId).removeClass(c).fadeOut("fast",function(){   $("#"+nId).addClass(c).fadeIn("fast");   });
    }
    if ((p=='p') && pId){ //previous page requested
        $("#"+aId).removeClass(c).fadeOut("fast",function(){ $("#"+pId).addClass(c).fadeIn("fast");   });
    }
};



dateInit=function(){
  $('#start_date').calendar();$('#end_date').calendar();
};


// this function toggle an image plus.png and minus.png
jQuery.toggleImg = function(i){
	var	src= $("img#"+i).attr("src");
	if (src == '<?php echo $iniObj->siteIcon;?>plus.png'){ return '<?php echo $iniObj->siteIcon;?>minus.png';}
	else{ return '<?php echo $iniObj->siteIcon;?>plus.png';}
}


var updatePage = function() {
	if ($('#protected').val()=='Y' ){  $('#protection').show("slow");}
    else {$('#protection').hide("slow");}
}

// Change the period info for the DIV
var sPe=function(dId,t){
	$("#"+dId).empty().append(t);
}


$(document).ready(function(){
/*
  var autocomplete = $('input[data-provide="typeahead"]').typeahead()
    .on('keyup',
      function(ev){
		    $(this)
		    ev.stopPropagation();
		    ev.preventDefault();
		    if( $.inArray(ev.keyCode,[40,38,9,13,27]) === -1 ){
			    var self = $(this);
			    self.data('typeahead').source = [];
			    if( !self.data('active') && self.val().length> 0){
			      self.data('active', true);
			      var current = $(this).attr('value');
			      var rnd = Math.random();  var cmpU ='/<?php echo $lib->lang;?>/json/sCont/<?php echo $_SESSION['customer']['address_id'];?>||'+current+"/"+rnd;
			      $.getJSON( cmpU, function(d) {
			        self.data('active',true);
			        //Filter out your own parameters. Populate them into an array, since this is what typeahead's source requires
			        var arr = [], i=d.rows;
			        while(i--){
			          arr[i] = d.data[i].text
			        }
			      self.data('typeahead').source =arr;
			      self.trigger('keyup');
			      self.data('active', false);
			      });
			      self.data('active', false);
			    }
		    }
	    }
    )
;
*/
//Css button. Source http://monc.se/kitchen/59/scalable-css-buttons-using-png-and-background-colors/
 $('.btn').each(function(){
 	var b = $(this);
 	var tt = b.text() || b.val();
 	/*
 	 *This was preventing the <a href> to be clicked ==> onClick function did not work
 	if ($(':submit,:button',this)) {
 		b = $('<a>').insertAfter(this). addClass(this.className).attr('id',this.id);
 		$(this).remove();
 	}
 	*/
 	b.text('').css({cursor:'pointer'}). prepend('<i></i>').append($('<span>').
 	text(tt).append('<i></i><span></span>'));
});

// pseudo : source : http://bassistance.de/2007/01/23/unobtrusive-clear-searchfield-on-focus/
	$('#protected').change(updatePage);
    // select the text of a component
	selectTxt=function(i){		$('#'+i).select();	}

	viewImage=function (i,n){
    // i is the ID of the item -  n is image_type
		$("#imgPreview").empty().append("<img src='<?php echo $iniObj->siteImage;?>/loading_orange.gif' alt='<?php echo $lang->trl(115);?>' /><?php echo $lang->trl(528);?>");
		$("#imgPreview").empty().append("<img class='left' alt='' src='/imgView/"+i+"/"+n+"/medium' />");
	}

	$("#image_id").change(function(){
		$("#imgPreview").empty().append("<img src='<?php echo $iniObj->siteImage;?>/loading_orange.gif' alt='<?php echo $lang->trl(115);?>' /><?php echo $lang->trl(115);?>");
		$("#imgPreview").empty().append("<img class='left' alt='' src='/imgView/"+$(this).val()+"//medium' />");
	});

	$("#loginLink").bind('click',function(){$("#login").toggle('slow');});

	$("input#accept").click(function(){
		if ($(this).is(":checked")) { $("#artistSubmit").show();}
		else { $("#artistSubmit").hide();}
	});

});
var tP = 0;

function l(dId){
  // dId -> div ID
$("#"+dId).empty().append("<img src='<?php echo $iniObj->siteImage;?>/loading_orange.gif' alt='<?php echo $lang->trl(115);?>' /><?php echo $lang->trl(528);?>");}

// call server asynchronously Load Json doc
  /*
    rt = request type
    url = value
	  dId = Object Name // div id
	  cN = Component name (html component to use
	  rP = request page
  */

/* Load all JSon Requests */
function lJson(rT,url,dId,cN,rP){
  var rnd = Math.random();  var cmpU ='/<?php echo $lib->lang;?>/json/'+rT;
  if(url> ''){ cmpU = cmpU+"/"+url+"/"+rnd;}
  $.getJSON( cmpU, function(json) { hJson(json,dId,cN,rT,url,rP); });
}

/*Handles all Json requestes */
function hJson(d,dId,cN,rT,url,rP){
  /* d->Data Json / rT-> Request Type, rP-> Request page, sV-> Selected Val */
  switch (rT){
    case "sCont": JsonC(d,dId,cN); break;
    case "cList": JsonC(d,dId,cN); break;
    case "track": JsonT(d,dId,cN); break;
    case "sList": JsonS(d,dId,cN); break;
    case "sAbook": JsonA(d,dId,cN); break;
  }
}

/* Handle contacts display */
function JsonC(d,dId,cN){
  var html = [];  html.push( '' );  var nb =d.rows;
  if (nb >0) {
      if (nb ><?php echo $iniObj->customerMaxResult;?>){
        html.push('<?php echo $lang->trl('There are more than '. $iniObj->customerMaxResult.' contacts in your history. Please refine your search');?>');
      }
      else{
		    var vls = d.data;
		    for( var v, i = -1;  v = vls[++i]; ) {
		        html.push(
		        '<li>'
		        ,'<input class="iradio" type="radio" name="'+ cN +'" id="'+ cN +'" value="'+v.id +'">'
		        ,v.text
		        ,'</li>'
		        );
		    }
      }
    }
    else{
      html.push('<?php echo $lang->trl('No result found for your search or within your history');?>');
      html.push(' <a href="/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/newAddress"><?php echo $lang->trl('Add new address');?></a>');
    }
    html.push( '' );
    $("#"+dId).empty().append(html.join('') );
}

/* Handle Tracking display */
function JsonT(d,dId,cN){
  var html = [];
   html.push("<td colspan='8'><br /><a class='close'><button class='btn btn-danger btn-mini'><?php echo $lang->trl('Close'); ?></button></a>",
   "<h3><?php echo $lang->trl('Tracking Results'); ?> : ",dId.substring(1) ,"</h3>",
            "<table class='table table-striped table-bordered'><thead>",
            "<tr><th><?php echo $lang->trl('Date'); ?></th><th><?php echo $lang->trl('Time'); ?></th><th><?php echo $lang->trl('Status'); ?></th></tr></thead>");
    var nb =d.rows;
  if (nb >0) {
        var vls = d.data;
        for( var v, i = -1;  v = vls[++i]; ) {
            html.push('<tr><td>'  ,v.date  ,'</td><td>' ,v.time ,'</td><td>' ,v.status ,'</td></tr>');
        }
  }
  else{
      html.push('<?php echo $lang->trl('No result found for your search or within your history');?>');
  }
  html.push( '</div>' );
   if ($("#"+dId).is(":hidden")) {
  $("#"+dId).empty()
  .append(html.join('') ).fadeIn('slow');
 }
  else{
  $("#"+dId).hide();
  }
}

/* Handle Shipping display */
function JsonS(d,dId,cN){
  var html = [];html.push("");
  var nb =d.rows;
  if (nb >0) {
    var vls = d.data;
    for( var v, i = -1;  v = vls[++i]; ) {
      html.push("<tr><td ><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.id," <i class='glyphicon glyphicon-zoom-in'></i></a></td>",
        "<td><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.agent_awb,"</a></td>",
        "<td><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.pickup_date,"</a></td>",
        "<td><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.adSource,"</a></td>",
        "<td><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.adShipto,"</a></td>",
        "<td><a href='javascript:sD(",v.id,")' class='push' title='<?php echo $lang->trl('Details'); ?>'>",v.delivery_date,"</a></td>",
        "<td><center><a href='javascript:trc(",v.id,",\"",v.tkey,"\")' class='push'  title='<?php echo $lang->trl('Tracking'); ?>'><i class='glyphicon glyphicon-road'></i></a></center></td>",
        "<td><center><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/print/ship/",v.id,"' target='_new' title='<?php echo $lang->trl('Print'); ?>'><i class='glyphicon glyphicon-print'></i></a></center></td>",
        "</tr>",
        "<tr id='d",v.id,"'  style='display: none'></tr>"
      );
    }
  }
  else{
    html.push('<tr><td colspan="8" align="center"><?php echo addslashes($lang->trl('No result found for your search or within your history'));?></td></tr>');
  }
  $("#"+dId).empty().append(html.join('') );
}


/* Handle Tracking display */
function trc(id,k){ lJson('track',k,"d"+id,'');}


/*Retrieve shipping detail data and display */
function sD(id) {
  var html = [];
  var dDiv = $("#d" + id);
  if (dDiv.is(":hidden")) {
  var rnd = Math.random();  var cmpU ='/<?php echo $lib->lang;?>/json/dS/'+id;
  $.getJSON(cmpU,null,
    // this will be run if successful
    function(d) {
      var nb =d.rows;
      if (nb >0) {
        var vls = d.data;
        for( var v, i = -1;  v = vls[++i]; ) {
          html.push("<td colspan='8'><br /><a class='close'><button class='btn btn-danger btn-mini'><?php echo $lang->trl('Close'); ?></button></a>",
          "<table style='width:100%; border: 1px solid black;'> ",
            "<tr> ",
            "<th style='width:50%;border: 1px solid black;'><?php echo $lang->trl('Shipper');?></th> ",
            "<th style='width:50%;border: 1px solid black;'><?php echo $lang->trl('Consignee');?></th> ",
            "</tr> ",
            "<tr> ",
            "<td valign='top' style='border: 1px solid black;'> ",
            "<center><strong>", v.id,"</strong></center><strong>",v.shipperCompany,"</strong><br />",v.adSource ,
            (v.shipperContact ? '<br /><strong><?php echo $lang->trl('Contact');?> : '+ v.shipperContact : '') ,"</strong>",
            (v.pickupInstructions ? '<br /><strong> <?php echo addslashes($lang->trl('Pickup instructions'));?></strong>: '+ v.pickupInstructions : '') ,
            "<br /><strong><?php echo addslashes($lang->trl('Pickup date'));?></strong> : ", v.pickupDate," - ", v.pickupTime,
            (v.content ? '<br /><strong><?php echo $lang->trl('Content');?> </strong>: '+ v.content : '') ,
            (v.weight ? '<br /><strong><?php echo $lang->trl('Weight');?> </strong>: '+ v.weight : '') ,
            (v.nbItems ? '<br /><strong><?php echo $lang->trl('Nb items');?> </strong>: '+ v.nbItems : '') ,
            ((v.insuranceValue && v.insuranceValue !='0' ) ? '<br /><strong><?php echo $lang->trl('Insurance value');?> </strong>: '+ v.insuranceValue : '') ,
            ((v.agentAwb && v.agentAwb !='0' ) ? '<br /><strong><?php echo $lang->trl('Your order reference');?> </strong>: '+ v.agentAwb : '') ,
            "     </td> ",
            "     <td valign='top' style='border: 1px solid black;'> ",
            "       <strong>", v.consigneeCompany,"</strong> "+"<br />", v.adShipto,
            (v.deliveryContact ? '<br /><strong><?php echo $lang->trl('Contact');?></strong> : '+ v.deliveryContact : '') ,
            (v.deliveryInstructions ? '<br /><strong> <?php echo addslashes($lang->trl('Delivery instructions'));?></strong>: '+ v.deliveryInstructions : '') ,
            "       <br /><strong><?php echo $lang->trl('Delivery date');?></strong> : ", v.expectedDeliveryDate," - ", v.expectedDeliveryTime,
            "     </td> ",
            "   </tr> ",
            "<tr><td colspan='2'><i class='glyphicon glyphicon-envelope'></i> <?php echo addslashes($lang->trl('Send a message to '.$iniObj->companyShortName.' for this shipment'));?> ",
            "<div align='left' ><input type='hidden' id='iTkey' value='", v.tKey , "' /><textarea class='col-md-5'  rows='2' id='", v.id,"' /> ",
            " <button class='btn btn-primary btn-small postM' ><?php echo $lang->trl('Send');?></button><label id='sp", v.id,"'></label>",
            "</div></td></tr>",
            "<tr><td colspan='2'><div id='trk",v.tKey,"' align='center'></div> ",
            " <script>lJson('track','",v.tKey,"','trk",v.tKey,"','');</script> ",
            "</td></tr>",
            " </table>",
            "</td>"
          );
					dDiv.empty()
						.append(html.join('') )
						.fadeIn('slow')
        }
      }
    }
  );
  }else {
    dDiv.hide();
  }
}

/* Handles all messages send for tracking */
$(document).on("click",".postM", function () {
  var tMsg = encodeURIComponent($.trim($(this).siblings('textarea').val()));
  var sId = encodeURIComponent($(this).siblings('textarea').attr('id'));
  var mess = $(this).siblings('label').attr('id');
  var tKey = $.trim($(this).siblings('input').val());
	if (tMsg>''){
	  $.ajax({
	    type: "POST",
	    url: "/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/pMess/",
	    data: { txt:tMsg, sId:sId},
	    success:function( msg ) {
	      if(msg == 'true') {
	        $("#"+mess).css('color', 'green').text('<?php echo addslashes($lang->trl('Your message was sent'));?>');
	        $("#"+sId).val('');
	        lJson('track',tKey,'trk'+tKey,'');
	      } else {
	        $("#"+mess).css('color', 'red').text('<?php echo addslashes($lang->trl('Your message could not be sent. Please try again'));?>');
	      }
	     }
	  });
	  }else{
	   $("#"+mess).css('color', 'red').text('Your message cannot be empty');
	   $("#"+sId).val('');
	  }
});

/* Fix the close button issue on all component*/
$(document).on("click",".close",function(){
    $(this).parent().toggle();
  }
);

/* Handle Address Book display */
function JsonA(d,dId,cN){
  var html = [];html.push("");
  var nb =d.rows;
  if (nb >0) {
        var vls = d.data;
        for( var v, i = -1;  v = vls[++i]; ) {
         if(v.userCreator=="<?php echo $_SESSION['customer']['contact_id']?>"){
          html.push("<tr title='<?php echo $lang->trl('Update an address'); ?>'>",
            " <td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.company," <i class='glyphicon glyphicon-pencil'></i></a></td>",
            "<td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.phone,"</a></td>",
            "<td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.email,"</a></td>",
            "<td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.address,"</a></td>",
            "<td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.zip," - ",v.city,"</a></td>",
            "<td><a href='/<?php echo $lib->lang;?>/<?php echo $iniObj->serviceName;?>/uAddress/",v.ad_id,"' target='_top'>",v.country,"</a></td>",
            "</tr>");	
          }
          else {
            html.push("<tr><td >",v.company,"</td>",
              "<td>",v.phone,"</td>",
		          "<td>",v.email,"</td>",
		          "<td>",v.address,"</td>",
		          "<td>",v.zip," - ",v.city,"</td>",
		          "<td>",v.country,"</td>",
		          "</tr>");
          }
        }
    }
    else{
      html.push('<tr><td colspan="8" align="center"><?php echo $lang->trl('No result found for your search or within your history');?></td></tr>');
    }
    html.push( '</div>' );
    $("#"+dId).empty().append(html.join('') );
}

