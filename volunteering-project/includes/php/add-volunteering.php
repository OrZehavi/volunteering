<!DOCTYPE html>

<?php
// Initialize the session
session_start();

//Check if the user is already logged in and save his user id
if (isset($_SESSION["name"])) {
    $user_id= $_SESSION["user_id"];
    $logged_in=true;
    $is_approved= $_SESSION["is_approved"];
    $role = $_SESSION["role"];
}
else
    {
    //echo "Only logged in users can add volunteering";
    $logged_in=false;
    }

// Include DB config file
require_once "connectionToDB.php";

// Define variables 
$title = trim($_POST["title"]);
$description = trim($_POST['description']);
$date = date('Y-m-d',strtotime($_POST['date']));
$time = $_POST['time'];
$duration = trim($_POST['duration']);
$location = trim($_POST["location"]);
$lat = trim($_POST["lat"]);  
$lng = trim($_POST["lng"]);  
$population = trim($_POST['population']);
$type = trim($_POST['type']);
$num_of_participants = $_POST['num_of_participants'];
$title_err = $description_err =$date_err = $time_err = $duration_err = $location_err = $population_err = $type_err =  $num_of_participants_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //Validations
    if (empty($title))
        $title_err="Please enter title";

    if (empty($description))
        $description_err="Please enter description";
    
    if (empty($date) || $date<"2020-06-01")
        $date_err="Please select date";
        
    if (empty($time))
        $time_err="Please enter time";
        
    if (empty($duration) || !is_numeric($duration))
        $duration_err="Please enter the number of hours the activity will take place.";
        
    if (empty($description))
        $title_err="Please enter description";
        
    if (empty($location) || $location=="Select Location")
        $location_err="Please select location";
        
    if (empty($population) || $population=="Select Population")
        $population_err="Please select population";
        
    if (empty($type) || $type =="Select Type")
        $type_err="Please select volunteering type";
        
    if (empty($num_of_participants))
        $num_of_participants_err="Please enter the maximum number of participants";
    if(!is_numeric($num_of_participants) || ($num_of_participants < 0) )
        $num_of_participants_err = "Number of participants mush be a positive number";

    //check all fields were fiiled and witout errors
    if(empty($title_err)&& empty($description_err) && empty($date_err)&&empty($duration_err)&&empty($location_err)&&empty($population_err)&&empty($type_err)&&empty($num_of_participants_err))
    {
       // Insert new volunteering
        $sql="insert into volunteerings( title, description, date, time, duration, location, population, type, num_of_participants, user_id, lat, lng) values ( '".$title."', '".$description."', '".$date."', '".$time."','".$duration."','".$location."','".$population."','".$type."','".$num_of_participants."', '".$user_id."', '".$lat."', '".$lng."')";

        if(mysqli_query($conn, $sql))
        {
            //Volunteering added successfully."
            echo "<script> alert('Volunteering added successfully');
            window.location.href='get-volunteerings.php';
             </script>";
        }    
            
        else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);}
    }
    
    $conn -> close();
}

?>

<html lang="en">
<head><meta charset="gb18030">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/volunteering-project/css/menu.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/add-volunteering.css">
	<script src="/volunteering-project/js/common.js"></script>
	<script src="/volunteering-project/js/add-volunteering.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBN60HXzpDZYJem-0OnEHB91MkZH45qqqk&libraries=places&callback=initAutocomplete" async defer></script>  
    <!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 

<!--Font Awesome (for the calander icon)-->
<link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />

<!-- Inline CSS based on choices in "Settings" tab -->
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/forms.css">

<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <script>
    	$(document).ready(function(){
		var date_input=$('input[name="date"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'mm/dd/yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
    </script>
    

   <title>Volunteering Platform</title>
</head>
    <body>
        <div id="menu"></div>
        <div class="container center_div">
            <div class="content">
            <h1>Add Volunteering</h1>
            <?php
            $hide=false;
            if (!$logged_in) 
            {
                echo '<h4> Please note that only registered users/associations can add Volunteerings </h4>';
                echo '<a class="btn btn-primary" href="/volunteering-project/includes/php/login.php"> Press here to sign in/sign up </a>';
                $hide=true;
            }
            else
            {
                //if the organization is logged in but not approved yet
                if ($role == "Organization" and $is_approved == 0)   
                {
                    echo '<h4>Please note that only approved associations can add Volunteerings. </h4> ';
                    echo '<a  class="btn btn-primary" href="/volunteering-project/includes/php/profile.php?user='.$user_id.'"> Check approval status in your profile</a>';
                    $hide=true;
                }
            }
            ?>
            
            <div id="allFields" class="container-fluid scrolling-div ">
                <?php
                    if ($hide == true)
                    {
                        echo '<script> hidevolunteeringfields("hide"); </script>';
                    }
                    else
                    echo '<script> hidevolunteeringfields("hide222"); </script>';
                ?>
                <div class="row">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="center_div">
                        <div class="form-group">
                            <label>Title</label>
                            <!-- "value=php echo... " in each field is for keeping the inserted values if the submit failed -->
                            <input class="form-control" id="title" name="title" placeholder="Enter Voulnteering title" type="text" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '' ?>"/>
                            <span class="asteriskField"><?php echo $title_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="7" style="max-width:270px; min-width:270px;" id="description" name="description"  placeholder="Enter volunteering description"><?php if(isset($_POST['description'])) { echo htmlentities ($_POST['description']); }?></textarea>
                            <span class="asteriskField"><?php echo $description_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 requiredField" for="date">Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                     <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control" id="date" name="date" placeholder="MM/DD/YYYY" type="text" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>"/>
                            </div>
                             <span class="asteriskField"><?php echo $date_err; ?></span>
                        </div>
                        
                        <div class="md-form md-outline">
                        <label>Time</label>
                          <input type="time" id="time" name="time" class="form-control"  placeholder="Enter volunteering time" value="<?php echo isset($_POST['time']) ? $_POST['time'] : '' ?>">
                        <span class="asteriskField"><?php echo $time_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Duration in hours</label>
                            <input class="form-control" id="duration" name="duration" placeholder="Hours number" type="number" min="0" value="<?php echo isset($_POST['duration']) ? $_POST['duration'] : '' ?>" />
                            <span class="asteriskField"><?php echo $duration_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input id="autocomplete" class="form-control" id="location" name="location" placeholder="Enter address" type="text" value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>" />
                            <span class="asteriskField"><?php echo $location_err; ?></span>
                        </div> 
                    
                        <input style="display: none" id="lat" name="lat" type="text">
                        <input style="display: none" id="lng" name="lng" type="text">

                        <div class="form-group">
                            <label>Population</label>
                            <select class="select form-control" id="population" name="population">
                                <option  selected disabled hidden style='display: none' value="Select Population">Select Population</option>
                                <option value="Seniors" <?php echo (isset($_POST['population']) && $_POST['population'] === "Seniors") ? 'selected' : ''; ?>>Seniors</option>
                                <option value="Teens and Kids" <?php echo (isset($_POST['population']) && $_POST['population'] === "Teens and Kids") ? 'selected' : ''; ?>>Teens and Kids</option>
                                <option value="Holocaust Survivors" <?php echo (isset($_POST['population']) && $_POST['population'] === "Holocaust Survivors") ? 'selected' : ''; ?>>Holocaust Survivors</option>
                                <option value="Patients" <?php echo (isset($_POST['population']) && $_POST['population'] === "Patients") ? 'selected' : ''; ?>>Patients</option>
                                <option value="People with Special Needs" <?php echo (isset($_POST['population']) && $_POST['population'] === "People with Special Needs") ? 'selected' : ''; ?>>People with Special Needs</option>
                                <option value="Families" <?php echo (isset($_POST['population']) && $_POST['population'] === "Families") ? 'selected' : ''; ?>>Families</option>
                                <option value="Minorities/Migrant Workers" <?php echo (isset($_POST['population']) && $_POST['population'] === "Minorities/Migrant Workers") ? 'selected' : ''; ?>>Minorities/Migrant Workers</option>
                                <option value="Animals" <?php echo (isset($_POST['population']) && $_POST['population'] === "Animals") ? 'selected' : ''; ?>>Animals</option>
                                <option value="Other" <?php echo (isset($_POST['population']) && $_POST['population'] === "Other") ? 'selected' : ''; ?>>Other</option>

                            </select>
                            <span class="asteriskField"><?php echo $population_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Activity Type</label>
                            <select class="select form-control" id="type" name="type" >
                                <option selected disabled hidden style='display: none' value="Select Type" >Select Type</option>
                                <option value="Lectures" <?php echo (isset($_POST['type']) && $_POST['type'] === "Lectures") ? 'selected' : ''; ?>>Lectures</option>
                                <option value="Helping the needy" <?php echo (isset($_POST['type']) && $_POST['type'] === "Helping the needy") ? 'selected' : ''; ?>>Helping the needy</option>
                                <option value="Mentoring" <?php echo (isset($_POST['type']) && $_POST['type'] === "Mentoring") ? 'selected' : ''; ?>>Mentoring</option>
                                <option value="Volunteering from home" <?php echo (isset($_POST['type']) && $_POST['type'] === "Volunteering from home") ? 'selected' : ''; ?>>Volunteering from home</option>
                                <option value="People with Special Needs" <?php echo (isset($_POST['type']) && $_POST['type'] === "People with Special Needs") ? 'selected' : ''; ?>>People with Special Needs</option>
                                <option value="Security &amp; Medicine" <?php echo (isset($_POST['type']) && $_POST['type'] === "Security and Medicine") ? 'selected' : ''; ?>>Security and Medicine</option>
                                <option value="Maintenance and renovation" <?php echo (isset($_POST['type']) && $_POST['type'] === "Maintenance and renovation") ? 'selected' : ''; ?>>Maintenance and renovation</option>
                                <option value="Animals" <?php echo (isset($_POST['type']) && $_POST['type'] === "Animals") ? 'selected' : ''; ?>>Animals</option>
                                <option value="Other" <?php echo (isset($_POST['type']) && $_POST['type'] === "Other") ? 'selected' : ''; ?>>Other</option>

                            </select>
                            <span class="asteriskField"><?php echo $type_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Max number of volunteers</label>
                            <input class="form-control" id="num_of_participants" name="num_of_participants" placeholder="Enter the maximum number of voulnteers" type="number" value="<?php echo $num_of_participants; ?>"/>
                            <span class="asteriskField"><?php echo $num_of_participants_err; ?></span>
                        </div>
                        <div style="overflow:hidden;">
                        <div class="form-group">
                            <?php 
                            if (!$logged_in) { 
                                echo '<a class="btn btn-default" href="/volunteering-project/includes/php/login.php">Login / Regiter to add new volunteering</a>';
                            } else { 
                                echo '<button type="submit"  class="btn btn-default" name="submit">Submit</button>';
                            }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </body>
</html>