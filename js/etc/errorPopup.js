function showErrorPopup(message) {
    const modal = document.getElementById("errorModal");
    const text = document.getElementById("errorText");
    if (modal && text) {
        text.textContent = message;
        modal.style.display = "flex";
}
}

function closeErrorPopup() {
    const modal = document.getElementById("errorModal");
    if (modal) {
        modal.style.display = "none";
    }
}