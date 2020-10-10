$( document ).ready(function() {
    changeUserTypeForm('user');    
});

function viewOrganizationFields() {
    let selectActivity = document.getElementById('select-activity');
    let lastName = document.getElementById('last-name');
    let birthYear = document.getElementById('year-of-birth');
    let driverLic = document.getElementById('driver-lic-chk');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let phoneNumber = document.getElementById('phone-number');
    let locationAutocomplete = document.getElementById('location-autocomplete');
    let foundationYear = document.getElementById('organization-foundation-year');
    let description = document.getElementById('description');
    let registrarForm = document.getElementById('organization-registrat-form');
    let webSite = document.getElementById('association-website');

    sessionStorage.setItem("is_organization_register", true);
    selectActivity.style.display = "block";
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

function viewUserFields() {
    let selectActivity = document.getElementById('select-activity');
    let lastName = document.getElementById('last-name');
    let birthYear = document.getElementById('year-of-birth');
    let driverLic = document.getElementById('driver-lic-chk');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let phoneNumber = document.getElementById('phone-number');
    let locationAutocomplete = document.getElementById('location-autocomplete');
    let foundationYear = document.getElementById('organization-foundation-year');
    let description = document.getElementById('description');
    let registrarForm = document.getElementById('organization-registrat-form');
    let webSite = document.getElementById('association-website');

    sessionStorage.setItem("is_organization_register", false);
    selectActivity.style.display = "none";
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

function changeUserTypeForm(newSelection) {
    let userBtn = document.getElementById("create-user-btn");
    let associationBtn = document.getElementById("create-association-btn");
    let nameLabel = document.getElementById("name-label");
    let roleInput = document.getElementById("role-input");
    
    if (newSelection === 'user') {
        nameLabel.innerHTML = 'First Name';
        userBtn.style.background = "#2e6da4";
        associationBtn.style.background = "#337ab7";
        roleInput.setAttribute('value', 'user');
        viewUserFields();

    } else {
        nameLabel.innerHTML = 'Name';
        userBtn.style.background = "#337ab7";
        associationBtn.style.background = "#2e6da4";
        roleInput.setAttribute('value', 'association');
        viewOrganizationFields();
    }
}

