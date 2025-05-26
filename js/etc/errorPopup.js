function showErrorPopup(message) {
    const modal = document.getElementById("errorModal");
    const etich = document.getElementById("errorTitle");
    const butt = document.getElementById("loginPopButton");
    const text = document.getElementById("errorText");
    if (modal && text) {
        text.textContent = message;
        etich.textContent = _("Attenzione! Si è verificato un errore");
        butt.textContent = _("Chiudi");
        modal.style.display = "flex";
}
}

function closeErrorPopup() {
    const modal = document.getElementById("errorModal");
    if (modal) {
        modal.style.display = "none";
    }
}