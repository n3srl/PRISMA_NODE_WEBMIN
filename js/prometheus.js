$(document).ready(function () {
    
    showStatus();
    
    // Enable upload button if user has chosen a file 
    $("#form-prometheuscfg").on('change', function(event){
        filename=$(this).val();
        if(filename!==''){
            $("#uploadprometheusbtn").attr('disabled', false);
        }
    });   
    
    // Upload new Prometheus configuration 
    $("#prometheusCfgFileForm").on("submit", function(e) {
        e.preventDefault();
        var file = $("#form-prometheuscfg")[0].files[0];
        var formData = new FormData();
        formData.append("configuration", file);
        });
        
    
});

// Show Prometheus status
function showStatus(){
    
    $.getJSON('http://' + window.location.hostname + ':9090/api/v1/status/runtimeinfo', function(res){            
            var vpnStatus = "";
            for (const [key, value] of Object.entries(res.data)) {
                vpnStatus += `${key}: ${value}<br>`;
            }
            if(vpnStatus === ''){
                vpnStatus = "";
                $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
                $('#status-prometheus').text("Servizio non attivo");
            } else {
                $('#status-prometheus').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVO, verde
                $('#status-prometheus').text("Servizio attivo");
            } 
            $('#status-prometheus-description').html(vpnStatus);
      })
      .fail(function() { 
          $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
          $('#status-prometheus').text("Servizio non attivo");
          $('#status-prometheus-description').html("");
      });
     
       
}

