$(document).ready(function () {

    // Carica info dispositivo (senza interrogare la camera: solo log + config).
    loadCameraHwInfo();

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
