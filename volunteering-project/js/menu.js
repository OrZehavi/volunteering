$(document).ready(function() {
    isLoggedin = sessionStorage.getItem("loggedin");
    if (isLoggedin) {
        //Hide or Present admin pages
        if (sessionStorage.getItem("role") == "Admin") {
            document.getElementById("admin-page").style.display = "inline-block";
        } else {
            document.getElementById("admin-page").style.display = "none";
        }
        
        switchMenuLinks();
    } else {
       userLoggedOut(); 
    }
    
    window.addEventListener("storage", function(e) {
        if (e.storageArea === sessionStorage) {
            switchMenuLinks();
        }
    }); 
});

function userLoggedOut() {
    document.getElementById("login-register-button").style.display = "inline-block";
    document.getElementById("user-profile-button").style.display = "none";

    document.getElementById("logout-button").style.display = "none";
}
function switchMenuLinks () {
    if (sessionStorage.getItem("loggedin") == "1") {
        document.getElementById("user-profile-button").style.display = "inline-block";
        $("#user-profile-link").attr("href", `/volunteering-project/includes/php/profile.php?user=${sessionStorage.getItem("userid")}`);

        document.getElementById("login-register-button").style.display = "none";
        document.getElementById("logout-button").style.display = "inline-block";
        
    } 
    else 
    {
        userLoggedOut();
    }
}

