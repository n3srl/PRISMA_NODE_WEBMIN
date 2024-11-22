$(document).ready(function () {
    
    showStatus();
    
});

// Show Prometheus status
function showStatus() {
    
    $.get('http://' + window.location.hostname + '/lib/prometheus/V2/prometheus/node_exporter', function(data, status) {
            var node_exporter_metrics = data;
            
            if(node_exporter_metrics === '' || status !== "success") {
                node_exporter_metrics = "";
                $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
                $('#status-prometheus').text(_("Servizio non attivo"));
            } else {
                $('#status-prometheus').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVO, verde
                $('#status-prometheus').text(_("Servizio attivo"));
                
                $('#status-prometheus-description').html(node_exporter_metrics);
            }
      })
      .fail(function() { 
          $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
          $('#status-prometheus').text(_("Servizio non attivo"));
          $('#status-prometheus-description').html("");
      });
     
       
}

