$(document).ready(function () {
    
    showStatus();
    
    // Abilita il pulsante 'carica' se l'utente ha scelto un file da caricare
    $("#form-prometheuscfg").on('change', function(event){
        filename=$(this).val();
        if(filename!==''){
            $("#uploadprometheusbtn").attr('disabled', false);
        }
    });   
    
    // Caricamento nuova configurazione freeture 
    $("#prometheusCfgFileForm").on("submit", function(e) {
        e.preventDefault();
        var file = $("#form-prometheuscfg")[0].files[0];
        var formData = new FormData();
        formData.append("configuration", file);
        
        /*
        $.ajax({
            url: "/lib/prometheus/V2/prometheus/editconfiguration",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                defaultSuccess("Configurazione caricata correttamente");
                $("#uploadprometheusbtn").attr('disabled', true);
                $('#form-prometheuscfg').val('');
                showStatus();
                console.log(res);
            }
            });
          */
        });
        
    
});

function showStatus(){
    /*
     $.ajax({
            url: "/lib/prometheus/V2/prometheus/status",
            type: "GET",
            success: function (res) {
                var vpnStatus = JSON.parse(res).data;
                if(vpnStatus === ''){
                    vpnStatus = "";
                    $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVA, rosso
                    $('#status-prometheus').text("Servizio non attivo");
                } else {
                    vpnStatus = vpnStatus.substr(vpnStatus.indexOf('tun0')); 
                    $('#status-prometheus').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVA, verde
                    $('#status-prometheus').text("Servizio attivo");
                }         
                $('#status-prometheus-description').text(vpnStatus);
            }
            });
     */
       
}

