$(document).ready(function () {

    var consoleOutput = $("#camera-control-out");
    var consoleInput = $("#camera-control-in");

    var list = $("#camera-control-list");
    var reset = $("#camera-control-reset");
    var freset = $("#camera-control-freset");
    var features = $("#camera-control-features");
    var values = $("#camera-control-values");
    var send = $("#camera-control-send");

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
            alert("Devi inviare un comando");
        }
        executeCustomCommand(cmd);
    });

    getAllCameras();

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
                        $("#camera-name").html("Nessuna camera disponibile");
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
                }
            } catch(error)
            {
                console.log("Error");
                console.error(error);
            }
        }
    });
}

