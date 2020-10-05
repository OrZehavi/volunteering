$( document ).ready(function() {
    let content = document.getElementById("volunteering-details-content");
    let volNotExist = document.getElementById("volunteering-not-exist");
    
    if (sessionStorage.getItem("is_volunteering_exist") === "true") {
        content.style.display = "block";
        volNotExist.style.display = "none";
    } else {
        content.style.display = "none";
        volNotExist.style.display = "block";
    }
});