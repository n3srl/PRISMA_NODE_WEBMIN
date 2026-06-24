$(document).ready(function () {

    // Carica info dispositivo (senza interrogare la camera: solo log + config).
    loadCameraHwInfo();

    // Bottone "lettura deep": stop freeture -> arv-tool values -> start freeture.
    $('#btn-camera-hwinfo-deep').on('click', runCameraHwInfoDeep);

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
            ['ExposureAuto',            _('Esposizione automatica')],
            ['Gain',                    _('Gain')],
            ['GainAuto',                _('Gain automatico')],
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
    ];

    var html = '';
    if (pausedSec !== undefined && pausedSec !== null) {
        html += '<div class="alert alert-success" style="margin:0 0 10px 0;">' +
            '<i class="fa fa-check"></i> ' +
            _('Lettura completata.') + ' ' +
            _('Pausa freeture') + ': <b>' + pausedSec + 's</b>. ' +
            _('Freeture riavviato automaticamente.') +
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
