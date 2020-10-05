$( document ).ready(function() {
    let content = document.getElementById("association-details-content");
    let associationNotExist = document.getElementById("association-not-exist");
    
    if (sessionStorage.getItem("is_association_exist") === "true") {
        content.style.display = "block";
        associationNotExist.style.display = "none";
    } else {
        content.style.display = "none";
        associationNotExist.style.display = "block";
    }
});