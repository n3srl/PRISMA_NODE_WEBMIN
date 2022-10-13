$(document).ready(function () {
    
    showStatus();
    
});

// Show Prometheus status
function showStatus() {
    
    $.getJSON('http://' + window.location.hostname + '/lib/prometheus/V2/prometheus/node_exporter', function(res) {
            var node_exporter_metrics = "";
            
            if(node_exporter_metrics === '') {
                node_exporter_metrics = "";
                $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
                $('#status-prometheus').text("Servizio non attivo");
            } else {
                $('#status-prometheus').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVO, verde
                $('#status-prometheus').text("Servizio attivo");
            } 
            $('#status-prometheus-description').html(node_exporter_metrics);
      })
      .fail(function() { 
          $('#status-prometheus').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVO, rosso
          $('#status-prometheus').text("Servizio non attivo");
          $('#status-prometheus-description').html("");
      });
     
       
}

