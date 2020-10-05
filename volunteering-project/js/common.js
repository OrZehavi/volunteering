$(document).ready(function() {
    $( "#menu" ).load("../html/menu.html" );
    
    $("body").on("click", "#volunteeringTable tr", function () {
        let tableRows = $('table').find('tr');
         if (this != tableRows[0]) {
            $("#volunteeringDetailsModal").modal("show");
         }
    });
    
});

function initAutocomplete() {
    // This function is in use in register page & in add volunteering page
  autocomplete = new google.maps.places.Autocomplete(
    (document.getElementById('autocomplete')), {
      types: ['geocode'],
      componentRestrictions: { country: 'il' }
    });

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
    let place = autocomplete.getPlace();
    let formattedAddress = place.formatted_address;
    let lat = place.geometry.location.lat();
    let lng = place.geometry.location.lng();
    document.getElementById("autocomplete").value = formattedAddress;
    document.getElementById("lat").value = lat;
    document.getElementById("lng").value = lng;

}
