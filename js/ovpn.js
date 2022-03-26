$(document).ready(function () {
    
    showStatus();
    
    // Abilita il pulsante 'carica' se l'utente ha scelto un file da caricare
    $("#form-ovpncfg").on('change', function(event){
        filename=$(this).val();
        if(filename!==''){
            $("#uploadovpnbtn").attr('disabled', false);
        }
    });   
    
    // Caricamento nuova configurazione freeture 
    $("#ovpnCfgFileForm").on("submit", function(e) {
        e.preventDefault();
        var file = $("#form-ovpncfg")[0].files[0];
        var formData = new FormData();
        formData.append("configuration", file);

        $.ajax({
            url: "/lib/ovpn/V2/ovpn/editconfiguration",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                defaultSuccess("Configurazione caricata correttamente");
                $("#uploadovpnbtn").attr('disabled', true);
                $('#form-ovpncfg').val('');
                showStatus();
            }
            });
          
        });
    
});

function showStatus(){
     $.ajax({
            url: "/lib/ovpn/V2/ovpn/status",
            type: "GET",
            success: function (res) {
               var vpnStatus = JSON.parse(res).data;
                if(vpnStatus === 'Device "tun0" does not exist'){
                    vpnStatus = "OVPN not working";
                    $('#status-ovpn').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVA, rosso
                    $('#status-ovpn').text("VPN non attiva");
                } else {
                    vpnStatus = vpnStatus.substr(vpnStatus.indexOf('tun0')); 
                    $('#status-ovpn').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVA, verde
                    $('#status-ovpn').text("VPN Attiva");
                }         
                $('#status-ovpn-description').text(vpnStatus);
            }
            });
       
    /*
     $.get("/lib/ovpn/V2/ovpn/getstatus", function(data, status){
         var vpnStatus = data.data;
         if(vpnStatus === 'Device "tun0" does not exist'){
             vpnStatus = "OVPN not working";
             $('#status-ovpn-description').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVA, rosso
         } else {
             vpnStatus = vpnStatus.substr(vpnStatus.indexOf('tun0')); 
             $('#status-ovpn-description').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVA, verde
         }         
         $('#status-ovpn-description').text(vpnStatus);
      });*/
}

