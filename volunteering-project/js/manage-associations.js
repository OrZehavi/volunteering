



let url = new URL(window.location.href);
let currentUserNavSelection;

    
$( document ).ready(function() {
        $( "#menu" ).load("../html/menu.html" );

    //if (sessionStorage.getItem("is_user_exist") === "true") {
      //  if (sessionStorage.getItem("loggedin") != "1" || sessionStorage.getItem("userid") !== url.searchParams.get("user")) {
            // The user not logged in or watching someone else profile
            // therfore disable input fields from typing
            //$("#updateProfile :input").prop("disabled", true);
      //  }
        changeToggleBarSelection('approved');
   // } else {
        //let content = document.getElementById("content");
        //content.style.display = "none";
        //let userNotExist = document.getElementById("user-not-exist");
        //userNotExist.style.display = "block";

  //  }
});

function changeToggleBarSelection(newSelection) {
    if (newSelection !== currentUserNavSelection) {
        currentUserNavSelection = newSelection;
        let Associations = document.getElementById("manageAssociations");
        let NewAssociations=document.getElementById("manageNewAssociations");
        let approvedAssociationsNavButton = document.getElementById("approved-associations-nav-button");
        let newAssociationsNavButton = document.getElementById("new-associations-nav-button");
        
        if (newSelection === 'approved') {
            
            Associations.style.display = "block";
            NewAssociations.style.display = "none";
            approvedAssociationsNavButton.classList.add("active-button");
            newAssociationsNavButton.classList.remove("active-button");
        } else {
           Associations.style.display = "none";
           NewAssociations.style.display = "block";
           newAssociationsNavButton.classList.add("active-button");
           approvedAssociationsNavButton.classList.remove("active-button");
        }
        
    }
}

function redirectToAssociationgDetails(id) {
    window.location.href = `/volunteering-project/includes/php/association.php?id=${id}`;
}