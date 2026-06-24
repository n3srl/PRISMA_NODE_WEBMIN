$(document).ready(function () {

    // Carica info dispositivo (senza interrogare la camera: solo log + config).
    loadCameraHwInfo();

    // Bottone "lettura deep": stop freeture -> arv-tool values -> start freeture.
    $('#btn-camera-hwinfo-deep').on('click', runCameraHwInfoDeep);

    // Bottone "diagnostica rete nodo": ethtool/sys/ping. Non interrompe freeture.
    $('#btn-camera-netdiag').on('click', runCameraNetDiag);

    // Bottone "esplora MIB cable diag" (creato dinamicamente dal render): event delegation.
    $(document).on('click', '#btn-explore-cablediag', exploreSwitchCableDiag);

    var consoleOutput = $("#camera-control-out");
    var consoleInput = $("#camera-control-in");

    var list = $("#camera-control-list");
    var reset = $("#camera-control-reset");
    var freset = $("#camera-control-freset");
    var features = $("#camera-control-features");
    var values = $("#camera-control-values");
    var send = $("#camera-control-send");
    var camera_calibration = $("#camera_calibration");

    var calibration_minGain = $("#calibration_minGain");
    var calibration_maxGain = $("#calibration_maxGain");

    var camera_select = $("#camera-select");

    $("#calibration_freeture").select2();

    list.click(function() {
        executeCommand("list");
    });
    reset.click(function() {
        executeCommand("reset");
    });
    freset.click(function() {
        executeCommand("freset");
    });
    features.click(function() {
        executeCommand("features");
    });
    values.click(function() {
        executeCommand("values");
    });
    send.click(function() {
        var cmd = $("#camera-control-in").val();
        if(cmd == "") {
            alert(_("Devi inviare un comando"));
        }
        executeCustomCommand(cmd);
    });
    camera_select.on('change', function()
    {
        var ip = this.value;
        var name = $(this).find("option:selected").text();
        $("#camera-ip").val(ip);
        $("#camera-name_d").val(name);
        get_camera_bounds();
    });
    camera_calibration.click(function() {
        run_camera_calibration();
    });

    calibration_maxGain.on("change", function()
    {
        if ($("#maxGain").html() == "") 
        {
            $(this).val("");
            return;
        }

        var newvalue = $(this).val();
        if(newvalue > $("#maxGain").html()) 
        {
            newvalue = $("#maxGain").html();
            $(this).val(newvalue);
            return;
        }

        
    });

    calibration_minGain.on('change', function()
    {
        if ($("#minGain").html() == "") 
        {
            $(this).val("");
            return;
        }

        var newvalue = $(this).val();
        if(newvalue < $("#minGain").html()) 
        {
            newvalue = $("#minGain").html();
            $(this).val(newvalue);
            return;
        }
    });

    can_calibrate();
    getAllCameras();
    get_all_calibration();
    

});

function executeCommand(command)
{
    var baseUrl = "/lib/camera/V1/camera/";

    $.ajax({
        url: baseUrl+command, 
        type: 'POST',
        data: {
            ip : $("#camera-ip").val()
        },
        success: function(json)
        {
            var data = JSON.parse(json);
            if(data)
            {
                $("#camera-control-out").val(data.data);
            }
        }
    });
}

function executeCustomCommand(command)
{
    var baseUrl = "/lib/camera/V1/camera/cmd/";
    $.ajax({
        url: baseUrl+command, 
        type: 'POST',
        data: {
            ip : $("#camera-ip").val()
        },
        success: function(json)
        {
            var data = JSON.parse(json);
            if(data)
            {
                $("#camera-control-out").val(data.data);
            }
        }
    });
}


function getAllCameras()
{
    var baseUrl = "/lib/camera/V1/camera/list";
    $.ajax({
        url: baseUrl, 
        type: 'POST',
        success: function(json)
        {
            try {
                var data = JSON.parse(json);
                if(data)
                {
                    var cameras = data.data.split('\n');
                    if(cameras.length == 0) {
                        $("#camera-name").html(_("Nessuna camera disponibile"));
                        return;
                    }
                    if(cameras.length > 2)
                    {
                        
                        var camera_select = Array();

                        for(var i = 0; i < cameras.length - 1; i++)
                        {
                            var ip = cameras[i].split('(')[1].split(')')[0];
                            camera_select.push(
                                {
                                    "id" : ip,
                                    "text" : cameras[i]
                                }
                            );
                        }   
                        var options = {
                            "results" : camera_select
                        }
                        $("#camera-select").select2({
                            data: camera_select
                        })

                        $("#camera-list-container-multiple").show();
                    } else 
                    {
                        var ip = cameras[0].split('(')[1].split(')')[0];
                        $("#camera-name").html(cameras[0]);
                        $("#camera-list-container-single").show();
                    }
                    var ip = cameras[0].split('(')[1].split(')')[0];
                    $("#camera-ip").val(ip);
                    $("#camera-name_d").val(cameras[0]);
                }
                // Now for the camera in first position get gain bounds
                get_camera_bounds();
            } catch(error)
            {
                console.log("Error");
                console.error(error);
            }
        }
    });
}

function get_camera_bounds()
{
    var baseUrl = "/lib/camera/V1/camera/bounds";
    $.ajax({
        url: baseUrl, 
        type: 'POST',
        data: {
            ip : $("#camera-ip").val(),
            camera : $("#camera-name_d").val().split("(")[0],
            feature : 'Gain'
        },
        success: function(json)
        {
            try
            {
                var data = JSON.parse(json);
                if(data)
                {   
                    var gains = data.data.replace(/\s+/g, '');
                    var gains = gains.split(',');
                    $("#minGain").html(gains[0]);
                    $("#maxGain").html(gains[1]);

                    $("#calibration_minGain").val(gains[0]);
                    $("#calibration_maxGain").val(gains[1]);
                }           
            } catch (err)
            {
                alert(json);
            }
        }
    });
}

function run_camera_calibration()
{
    var baseUrl = "/lib/camera/V1/camera/calibration";

    minGain = $("#calibration_minGain").val();
    maxGain = $("#calibration_maxGain").val();
    exposure = $("#calExposure").val();
    step = $("#calibration_step").val();
    image = $("#calibration_freeture").val();

    if(minGain == "" || maxGain == "" || exposure == "")
    {
        alert(_("Gain o exposure non sono validi"));
        return;
    }

    if(maxGain < minGain)
    {
        alert(_("MaxGain deve essere > di MinGain"));
        return;
    }

    $.ajax({
        url: baseUrl, 
        type: 'POST',
        data: {
            minGain : minGain,
            maxGain : maxGain,
            exposure : $("#calibration_exp").val(),
            camera : $("#camera-name_d").val().split("(")[0],
            step : step,
            image : image
        },
        success: function(json)
        {
            try {
                var data = JSON.parse(json);
                if(data)
                {
                    $("#calibration_form").hide();
                    $("#calibration_notice").show();
                    alert(data.data);
                }
            } catch (error)
            {
                alert(json);
            }
        }
    });
}

function get_all_calibration()
{
    var baseUrl = "/lib/camera/V1/camera/calibration";
    var table = $("calibration_table");

    $.ajax({
        url: baseUrl, 
        type: 'GET',
        success: function(json)
        {
            try
            {
                var data = JSON.parse(json);
                if(data)
                {
                    $("#calibration_table").html(data.data);
                }  

                $(".calibration_delete").on('click', function(event)
                {
                    calibration = $(this).attr('name');
                    delete_calibration(calibration);
                });
            } catch (error)
            {
                console.error(error);
            }
        }
    });
}

function delete_calibration(name)
{
    var baseUrl = "/lib/camera/V1/camera/calibration";
    $.ajax({
        url: baseUrl, 
        type: 'DELETE',
        data: {
            calibration : name
        },
        success: function(json)
        {
            get_all_calibration();
        }
    });
}

function can_calibrate()
{
    var baseUrl = "/lib/camera/V1/camera/cancalibrate";
    $.ajax({
        url: baseUrl, 
        type: 'GET',
        success: function(json)
        {
            try
            {
                var data = JSON.parse(json);
                if(data)
                {
                    if(!data.data)
                    {
                        $("#calibration_form").hide();
                        $("#calibration_notice").show();
                    }
                }
            } catch (error)
            {
                console.error(error);
            }
        }
    });
}

// Popola il box "Informazioni dispositivo" leggendo /camera/hwinfo.
// I dati arrivano da configuration.cfg (sempre disponibili) e dai log freeture
// (vendor/model/firmware/serial, scritti all'avvio dell'acquisizione).
function loadCameraHwInfo() {
    function setCell(id, val) {
        var $el = $('#' + id);
        if (!$el.length) return;
        if (val === null || val === undefined || val === '') {
            $el.html('<span class="text-muted">&mdash;</span>');
        } else {
            $el.text(String(val));
        }
    }
    $.ajax({
        url: '/lib/camera/V1/camera/hwinfo',
        method: 'GET',
        dataType: 'json',
        cache: false
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) {
            $('#camera-hwinfo-loading').text(_('Informazioni non disponibili'));
            return;
        }
        var hw  = resp.data.hardware   || {};
        var cfg = resp.data.configured || {};

        setCell('hw-vendor',   hw.vendor);
        setCell('hw-model',    hw.model);
        setCell('hw-firmware', hw.firmware);
        setCell('hw-serial',   hw.serial);
        setCell('hw-ip',       hw.ip);
        setCell('hw-aravis',   hw.aravis);
        setCell('hw-lastseen', hw.lastSeenAt);

        setCell('cfg-camera',     cfg.camera);
        setCell('cfg-cameraId',   cfg.cameraId);
        setCell('cfg-instrument', cfg.instrument);
        setCell('cfg-telescope',  cfg.telescope);
        setCell('cfg-format',     cfg.format);
        setCell('cfg-resolution', cfg.resolution);
        setCell('cfg-fps',        cfg.fps);

        $('#camera-hwinfo-loading').hide();
        $('#camera-hwinfo').show();
    }).fail(function (xhr) {
        console.error('[camera/hwinfo] FAIL', xhr && xhr.status, xhr && xhr.responseText);
        $('#camera-hwinfo-loading').text(_('Errore nel caricamento delle informazioni dispositivo'));
    });
}

// Lettura "deep": chiede conferma, ferma freeture e legge tutti i parametri GenICam.
function runCameraHwInfoDeep() {
    var msg = _('Questa operazione fermerà freeture per circa 5 secondi per leggere tutti i parametri della camera. Confermi?');
    if (!confirm(msg)) return;

    var $btn = $('#btn-camera-hwinfo-deep');
    var $prog = $('#camera-hwinfo-deep-progress');
    var $out = $('#camera-hwinfo-deep');
    $btn.prop('disabled', true);
    $prog.show();
    $out.hide().empty();

    // Recupero CSRF token (stesso pattern usato altrove).
    $.ajax({
        url: '/lib/core/v1/csfr',
        method: 'GET',
        dataType: 'json'
    }).then(function (csrf) {
        if (!csrf || !csrf.result || !csrf.data || !csrf.data.token) {
            return $.Deferred().reject('missing csrf token');
        }
        return $.ajax({
            url: '/lib/camera/V1/camera/hwinfo/deep',
            method: 'POST',
            data: { token: csrf.data.token },
            dataType: 'json'
        });
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) {
            $out.html('<div class="alert alert-danger">' +
                _('Errore durante la lettura') +
                (resp && resp.data ? ': ' + _escDeep(String(resp.data)) : '') +
            '</div>').show();
            return;
        }
        renderCameraHwInfoDeep(resp.data);
    }).fail(function (xhr) {
        console.error('[camera/hwinfo/deep] FAIL', xhr && xhr.status, xhr && xhr.responseText);
        var detail = (xhr && xhr.responseText) ? xhr.responseText.substring(0, 300) : '';
        $out.html('<div class="alert alert-danger">' +
            _('Errore HTTP nella lettura completa') + ' (' + (xhr && xhr.status) + ')' +
            (detail ? ': <code>' + _escDeep(detail) + '</code>' : '') +
        '</div>').show();
    }).always(function () {
        $btn.prop('disabled', false);
        $prog.hide();
    });
}

function _escDeep(s) {
    return $('<div>').text(s == null ? '' : String(s)).html();
}

function renderCameraHwInfoDeep(data) {
    var live = data.live || {};
    var warnings = data.warnings || [];
    var pausedSec = data.pausedSec;
    var rawOutput = data.raw || '';
    console.log('[camera/hwinfo/deep] response', data);
    console.log('[camera/hwinfo/deep] live keys:', Object.keys(live));

    // Raggruppamenti logici delle feature in ordine di interesse.
    var groups = [
        { title: _('Identità'), keys: [
            ['DeviceVendorName',       _('Vendor')],
            ['DeviceModelName',        _('Modello')],
            ['DeviceVersion',          _('Versione firmware')],
            ['DeviceManufacturerInfo', _('Info produttore')],
            ['DeviceSerialNumber',     _('Serial number')],
            ['DeviceTLType',           _('Transport layer')],
        ]},
        { title: _('Link e throughput'), keys: [
            ['DeviceLinkSpeed',                    _('Velocità link')],
            ['DeviceMaxThroughput',                _('Throughput massimo')],
            ['DeviceLinkThroughputLimitMode',      _('Limite throughput')],
            ['DeviceLinkThroughputLimit',          _('Limite (configurato)')],
            ['DeviceLinkThroughputReserve',        _('Riserva throughput')],
            ['AcquisitionFrameRateLinkLimitEnable',_('FPS limitato dal link')],
            ['DeviceStreamChannelPacketSize',      _('Packet size stream')],
            ['GevSCPSPacketSize',                  _('GigE packet size')],
            ['GevSCPD',                            _('GigE packet delay')],
        ]},
        { title: _('Acquisizione'), keys: [
            ['AcquisitionMode',         _('Modo acquisizione')],
            ['AcquisitionFrameRate',    _('Frame rate (corrente)')],
            ['AcquisitionFrameRateEnable', _('Frame rate enabled')],
            ['ExposureTime',            _('Tempo esposizione')],
            ['Gain',                    _('Gain')],
            ['BlackLevel',              _('Black level')],
            ['PixelFormat',             _('Formato pixel')],
            ['Width',                   _('Larghezza')],
            ['Height',                  _('Altezza')],
            ['OffsetX',                 _('Offset X')],
            ['OffsetY',                 _('Offset Y')],
            ['TriggerMode',             _('Trigger')],
            ['ADCBitDepth',             _('Profondità ADC')],
            ['SensorShutterMode',       _('Shutter')],
        ]},
        { title: _('Sensore'), keys: [
            ['SensorWidth',       _('Sensor width')],
            ['SensorHeight',      _('Sensor height')],
            ['PhysicalPixelSize', _('Pixel size (µm)')],
        ]},
        { title: _('Runtime'), keys: [
            ['DeviceTemperature', _('Temperatura')],
            ['DevicePower',       _('Potenza')],
            ['DeviceUpTime',      _('Uptime device')],
            ['LinkUpTime',        _('Uptime link')],
        ]},
        { title: _('GigE Vision'), keys: [
            ['GevCurrentIPAddress',      _('IP corrente')],
            ['GevCurrentSubnetMask',     _('Subnet mask')],
            ['GevCurrentDefaultGateway', _('Gateway')],
            ['GevMACAddress',            _('MAC address')],
        ]},
        { title: _('Stabilità acquisizione'), keys: [
            ['DeviceLinkHeartbeatMode',      _('Heartbeat GigE')],
            ['DeviceLinkHeartbeatTimeout',   _('Heartbeat timeout (s)')],
            ['DeviceLinkCommandTimeout',     _('Command timeout (s)')],
            ['ActionUnconditionalMode',      _('Action unconditional')],
            ['ExposureAuto',                 _('Esposizione automatica')],
            ['ExposureAutoLimitAuto',        _('AE limit auto')],
            ['GainAuto',                     _('Gain automatico')],
            ['PtpEnable',                    _('PTP abilitato')],
            ['PtpStatus',                    _('PTP stato')],
            ['PtpServoStatus',               _('PTP servo')],
            ['AcquisitionStartMode',         _('Modo avvio acquisizione')],
            ['TransferControlMode',          _('Transfer control mode')],
            ['PacketResendWindowFrameCount', _('Packet resend window')],
        ]},
    ];

    var html = '';
    if (pausedSec !== undefined && pausedSec !== null) {
        var parserChip = data.parserUsed
            ? ' <span class="label label-default" style="margin-left:6px;" title="' + _escDeep(_('Parser usato per leggere i valori arv-tool (vendor-aware)')) + '">parser: ' + _escDeep(data.parserUsed) + '</span>'
            : '';
        html += '<div class="alert alert-success" style="margin:0 0 10px 0;">' +
            '<i class="fa fa-check"></i> ' +
            _('Lettura completata.') + ' ' +
            _('Pausa freeture') + ': <b>' + pausedSec + 's</b>. ' +
            _('Freeture riavviato automaticamente.') +
            parserChip +
        '</div>';
    }
    if (warnings.length) {
        html += '<div class="alert alert-warning">' +
            '<b>' + _('Avvisi') + ':</b><ul style="margin:4px 0 0 0; padding-left:20px;">' +
            warnings.map(function (w) { return '<li>' + _escDeep(w) + '</li>'; }).join('') +
        '</ul></div>';
    }

    // Tieni traccia di quali key sono gia' state mostrate nei gruppi, cosi'
    // possiamo poi mostrare tutto cio' che il parser ha estratto MA che non e'
    // mappato in alcun gruppo (utile per scoprire nuove feature interessanti).
    var shownKeys = {};
    var renderedGroups = 0;

    groups.forEach(function (g) {
        var rows = g.keys.filter(function (kv) { return live[kv[0]] !== undefined; });
        if (!rows.length) return;
        renderedGroups++;
        html += '<div class="col-md-6 col-sm-12" style="padding-left:0;">' +
            '<h4 style="margin-top:0;">' + _escDeep(g.title) + '</h4>' +
            '<table class="table table-condensed" style="margin-bottom:14px;">';
        rows.forEach(function (kv) {
            shownKeys[kv[0]] = true;
            html += '<tr>' +
                '<th style="width:45%;">' + _escDeep(kv[1]) + '</th>' +
                '<td>' + _escDeep(live[kv[0]]) + '</td>' +
            '</tr>';
        });
        html += '</table></div>';
    });
    html += '<div class="clearfix"></div>';

    // Sezione "Altri parametri letti": chiavi di live che NON sono in alcun gruppo.
    var otherKeys = Object.keys(live).filter(function (k) { return !shownKeys[k]; });
    if (otherKeys.length) {
        html += '<h4 style="margin-top:6px;">' + _('Altri parametri letti') + '</h4>' +
                '<table class="table table-condensed table-striped" style="margin-bottom:14px;">';
        otherKeys.sort().forEach(function (k) {
            html += '<tr>' +
                '<th style="width:35%;"><code>' + _escDeep(k) + '</code></th>' +
                '<td>' + _escDeep(live[k]) + '</td>' +
            '</tr>';
        });
        html += '</table>';
    }

    // Fallback: se non e' uscito NESSUN campo strutturato, il parser PHP non ha
    // matchato il formato di arv-tool. Mostra l'output grezzo cosi' possiamo capire.
    if (renderedGroups === 0 && !otherKeys.length) {
        html += '<div class="alert alert-warning">' +
            '<b>' + _('Nessun parametro estratto') + '.</b> ' +
            _('Il parser non ha riconosciuto il formato di output di arv-tool. Sotto trovi l\'output grezzo: serve per affinare il parser.') +
        '</div>';
    }

    // Output grezzo sempre disponibile come <details> espandibile.
    if (rawOutput) {
        var snippet = rawOutput.length > 100000 ? rawOutput.substring(0, 100000) + '\n[...troncato...]' : rawOutput;
        html += '<details style="margin-top:8px;">' +
            '<summary style="cursor:pointer;"><b>' + _('Output grezzo arv-tool') + '</b> ' +
            '<small class="text-muted">(' + rawOutput.length + ' ' + _('caratteri') + ')</small></summary>' +
            '<pre style="max-height:400px; overflow:auto; background:#f7f7f9; padding:8px; font-size:11px; margin-top:6px;">' +
            _escDeep(snippet) +
            '</pre>' +
        '</details>';
    }

    $('#camera-hwinfo-deep').html(html).show();
}

// Diagnostica rete nodo->camera. Non interrompe freeture.
function runCameraNetDiag() {
    var $btn = $('#btn-camera-netdiag');
    var $prog = $('#camera-netdiag-progress');
    var $out = $('#camera-netdiag');
    $btn.prop('disabled', true);
    $prog.show();
    $out.hide().empty();

    var cameraIp = $('#camera-ip').val() || '';

    $.ajax({
        url: '/lib/camera/V1/camera/diag',
        method: 'GET',
        data: cameraIp ? { ip: cameraIp } : {},
        dataType: 'json',
        cache: false
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) {
            $out.html('<div class="alert alert-danger">' +
                _('Errore durante la diagnostica') +
                (resp && resp.data ? ': ' + _escDeep(String(resp.data)) : '') +
            '</div>').show();
            return;
        }
        renderCameraNetDiag(resp.data);
    }).fail(function (xhr) {
        console.error('[camera/diag] FAIL', xhr && xhr.status, xhr && xhr.responseText);
        $out.html('<div class="alert alert-danger">' +
            _('Errore HTTP durante la diagnostica') + ' (' + (xhr && xhr.status) + ')' +
        '</div>').show();
    }).always(function () {
        $btn.prop('disabled', false);
        $prog.hide();
    });
}

function renderCameraNetDiag(data) {
    var verdict  = data.verdict || [];
    var link     = data.link || {};
    var counters = data.counters || {};
    var ping     = data.ping || {};
    var warnings = data.warnings || [];

    var statColors = { ok: '#1d7a44', warn: '#b07d00', err: '#b52c1d' };
    var statIcons  = { ok: 'fa-check-circle', warn: 'fa-exclamation-triangle', err: 'fa-times-circle' };

    var html = '';

    // Header con riassunto
    html += '<div class="alert alert-info" style="margin:0 0 10px 0;">' +
        '<i class="fa fa-stethoscope"></i> ' +
        _('Diagnostica nodo &rarr; camera completata') + '.' +
        (data.nic ? ' <b>NIC</b>: <code>' + _escDeep(data.nic) + '</code>' : '') +
        (data.cameraIp ? ' &middot; <b>' + _('IP camera') + '</b>: <code>' + _escDeep(data.cameraIp) + '</code>' : '') +
        (link.cameraMac ? ' &middot; <b>MAC</b>: <code>' + _escDeep(link.cameraMac) + '</code>' : '') +
    '</div>';

    if (warnings.length) {
        html += '<div class="alert alert-warning">' +
            '<b>' + _('Avvisi') + ':</b><ul style="margin:4px 0 0 0; padding-left:20px;">' +
            warnings.map(function (w) { return '<li>' + _escDeep(w) + '</li>'; }).join('') +
        '</ul></div>';
    }

    // Verdetti principali
    if (verdict.length) {
        html += '<h4 style="margin-top:0;">' + _('Verdetti') + '</h4>' +
                '<table class="table table-condensed table-striped" style="margin-bottom:14px;">' +
                '<thead><tr><th style="width:25%;">' + _('Metrica') + '</th>' +
                '<th style="width:35%;">' + _('Valore') + '</th>' +
                '<th>' + _('Note') + '</th></tr></thead><tbody>';
        verdict.forEach(function (v) {
            var color = statColors[v.status] || '#666';
            var icon  = statIcons[v.status] || '';
            html += '<tr>' +
                '<td><i class="fa ' + icon + '" style="color:' + color + '"></i> ' +
                    _escDeep(v.label) + '</td>' +
                '<td style="font-weight:600; color:' + color + '">' + _escDeep(v.value) + '</td>' +
                '<td><small>' + (v.hint ? _escDeep(v.hint) : '') + '</small></td>' +
            '</tr>';
        });
        html += '</tbody></table>';
    }

    // Sezione contatori completi (collapsible)
    if (counters && Object.keys(counters).length) {
        html += '<details style="margin-top:8px;">' +
            '<summary style="cursor:pointer;"><b>' + _('Tutti i contatori NIC') + '</b></summary>' +
            '<table class="table table-condensed" style="margin-top:6px; margin-bottom:0;">';
        Object.keys(counters).sort().forEach(function (k) {
            html += '<tr><th style="width:40%;"><code>' + _escDeep(k) + '</code></th>' +
                    '<td>' + _escDeep(counters[k].toLocaleString('it-IT')) + '</td></tr>';
        });
        html += '</table></details>';
    }

    // Output ping grezzo (collapsible)
    if (ping && ping.rawTail) {
        html += '<details style="margin-top:8px;">' +
            '<summary style="cursor:pointer;"><b>' + _('Output ping') + '</b></summary>' +
            '<pre style="max-height:200px; overflow:auto; background:#f7f7f9; padding:8px; font-size:11px; margin-top:6px;">' +
            _escDeep(ping.rawTail) +
            '</pre></details>';
    }

    // Sezione switch (fase 2). Visibile solo se configurato in config.php.
    if (data.switch && data.switch.configured) {
        html += renderSwitchSection(data.switch);
    }

    $('#camera-netdiag').html(html).show();
}

function renderSwitchSection(sw) {
    var html = '<hr style="margin:14px 0;">';
    html += '<h4 style="margin-top:0;">' + _('Switch') + ' <code>' + _escDeep(sw.ip) + '</code></h4>';

    if (!sw.reachable) {
        var w = (sw.warnings && sw.warnings.length) ? sw.warnings.join(' ') : _('Non raggiungibile via SNMP');
        html += '<div class="alert alert-warning">' + _escDeep(w) + '</div>';
        return html;
    }

    // sysName / sysDescr (fallback su sysDescr se sysName e' stringa vuota)
    var displayName = sw.sysName && sw.sysName !== '' ? sw.sysName : (sw.sysDescr || '-');
    var descLine = (sw.sysName && sw.sysName !== '' && sw.sysDescr) ? sw.sysDescr : '';
    html += '<div class="alert alert-info" style="margin-bottom:10px;">' +
        '<b>' + _('Nome') + '</b>: ' + _escDeep(displayName) +
        (descLine ? ' &middot; <small>' + _escDeep(descLine) + '</small>' : '') +
        '<br>' +
        '<b>' + _('Porte totali') + '</b>: ' + (sw.portsTotal || 0) +
        ' &middot; <b>' + _('Up') + '</b>: ' + (sw.portsUp || 0) +
        ' &middot; <b>' + _('Down') + '</b>: ' + ((sw.portsTotal || 0) - (sw.portsUp || 0)) +
        (sw.cameraPort ? ' &middot; <b>' + _('Camera') + '</b>: <span class="label label-success">Port ' + sw.cameraPort + '</span>' : '') +
        (sw.nodePort   ? ' &middot; <b>' + _('Nodo')   + '</b>: <span class="label label-info">Port ' + sw.nodePort + '</span>' : '') +
        (sw.uplinkPort ? ' &middot; <b>' + _('Uplink') + '</b>: <span class="label label-primary">Port ' + sw.uplinkPort + '</span>' : '') +
    '</div>';

    // Riepilogo: i 3 ruoli (camera/nodo/uplink) devono cadere su 3 porte
    // fisiche distinte del DGS-1210. Tre alert distinti, in ordine di gravita':
    //   1) TOPOLOGIA VIOLATA (rosso scuro) -> ruoli che condividono porta
    //   2) Allerta sicurezza (rosso)       -> porte UP non giustificate
    //   3) Ruoli non identificati (giallo) -> MAC mancanti nel FDB
    // "Configurazione OK" solo se nessuno dei tre scatta.
    var intruders   = sw.intruders || [];
    var missing     = sw.missingRoles || [];
    var violations  = sw.topologyViolations || [];
    var fdbHint = sw.fdbSource
        ? ' <small class="text-muted">(FDB: ' + _escDeep(sw.fdbSource) + ', ' + (sw.fdbSize || 0) + ' MAC visti)</small>'
        : '';

    if (violations.length > 0) {
        html += '<div class="alert alert-danger" style="margin-bottom:10px; border-width:2px; font-size:14px;">' +
            '<i class="fa fa-exclamation-circle fa-lg"></i> ' +
            '<b>' + _('TOPOLOGIA DI RETE VIOLATA') + '</b>: ' +
            _('camera, nodo e uplink devono essere collegati ognuno a una porta fisica diversa dello switch') + '.' +
            '<ul style="margin:6px 0 0 0; padding-left:20px;">';
        violations.forEach(function (v) {
            html += '<li>' + _escDeep(v) + '</li>';
        });
        html += '</ul></div>';
    }

    if (intruders.length > 0) {
        html += '<div class="alert alert-danger" style="margin-bottom:10px;">' +
            '<i class="fa fa-exclamation-triangle"></i> ' +
            '<b>' + _('Allerta sicurezza') + '</b>: ' +
            _('rilevati') + ' <b>' + intruders.length + '</b> ' +
            _('host non giustificati collegati allo switch (non camera, non nodo, non uplink)') + '.' +
            '<ul style="margin:6px 0 0 0; padding-left:20px;">';
        intruders.forEach(function (it) {
            var macList = (it.macs && it.macs.length)
                ? it.macs.map(function (m) { return '<code>' + _escDeep(m) + '</code>'; }).join(', ')
                : '<span class="text-muted">(' + _('nessun MAC visto recentemente') + ')</span>';
            html += '<li><b>Port ' + it.ifIndex + '</b> &mdash; ' + _escDeep(it.name) +
                ' (' + (it.speedMbps || 0) + ' Mb/s): ' + macList + '</li>';
        });
        html += '</ul></div>';
    }

    if (missing.length > 0) {
        html += '<div class="alert alert-warning" style="margin-bottom:10px;">' +
            '<i class="fa fa-question-circle"></i> ' +
            '<b>' + _('Ruoli non identificati') + '</b>: ' +
            missing.map(function (m) { return '<code>' + _escDeep(m) + '</code>'; }).join(', ') +
            '. ' + _('Il MAC corrispondente non e\' presente nel FDB dello switch.') + fdbHint +
        '</div>';
    }

    // Velocita' sub-gigabit su una porta di ruolo = degradazione di link.
    var speedWarnings = sw.speedWarnings || [];
    if (speedWarnings.length > 0) {
        html += '<div class="alert alert-warning" style="margin-bottom:10px;">' +
            '<i class="fa fa-tachometer"></i> ' +
            '<b>' + _('Velocita link inferiore al gigabit') + '</b>: ';
        var bullets = speedWarnings.map(function (w) {
            return '<li><b>Port ' + w.port + '</b> (' + w.roles.join(' + ') + ')' +
                ' &mdash; <span style="color:#b07d00;font-weight:600;">' + w.speedMbps + ' Mb/s</span>' +
                ' ' + _('invece dei') + ' <b>1000 Mb/s</b> ' + _('attesi') + '.</li>';
        }).join('');
        html += '<ul style="margin:6px 0 0 0; padding-left:20px;">' + bullets + '</ul>' +
            '<div style="margin-top:8px;"><b>' + _('Come diagnosticare') + ':</b>' +
            '<ol style="margin:4px 0 0 0; padding-left:20px;">' +
            '<li>' + _('Sostituisci il cavo con un Cat5e/Cat6 nuovo (di norma e\' il problema piu\' comune)') + '.</li>' +
            '<li>' + _('Verifica connettori RJ45 (pin storti, sporcizia, terminazione cattiva)') + '.</li>' +
            '<li>' + _('Usa Cable Diagnostics dalla GUI dello switch:') +
                ' <code>http://localhost:8081 → L2 Functions → Diagnostics → Cable Diagnostics</code>' +
                ' (' + _('o nel menu "Monitoring → Cable Diagnostics" a seconda del firmware') + '). ' +
                _('Seleziona la porta interessata e lancia il test: indica lunghezza e stato per ciascuna delle 4 coppie del cavo (OK / Open / Short / Impedance Mismatch).') +
                '</li>' +
            '<li>' + _('Se il test segnala una coppia "Open Circuit" o "Short", il cavo va sostituito') + '.</li>' +
            '</ol></div>' +
            '<div style="margin-top:10px;">' +
                '<button type="button" id="btn-explore-cablediag" class="btn btn-warning btn-sm">' +
                '<i class="fa fa-search"></i> ' + _('Prova a leggere Cable Diag dallo switch via SNMP') +
                '</button>' +
                ' <small class="text-muted">' + _('Esplora la MIB D-Link per trovare i dati del test cavo') + '</small>' +
                '<div id="cablediag-explore-out" style="margin-top:10px;"></div>' +
            '</div>' +
        '</div>';
    }

    if (violations.length === 0 && intruders.length === 0 && missing.length === 0 && speedWarnings.length === 0) {
        html += '<div class="alert alert-success" style="margin-bottom:10px;">' +
            '<i class="fa fa-shield"></i> ' +
            '<b>' + _('Configurazione OK') + '</b>: ' +
            _('camera + nodo + uplink su tre porte fisiche dedicate, tutte a gigabit, nessun host non giustificato.') +
            fdbHint +
        '</div>';
    }

    if (!sw.ports || !sw.ports.length) {
        html += '<div class="alert alert-warning">' + _('Nessuna porta ethernet trovata via SNMP.') + '</div>';
        return html;
    }

    // Tabella porte
    html += '<table class="table table-condensed table-striped" style="margin-bottom:8px;">' +
            '<thead><tr>' +
                '<th>#</th>' +
                '<th>' + _('Nome') + '</th>' +
                '<th>' + _('Status') + '</th>' +
                '<th>' + _('Speed') + '</th>' +
                '<th>' + _('RX (octets)') + '</th>' +
                '<th>' + _('TX (octets)') + '</th>' +
                '<th>' + _('RX errors') + '</th>' +
                '<th>' + _('CRC') + '</th>' +
                '<th>' + _('Discards') + '</th>' +
                '<th>' + _('Note') + '</th>' +
            '</tr></thead><tbody>';

    var intruderIfx = {};
    (sw.intruders || []).forEach(function (it) { intruderIfx[it.ifIndex] = true; });

    sw.ports.forEach(function (p) {
        var noteParts = [];
        var rowClass = '';
        if (sw.cameraPort && p.ifIndex === sw.cameraPort) {
            noteParts.push('<span class="label label-success">camera</span>');
        }
        if (sw.nodePort && p.ifIndex === sw.nodePort) {
            noteParts.push('<span class="label label-info">nodo</span>');
        }
        if (sw.uplinkPort && p.ifIndex === sw.uplinkPort) {
            noteParts.push('<span class="label label-primary">uplink</span>');
        }
        if (noteParts.length > 0) {
            rowClass = 'class="info"';
        } else if (p.up && intruderIfx[p.ifIndex]) {
            noteParts.push('<span class="label label-danger">INTRUSO</span>');
            rowClass = 'class="danger"';
        }
        var note = noteParts.join(' ');

        var speedHtml;
        if (!p.up) {
            speedHtml = '<span class="text-muted">&mdash;</span>';
        } else if (p.speedMbps >= 1000) {
            speedHtml = '<span style="color:#1d7a44;font-weight:600;">' + p.speedMbps + ' Mb/s</span>';
        } else if (p.speedMbps >= 100) {
            speedHtml = '<span style="color:#b07d00;font-weight:600;">' + p.speedMbps + ' Mb/s</span>';
        } else if (p.speedMbps > 0) {
            speedHtml = '<span style="color:#b52c1d;font-weight:600;">' + p.speedMbps + ' Mb/s</span>';
        } else {
            speedHtml = '<span class="text-muted">0</span>';
        }

        var statusHtml = p.up
            ? '<span style="color:#1d7a44;"><i class="fa fa-check"></i> up</span>'
            : '<span class="text-muted"><i class="fa fa-circle-o"></i> down</span>';

        html += '<tr ' + rowClass + '>' +
            '<td>' + p.ifIndex + '</td>' +
            '<td><code>' + _escDeep(p.name) + '</code></td>' +
            '<td>' + statusHtml + '</td>' +
            '<td>' + speedHtml + '</td>' +
            '<td>' + (p.inOctets ? p.inOctets.toLocaleString('it-IT') : '0') + '</td>' +
            '<td>' + (p.outOctets ? p.outOctets.toLocaleString('it-IT') : '0') + '</td>' +
            '<td' + ((p.inErrors > 0) ? ' style="color:#b52c1d;font-weight:600;"' : '') + '>' + (p.inErrors || 0) + '</td>' +
            '<td' + ((p.fcsErrors > 0) ? ' style="color:#b52c1d;font-weight:600;"' : '') + '>' + (p.fcsErrors === null ? '&mdash;' : p.fcsErrors) + '</td>' +
            '<td' + ((p.inDiscards > 0) ? ' style="color:#b07d00;font-weight:600;"' : '') + '>' + (p.inDiscards || 0) + '</td>' +
            '<td>' + note + '</td>' +
        '</tr>';
    });

    html += '</tbody></table>';
    return html;
}

// Esplora la MIB dello switch alla ricerca degli OID Cable Diagnostics.
function exploreSwitchCableDiag() {
    var $btn = $('#btn-explore-cablediag');
    var $out = $('#cablediag-explore-out');
    $btn.prop('disabled', true);
    $out.html('<div class="alert alert-info" style="margin:0;"><i class="fa fa-spinner fa-spin"></i> ' +
        _('Walk SNMP in corso (puo\' richiedere 10-30 secondi)...') + '</div>');

    $.ajax({
        url: '/lib/camera/V1/camera/diag/switch/explore',
        method: 'GET',
        dataType: 'json',
        cache: false
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) {
            $out.html('<div class="alert alert-danger">' + _('Errore nell\'esplorazione') + '</div>');
            return;
        }
        var d = resp.data;
        if (!d.configured) {
            $out.html('<div class="alert alert-warning">' + _('Switch non configurato in config.php') + '</div>');
            return;
        }
        var html = '';
        var totalEntries = 0;
        d.branches.forEach(function (b) { totalEntries += b.count; });

        if (totalEntries === 0) {
            html += '<div class="alert alert-warning">' +
                _('Nessun OID risponde sulle branch D-Link tentate') + '. ' +
                _('Probabilmente il firmware') + ' <b>6.30.016</b> ' +
                _('non espone Cable Diagnostics via SNMP. Resta accessibile dalla GUI dello switch (Monitoring/L2 → Cable Diagnostics).') +
            '</div>';
        } else {
            html += '<div class="alert alert-success" style="margin-bottom:8px;">' +
                _('Trovate') + ' <b>' + totalEntries + '</b> ' + _('entry totali. Manda il dump completo per scrivere il parser definitivo.') +
            '</div>';
        }

        d.branches.forEach(function (b) {
            html += '<details style="margin-bottom:8px;" ' + (b.count > 0 ? 'open' : '') + '>' +
                '<summary style="cursor:pointer;">' +
                '<b>' + _escDeep(b.base) + '</b> &mdash; ' + _escDeep(b.desc) +
                ' <span class="label label-' + (b.count > 0 ? 'success' : 'default') + '">' +
                b.count + ' ' + _('entry') + '</span>' +
                '</summary>';
            if (b.sample.length > 0) {
                html += '<table class="table table-condensed" style="margin-top:6px; font-size:11px;">';
                b.sample.forEach(function (e) {
                    html += '<tr><td style="font-family:monospace; width:50%; word-break:break-all;">' +
                        _escDeep(e.oid) + '</td><td>' + _escDeep(e.value) + '</td></tr>';
                });
                html += '</table>';
            }
            html += '</details>';
        });

        $out.html(html);
    }).fail(function (xhr) {
        $out.html('<div class="alert alert-danger">' +
            _('Errore HTTP') + ' (' + (xhr && xhr.status) + ')' +
        '</div>');
    }).always(function () {
        $btn.prop('disabled', false);
    });
}
