const times = {
    allDays: 'all',
    today: 'today',
    month: 'month',
    year: 'year'
};

var selectedTimeGlobal = times.allDays;
var selectedPopulationGlobal = '';
var selectedActivityGlobal = '';
var currentUserNavSelection;
var map;
var infoWindow = null;

$( document ).ready(function() {
    changeToggleBarSelection('list');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((setCenter));
    }    
    
    getVolunteeringsLocation();
    $("#get-all-volunteers").on('touchstart click', function() {
        removeActiveColour();
        setCookie("selectedTime", times.allDays, 1);
        setCookie("selectedPopulation", '', 1);
        setCookie("selectedActivityType", '', 1);
        selectedTimeGlobal = times.allDays;
        selectedPopulationGlobal = '';
        selectedActivityGlobal = '';
        
        $("#select-population").val('1');
        $("#select-activity-type").val('1');
        $.each($("#volunteerings").find("tr"), function(index, tr) {
            let selector = '#' + tr.id;
            $(selector).show();
        });
    });
    
    $("#get-today-volunteers").on('touchstart click', function() {
        removeActiveColour();
        let todayButton = document.getElementById("get-today-volunteers");
        todayButton.style.background = "#2e6da4";
        
        selectedTimeGlobal = times.today;
        setCookie("selectedTime", times.today, 1);
        filterAllRows();
    });
    
    $("#get-this-month-volunteers").on('touchstart click', function() {
        removeActiveColour();
        let monthButton = document.getElementById("get-this-month-volunteers");
        monthButton.style.background = "#2e6da4";
        
        selectedTimeGlobal = times.month;
        setCookie("selectedTime", times.month, 1);
        filterAllRows();
    });
    
    $("#get-this-year-volunteers").on('touchstart click',function() {
        removeActiveColour();
        let yearButton = document.getElementById("get-this-year-volunteers");
        yearButton.style.background = "#2e6da4";  

        selectedTimeGlobal = times.year;
        setCookie("selectedTime", times.year, 1);
        filterAllRows();
    });
    
    $('#select-population').on('change', function() {
        setCookie("selectedPopulation", this.value, 1);
        selectedPopulationGlobal = this.value;
        filterAllRows();
    });
    
    $('#select-activity-type').on('change', function() {
        setCookie("selectedActivityType", this.value, 1);
        selectedActivityGlobal = this.value;
        filterAllRows();
    });
});

function filterAllRows() {
    
    $.each($("#volunteerings").find("tr"), function(index, tr) {
        let volunteeringDate = $(tr).children()[2].innerText;
        let date = volunteeringDate.split(' ')[0].split('-');
        let year = date[0];
        let month = date[1];
        let day = date[2];
        let population = $(tr).children()[5].innerText;
        let activityType = ($(tr).children()[6].innerText);
        let selector = '#' + tr.id;

        if (shouldFilterByTime(year,month,day) || shouldFilterByPopulation(population) || shouldFilterByActivityType(activityType)) {
            $(selector).hide();
        } else {
            $(selector).show();
        }
    });
}

function removeActiveColour() {
    let todayButton = document.getElementById("get-today-volunteers");
    let monthButton = document.getElementById("get-this-month-volunteers");
    let yearButton = document.getElementById("get-this-year-volunteers");

    todayButton.style.background = "#337ab7";
    monthButton.style.background = "#337ab7";  
    yearButton.style.background = "#337ab7";  

}

function shouldFilterByPopulation(population) {
    let selectedPopulation = getCookie("selectedPopulation");
    if (selectedPopulation === null) {
        selectedPopulation = selectedPopulationGlobal;
    }
    let isPopulationEmpty = selectedPopulation === null || selectedPopulation === '' ? true : false;
    
    return !isPopulationEmpty && selectedPopulation !== population;
}

function shouldFilterByActivityType(activityType) {
    let selectedActivityType = getCookie("selectedActivityType");
    if (selectedActivityType === null) {
        selectedActivityType = selectedActivityGlobal;
    }
    let isActivityTypeEmpty = selectedActivityType === null || selectedActivityType === '' ? true : false;
    
    return !isActivityTypeEmpty && selectedActivityType !== activityType;
}

function shouldFilterByTime(year, month, day) {
    let filter = false;

    let selectedTime = getCookie("selectedTime");
    if (selectedTime === null) {
        selectedTime = selectedTimeGlobal;
    }
    
    switch (selectedTime) {
        case times.today:
            filter = !isToday(year, month, day);
            break;
        case times.month:
            filter = !isSameMonth(month, year);
            break;
        case times.year:
            filter = !isSameYear(year);
            break;
        case times.allDays:
            break;
    }
     
    return filter;
}

function isToday(year, month, day) {
    let today = new Date();
    let date = today.toISOString().split('T')[0].split('-');
    
    return day == date[2] && isSameMonth(date[1],date[0]) && isSameYear(date[0]);
}

function isSameMonth(month, year) {
    let today = new Date();
    let date = today.toISOString().split('T')[0].split('-');
    
    return month == date[1] && isSameYear(date[0]);
}

function isSameYear(year) {
    let today = new Date();
    let date = today.toISOString().split('T')[0].split('-');
    
    return year == date[0];
}

function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  
  document.cookie = name + "=" + (value || "") + expires + "; path=../includes/get-volunteerings.php";
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  
  return null;
}

function setSelectedPopulationByCookie(populationName) {
    let index = 0;
    
    let element = document.getElementById('select-population');
    let options = element.options;
    for (i = 0; i < options.length; i++){
        if (options[i].text === populationName){
            index = i;
        }
    }
    
    element.selectedIndex  = index;
}

function setSelectedTimeByCookie(time) {
    if (time === times.today) {
        let todayButton = document.getElementById("get-today-volunteers");
        todayButton.style.background = "#2e6da4";
    }else if (time === times.month) {
        let monthButton = document.getElementById("get-this-month-volunteers");
        monthButton.style.background = "#2e6da4";
    }else if (time === times.year) {
        let yearButton = document.getElementById("get-this-year-volunteers");
        yearButton.style.background = "#2e6da4"; 
    }
}

function setSelectedActivityTypeByCookie(activityType) {
    let index = 0;
    
    let element = document.getElementById('select-activity-type');
    let options = element.options;
    for (i = 0; i < options.length; i++){
        if (options[i].text === activityType){
            index = i;
        }
    }
    
    element.selectedIndex  = index;
}

function changeToggleBarSelection(newSelection) {
    if (newSelection !== currentUserNavSelection) {
        currentUserNavSelection = newSelection;
        let listElement = document.getElementById("list-element");
        let mapElement = document.getElementById("map-element");
        let listNavButton = document.getElementById("list-button");
        let mapNavButton = document.getElementById("map-button");
    
        if (newSelection === 'list') {
            listElement.style.display = "block";
            mapElement.style.display = "none";
            listNavButton.classList.add("active-button");
            mapNavButton.classList.remove("active-button");
        } else {
            listElement.style.display = "none";
            mapElement.style.display = "block";
            mapNavButton.classList.add("active-button");
            listNavButton.classList.remove("active-button");
        }
        
    }
}

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: {
      lat: 31.975194,
      lng: 34.8133206
    },
    zoom: 5
  });

    
}

function setCenter(position) {
    map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
    map.setZoom(14);
}

function getVolunteeringsLocation() {
    let volunteeringsDetails = sessionStorage.getItem("get_volunteerings_details");
    if (volunteeringsDetails) {
        volunteeringsDetails = JSON.parse(volunteeringsDetails);
        console.log(volunteeringsDetails);
        for (let i = 0; i < volunteeringsDetails.length; i++) {
            createMarker(volunteeringsDetails[i], 'This is volunteering ' + i);
        }
    }

}  

function createMarker(volunteeringDetails, content) {
    // Funtion input is latlng = volunteering position, content = content of the infowindow
    let volunteeringDate = new Date(volunteeringDetails.date + " " + volunteeringDetails.time);
    let currentDate = new Date();
    if (volunteeringDate >= currentDate) {
        let position = new google.maps.LatLng(volunteeringDetails.lat, volunteeringDetails.lng);
        let volunteeringId = volunteeringDetails.id;
        let marker = new google.maps.Marker({
            position: position,
            map: map,
            animation: google.maps.Animation.DROP,
            url: `/volunteering-project/includes/php/volunteering.php?id=${volunteeringId}`
        });
        google.maps.event.addListener(marker, 'click', function () {
            window.location.href = this.url;
        });
    }
}


function redirectToVolunteeringDetails(id) {
    window.location.href = `/volunteering-project/includes/php/volunteering.php?id=${id}`
}


Date.prototype.addHours = function(h){
    this.setHours(this.getHours()+h);
    
    return this;
}