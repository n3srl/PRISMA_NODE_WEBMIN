$(document).ready(function () {

    showStatus();
    showWiredNetworkStatus();

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
                defaultSuccess(_("Configurazione caricata correttamente"));
                $("#uploadovpnbtn").attr('disabled', true);
                $('#form-ovpncfg').val('');
                showStatus();
                showWiredNetworkStatus();
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
                $('#status-ovpn').text(_("VPN non attiva"));
            } else {
                $('#status-ovpn').css({'color': '#35b85a', 'font-weight': 'bold'}); // Stato ATTIVA, verde
                $('#status-ovpn').text(_("VPN Attiva"));
            }
            $('#status-ovpn-description').html(vpnStatus);
        }
    });

}

// Show wired network status with one row per known interface.
function showWiredNetworkStatus() {
    var $el = $('#status-wired-content');
    $.ajax({
        url: "/lib/ovpn/V2/ovpn/wired_status",
        type: "GET",
        success: function (res) {
            var data;
            try { data = JSON.parse(res).data || []; } catch (e) { data = []; }
            $el.html(renderWiredInterfaces(data));
        },
        error: function () {
            $el.html('<p class="text-muted">' + _('Impossibile leggere lo stato della rete') + '</p>');
        }
    });
}

function renderWiredInterfaces(list) {
    if (!list.length) {
        return '<p class="text-muted">' + _('Nessuna interfaccia configurata') + '</p>';
    }
    var esc = function (s) {
        return $('<div/>').text(s == null ? '' : s).html();
    };
    var blocks = [];
    list.forEach(function (iface) {
        var label, color;
        if (!iface.present) {
            label = _('NON PRESENTE');
            color = '#999';
        } else if (iface.operstate === 'UP') {
            label = _('ATTIVA');
            color = '#35b85a';
        } else {
            label = _('NON ATTIVA') + (iface.operstate ? ' (' + iface.operstate + ')' : '');
            color = '#b52c1d';
        }

        var rows = '';
        if (iface.present) {
            var v4 = (iface.ipv4 && iface.ipv4.length) ? iface.ipv4.join(', ') : '—';
            rows += '<div><b>IPv4:</b> ' + esc(v4) + '</div>';
            if (iface.ipv6 && iface.ipv6.length) {
                rows += '<div><b>IPv6:</b> ' + esc(iface.ipv6.join(', ')) + '</div>';
            }
            if (iface.mac) { rows += '<div><b>MAC:</b> ' + esc(iface.mac) + '</div>'; }
            if (iface.mtu) { rows += '<div><b>MTU:</b> ' + esc(iface.mtu) + '</div>'; }
        }

        var titleSuffix = iface.isDefault
            ? ' <small class="text-muted" style="color:#666;">(' + _('default route') + ')</small>'
            : '';
        blocks.push(
            '<div style="margin-bottom:14px;">' +
                '<h4 style="margin:4px 0; color:' + color + '; font-weight:bold;">' +
                    esc(iface.name) + ': ' + label + titleSuffix +
                '</h4>' +
                rows +
            '</div>'
        );
    });
    return blocks.join('');
}

