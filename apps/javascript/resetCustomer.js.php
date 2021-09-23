<?php @header('Content-Type: text/javascript; charset=utf-8');?>

$(document).ready(function(){
  $("#s5ResetCustomer").click(function(){
      $("#s5ResetCustomer").html("Vérification en cours. Veuillez patienter  ... <i class='fa fa-spinner fa-spin'></i>")
      .attr("disabled", true);
      resetCustomer();
  });
});

function resetCustomer(){
  var email= encodeURIComponent($("input[name='email']").val());
        
    $.ajax({
        type: "POST",
        url: "/customer/pReset/",
        data: { email : email},
        success:function( msg ) {
        var val=msg.split('||');
          console.log(msg);
        if( val[0]== "false") {
          $("#error_message").html(val[1]);
          $("#s5ResetCustomer").html("Réinitialiser le compte <i class='fa fa-user'></i>")
          .attr("disabled", false);
        }
        else if( val[0]== "true") {
          $("#success_message").html("Réinitialisation effectuée. Veuillez bien vouloir consulter votre email.");
          $("#error_message").html("");
          $("#s5ResetCustomer").html("Réinitialisation effectuée <i class='fa fa-check'></i>")
          .attr("disabled", true);
          rnd = Math.random();
          window.location.href = '/customer/login/'+ rnd;
        }
      },
      error : function(resultat, statut, erreur){
        $("#s5ResetCustomer").html("Réinitialiser le compte <i class='fa fa-user'></i>")
        .attr("disabled", false);
        $("#error_message").html("Une erreur s'est produite. Veuillez réessayer. <i class='fa fa-stop-circle'></i>");
          console.log('Resultat : '+ resultat);
          console.log('Statut : '+ statut);
          console.log('Erreur : '+ erreur);
      }
    });
}