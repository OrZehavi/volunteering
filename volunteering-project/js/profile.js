let url = new URL(window.location.href);
let currentUserNavSelection;

$( document ).ready(function() {
    if (sessionStorage.getItem("is_user_exist") === "true") 
    {
        if (sessionStorage.getItem("loggedin") != "1" || sessionStorage.getItem("userid") !== url.searchParams.get("user")) 
        {
            // The user not logged in or watching someone else profile
            // therfore disable input fields from typing
            $("#updateProfile :input").prop("disabled", true);
        }
        changeToggleBarSelection('profile');
            if( sessionStorage.getItem("display_fields_for") == "Organization")
            {
                viewOrganizationFields();
            }
            else
            {
                viewUserFields();
            }
    }
    else 
    {
        let content = document.getElementById("content");
        content.style.display = "none";
        let userNotExist = document.getElementById("user-not-exist");
        userNotExist.style.display = "block";

    }
});

function changeToggleBarSelection(newSelection) {
    if (newSelection !== currentUserNavSelection) {
        currentUserNavSelection = newSelection;
        let updateProfile = document.getElementById("updateProfile");
        let manageVolunteerings = document.getElementById("manageVolunteerings");
        let userProfileNavButton = document.getElementById("user-profile-nav-button");
        let userVolNavButton = document.getElementById("user-vol-nav-button");
    
        if (newSelection === 'profile') {
            updateProfile.style.display = "block";
            manageVolunteerings.style.display = "none";
            userProfileNavButton.classList.add("active-button");
            userVolNavButton.classList.remove("active-button");
        } else {
            updateProfile.style.display = "none";
            manageVolunteerings.style.display = "block";
            userVolNavButton.classList.add("active-button");
            userProfileNavButton.classList.remove("active-button");
        }
        
    }
}

function redirectToVolunteeringDetails(id) {
    window.location.href = `/volunteering-project/includes/php/volunteering.php?id=${id}`;
}

function viewOrganizationFields()
{

    let lastName = document.getElementById('last-name');
    let birthYear = document.getElementById('year-of-birth');
    let driverLic = document.getElementById('driver-lic-chk');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let phoneNumber = document.getElementById('phone');
    let locationAutocomplete = document.getElementById('location-autocomplete');
    let foundationYear = document.getElementById('organization-foundation-year');
    let description = document.getElementById('description');
    let registrarForm = document.getElementById('organization-registrat-form');
    let webSite = document.getElementById('association-website');
            
    sessionStorage.setItem("is_organization_register", true);
    lastName.style.display = "none";
    birthYear.style.display = "none";
    driverLic.style.display ="none";
    selectPopulation.style.display = "block";
    organizationNumber.style.display = "block";
    phoneNumber.style.display = "block";
    locationAutocomplete.style.display = "block";
    foundationYear.style.display = "block";
    description.style.display = "block";
    registrarForm.style.display ="block";
    webSite.style.display ="block";
}

function viewUserFields()
{
    let lastName = document.getElementById('last-name');
    let birthYear = document.getElementById('year-of-birth');
    let driverLic = document.getElementById('driver-lic-chk');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let phoneNumber = document.getElementById('phone');
    let locationAutocomplete = document.getElementById('location-autocomplete');
    let foundationYear = document.getElementById('organization-foundation-year');
    let description = document.getElementById('description');
    let registrarForm = document.getElementById('organization-registrat-form');
    let webSite = document.getElementById('association-website');
    
    sessionStorage.setItem("is_organization_register", false);
    lastName.style.display = "block";
    birthYear.style.display = "block";
    driverLic.style.display = "block";
    selectPopulation.style.display = "none";
    organizationNumber.style.display = "none";
    phoneNumber.style.display = "block";
    locationAutocomplete.style.display = "none";
    foundationYear.style.display = "none";
    description.style.display = "block";
    registrarForm.style.display ="none";
    webSite.style.display ="none";

}
