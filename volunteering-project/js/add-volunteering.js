$(document).ready(function() {
    //$("#redirect-to-login").on('touchstart click', function() {
    //    window.location = "/volunteering-project/includes/php/login.php";
    //});
    //$("#redirect-to-profile").on('touchstart click', function() {
    //    window.location = "/volunteering-project/includes/php/profile.php";
    //});
    //hidevolunteeringfields('status');
});

function hidevolunteeringfields(status)
{
    let volunteeringfields = document.getElementById("allFields");
    if(status === 'hide')
    {
        //volunteeringfields.style.backgroundColor = "red";
        volunteeringfields.style.display = "none";
        
    }
    else
    {
        //volunteeringfields.style.backgroundColor = "yellow";
        volunteeringfields.style.display = "block";

    }
}



