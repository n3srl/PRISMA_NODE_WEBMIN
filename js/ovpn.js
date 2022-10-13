$(document).ready(function () {

    showStatus();

    // Enable upload button if user has chosen a file 
    $("#form-ovpncfg").on('change', function (event) {
        filename = $(this).val();
        if (filename !== '') {
            $("#uploadovpnbtn").attr('disabled', false);
        }
    });

    // Upload new ovpn configuration
    $("#ovpnCfgFileForm").on("submit", function (e) {
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
                console.log(res);
            }
        });

    });

});

// Show ovpn status
function showStatus() {
    $.ajax({
        url: "/lib/ovpn/V2/ovpn/status",
        type: "GET",
        success: function (res) {
            var vpnStatus = JSON.parse(res).data;
            if (vpnStatus === '') {
                $('#status-ovpn').css({'color': '#b52c1d', 'font-weight': 'bold'}); // Stato NON ATTIVA, rosso
                $('#status-ovpn').text("VPN non attiva");
            } else {
                $('#status-ovpn').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVA, verde
                $('#status-ovpn').text("VPN Attiva");
            }
            $('#status-ovpn-description').text(vpnStatus);
        }
    });

}

// Show network status
function showStatus() {
    $.ajax({
        url: "/lib/ovpn/V2/ovpn/net_status",
        type: "GET",
        success: function (res) {
            var vpnStatus = JSON.parse(res).data;
            $('#status-network-description').text(vpnStatus);
        }
    });

}

