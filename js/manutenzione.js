/**
 *
 * @author: N3 S.r.l.
 */

/**
 *
 * @author: N3 S.r.l.
 */

$(document).ready(function () {

});

function exec_reboot() {
    var baseUrl = "/lib/camera/V1/camera/rebootServer"; //da modificare

    $.ajax({
        url: baseUrl,
        type: 'POST',
        success: function(json) {
            try {
                var data = JSON.parse(json); 
                if (data) {
                    alert(data.data.data); 
                }
            }  catch (error) {
                console.error(error);
                alert("Errore nella risposta del server: " + json);
            }
        },
        error: function(xhr, status, error) {
            alert("Errore nella richiesta: " + error); 
        }
    });
}
