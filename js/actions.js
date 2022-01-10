function verify() {
    msg = "Sei sicuro di voler eliminare?";
    //all we have to do is return the return value of the confirm() method
    return confirm(msg);
}

function submitForm(d) {
    document.getElementById(d).submit();

}
function submitFormWithCheck(d) {

    //alert("Procedere con l'eliminazione?");
    if (confirm("Vuoi perocedere con l'eliminazione?")) {

        document.getElementById(d).submit();

        return true;
    } else
        alert("Eliminazione annullata.");
    {
        return false;
    }



}


function parseDataMySQL(data) {
    var dataTmp = data.split("/");
    return dataTmp[2] + "-" + dataTmp[1] + "-" + dataTmp[0];
}
function evidenziaTestoOn(d) {

    d.style.backgroundColor = "#CCCCCC";
}
function evidenziaTestoOff(d) {

    d.style.backgroundColor = "#FFFFFF";
}