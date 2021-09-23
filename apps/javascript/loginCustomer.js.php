<?php @header('Content-Type: text/javascript; charset=utf-8');?>

$(document).ready(function(){
          $("#s5LoginCustomer").click(function(){
              $("#s5LoginCustomer").html("Vérification en cours. Veuillez patienter  ... <i class='fa fa-spinner fa-spin'></i>")
              .attr("disabled", true);
              loginCustomer();
          });
        });

        function loginCustomer(){
          var contact_num= encodeURIComponent($("input[name='contact_num']").val());
          var password= encodeURIComponent($("input[name='password']").val());
          
          $.ajax({
            type: "POST",
            url: "/customer/cLogin/",
            data: { contact_num : contact_num, password : password},
            success:function( msg ) {
              var val=msg.split('||');
              if( val[0]=="false") {
                $("#error_message").html(val[1]);
                $("#s5LoginCustomer").html("Se connecter <i class='fa fa-user'></i>")
                .attr("disabled", false);
              }
              else if( val[0]=="true") {
                $("#success_message").html("Authentication réussie.");
                $("#error_message").html("");
                $("#s5LoginCustomer").html("Login réussie. <i class='fa fa-check'></i>")
                .attr("disabled", true);

                var myCartArray = shoppingCart.listCart();
                if(myCartArray.length >0){
                  rnd = Math.random();
                  window.location.href = '<?php echo $iniObj->siteUrl;?>checkout/'+ rnd;
                } else{
                  rnd = Math.random();
                 window.location.href = '/customer/dashboard/'+ rnd;
                }
              }
            },
            error : function(resultat, statut, erreur){
              $("#s5LoginCustomer").html("Se connecter <i class='fa fa-user'></i>")
                .attr("disabled", false);
              $("#error_message").html("Une erreur s'est produite. Veuillez réessayer. <i class='fa fa-stop-circle'></i>");
              console.log('Resultat : '+ resultat);
              console.log('Statut : '+ statut);
              console.log('Erreur : '+ erreur);
            }
          });
        }
