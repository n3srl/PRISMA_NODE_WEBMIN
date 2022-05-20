/**
 *
 * @author: N3 S.r.l.
 */
function formatBytes(a, b = 2, k = 1024) {
    with (Math) {
        let d = floor(log(a) / log(k));
        return 0 == a ? "0 Bytes" : parseFloat((a / pow(k, d)).toFixed(max(0, b))) + " " + ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"][d];
}
}

// Free temporary media folder
function cleanMedia() {
    $.ajax({
        url: "/lib/ft/V2/freeturefinal/storage/clean",
        method: "POST",
        success: function (json) {
            defaultSuccess("Spazio liberato con successo");
            loadMediaStorageInfo();
        }
    });
}

// Get media storage info
function loadMediaStorageInfo() {
    $.get("/lib/ft/V2/freeturefinal/media/info", function (json) {
        var data = JSON.parse(json).data;
        $("#mediausagelbl").html("Spazio occupato dai media: " + formatBytes(data));
    });
}

// Get if media preview is enabled
function checkIfMediaPreviewIsEnabled() {
    $.get("/lib/ft/V2/freeturefinal/media/preview", function (json) {
        var data = JSON.parse(json).data;
        // Set toggle switch unchecked or checked
        $("#enable-media-preview").attr("checked", data);
    });
}

// Enable detections previews
$("#enable-media-preview").on('change', function (event) {
    var enablePreview = $("#enable-media-preview").is(":checked");
    $.post('/lib/ft/V2/freeturefinal/media/preview', {mediaPreview: enablePreview},
            function (data) {
                loadMediaStorageInfo();
            });
});

// Get if media preview is enabled
function checkIfMediaProcessingIsEnabled() {
    $.get("/lib/ft/V2/freeturefinal/media/processing", function (json) {
        var data = JSON.parse(json).data;
        // Set toggle switch unchecked or checked
        $("#enable-media-processing").attr("checked", data);
    });
}

// Enable detections previews
$("#enable-media-processing").on('change', function (event) {
    var enableProcessing = $("#enable-media-preview").is(":checked");
    $.post('/lib/ft/V2/freeturefinal/media/processing', {mediaProcessing: enableProcessing},
            function (data) {
                loadMediaStorageInfo();
            });
});

$(document).ready(function () {
    loadMediaStorageInfo();
    checkIfMediaPreviewIsEnabled();
    checkIfMediaProcessingIsEnabled();
});


