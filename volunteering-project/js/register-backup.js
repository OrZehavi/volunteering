$( document ).ready(function() {
    

    if (sessionStorage.getItem("is_organization_register") === "true") {
        $('#isOrganizationCheckbox').attr("checked", true);
        viewOrganizationFields();
    } else {
        $('#isOrganizationCheckbox').attr("checked",false);
    }
    
    $('input:checkbox').change(function () {
        if ($(this).is(':checked')) {
            viewOrganizationFields();
        } else {
            viewUserFields();
        }
    });
});

function viewOrganizationFields() {
    let selectActivity = document.getElementById('select-activity');
    let lastName = document.getElementById('last-name');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let organizationPhoneNumber = document.getElementById('organization-phone-number');
    let locationAutocomplete = document.getElementById('location-autocomplete');

    sessionStorage.setItem("is_organization_register", true);
    selectActivity.style.display = "block";
    lastName.style.display = "none";
    selectPopulation.style.display = "block";
    organizationNumber.style.display = "block";
    organizationPhoneNumber.style.display = "block";
    locationAutocomplete.style.display = "block";
}

function viewUserFields() {
    let selectActivity = document.getElementById('select-activity');
    let lastName = document.getElementById('last-name');
    let selectPopulation = document.getElementById('select-population');
    let organizationNumber = document.getElementById('organization-number');
    let organizationPhoneNumber = document.getElementById('organization-phone-number');
    let locationAutocomplete = document.getElementById('location-autocomplete');

    sessionStorage.setItem("is_organization_register", false);
    selectActivity.style.display = "none";
    lastName.style.display = "block";
    selectPopulation.style.display = "none";
    organizationNumber.style.display = "none";
    organizationPhoneNumber.style.display = "none";
    locationAutocomplete.style.display = "none";

}