<!DOCTYPE html>

<?php
// Include DB config file
require_once "connectionToDB.php";

// Define variables and initialize with empty values
$name = trim($_POST["name"]);
$last_name = trim($_POST["last_name"]);
$year_of_birth = trim($_POST["year_of_birth"]);
$driver_lic = $_POST["driver_lic"];
$organization_number = trim($_POST["organization_number"]);
$phone_part_one = $_POST["phone-number-part-one"];
$phone_part_two = $_POST["phone-number-part-two"];
$type = trim($_POST['type']);
$population = trim($_POST['population']);
$location = $_POST["location"];
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$confirm_password = trim($_POST["confirm_password"]);
$year_of_foundation = trim($_POST["year_of_foundation"]);
$description=trim($_POST["description"]);
$website = $_POST["website"];

$phone_number_err = $organization_number_err = $name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = $year_of_birth_err = $driver_lic_err = $year_of_foundation_err =  $website_err = "" ;

if (isset($_POST['is_organization']) && $_POST['is_organization'] == 'true') 
{
   $role = "Organization";
   $is_approved = 0;
} 
else 
{
    $role = "User";
   $is_approved = 1;
}
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Organization fields
    if ($role === "Organization") 
    {
        if (empty($organization_number) || !is_numeric($organization_number)) {
            $organization_number_err = "Please enter valid organization number";
        }
        
        if (empty($type) || $type == "Select Type") {
            $type_err = "Please select organization type";
        }
        
        if (empty($population) || $population == "Select Population") {
            $population_err = "Please select organization population";
        }
        
        if (empty($location) || $location=="Select Location") {
            $location_err="Please enter Address location";
        }
        if( empty($year_of_foundation)|| $year_of_foundation >= "2020" || $year_of_foundation <= "1900" || !is_numeric($year_of_foundation)){
            $year_of_foundation_err = "Please enter a valid date of association foundation";
        }
        if( empty($website))    {
            $website_err = "Please enter a valid assocoation website";
        }
    } 
    else
    // User fields 
    {
        if (empty($last_name)) {
            $last_name_err = "Please enter a last name.";
        }
        if (empty($year_of_birth) || $year_of_birth >= "2015" || $year_of_birth <= "1929" || !is_numeric($year_of_birth) )  {
            $year_of_birth_err = "Please enter a valid year of birth";
        }
        //if (empty($driver_lic) ) {
        //    $driver_lic_err = "Please select if you have/haven't a driving license";
        //}
    }
    //fields related to both - association and user

    if (empty($phone_part_one) || empty($phone_part_two)) 
        $phone_number_err = "Please enter phone number";
    else 
    {
            if (is_numeric($phone_part_one) == "0" || is_numeric($phone_part_two) == "0")
                $phone_number_err = "Invalid phone number1";
            // ==3 for cell phoned & ==1 for home phones like: 03-9545821
            else if ((ceil(log10((double)$phone_part_one)) == 2 )|| ((ceil(log10((double)$phone_part_one)) == 1) && ceil(log10((double)$phone_part_two)) == 7))   
                $phone_number = $phone_part_one.'-'.$phone_part_two;
            else
                $phone_number_err = "Invalid phone number2 ";
    }

    // Validate name
    if (empty($name)) {
        $name_err = "Please enter a name.";
    }

    if (strlen($name) > 25) {
        $name_err = "Please enter a name shorter then 25 chars.";
    }

    if (strlen($last_name) > 25) {
        $name_err = "Please enter a last name shorter then 25 chars.";
    }

    if (empty($email)) {
        $email_err = "Please enter Email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE email = '$email'";

        $result = $conn->query($sql);
        // Check if email exists, if yes print error
        if ($result->num_rows > 0) {
            $email_err = "Sorry, This email is already taken.";
        }
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif(strlen($password) < 6) {
        $password_err = "Password must have at least 6 characters.";
    }

    // Validate password strength
    //preg_match — Perform a regular expression match
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {
        $password_err ="Password should be at least 6 characters  length <br> At least one upper case letter <br> At least one lower case letter <br> At least one number <br> At least one special character.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm password.";
    } else {
        if (empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    //Profile picture handling
    $file = $_FILES['image']['tmp_name'];
                            
    if (is_uploaded_file($file)) {
        $check = getimagesize($file);
        if ($check !== false) {
            $data = file_get_contents($file);
            $profile_picture_base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
            
        } else {
            $profile_picture_err = "file is not an image";
        }                        
    } else {
        // Use default profile picture
        $profile_picture_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAScAAAEnCAMAAADchHVzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDFGODkyNjU2NTFCMTFFNTg1Q0JDM0U2MTgzNTQyMUQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDFGODkyNjY2NTFCMTFFNTg1Q0JDM0U2MTgzNTQyMUQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0MUY4OTI2MzY1MUIxMUU1ODVDQkMzRTYxODM1NDIxRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0MUY4OTI2NDY1MUIxMUU1ODVDQkMzRTYxODM1NDIxRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnmMEbAAAACxUExURcXF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';
    }
    
    // Registrat form handling
    
    
    $form = $_FILES['registrar-form']['tmp_name'];
    
    if (is_uploaded_file($form)) {
        $check = filesize($form);
        if ($check !== false) {
            $data = file_get_contents($form);
            $registrar_form = 'data:' . $file_type . ';base64,' . base64_encode($data);
            
        } 
    }
                         

   
    
   

    // Check input errors before inserting in database
    if (empty($name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($profile_picture_err) && empty($year_of_birth_err) && empty($driver_lic_err) && empty($year_of_foundation_err) && empty($organization_number_err) && empty ($website_err))
    {
        // Encrypt password before inserting to DB
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, last_name, email, password, profile_image, role, is_approved, organization_number, phone, population, type, year_of_birth,driver_lic, year_of_foundation, description, registrar_form , location ,  website) VALUES ('".$name."', '".$last_name."','".$email."','".$hashedPassword."','".$profile_picture_base64."','".$role."','".$is_approved."', '$organization_number', '$phone_number', '$population', '$type', '$year_of_birth', '$driver_lic', '$year_of_foundation', '$description' , '$registrar_form', '$location', '$website')";
        
        if ($conn->query($sql) == FALSE)
        {
            echo "<script> 
            alert('Something went wrong. Please try again later. Error is: $conn->error); 
            </script>";
            
            //exit();
        } 
        else //User was created succesfully, popup message, start a new session and redirect to main page
        {
            echo "<script>
                sessionStorage.setItem('is_organization_register', false);
                alert('Welcome to Good People Doing Good.');
                window.location.href='login.php';
             </script>";
        }

    }
    //else
    //{
        //echo "<script> alert('Oops, Something went wrong, please try again');</script>";
    //}
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
    <script src="/volunteering-project/js/common.js"></script>
    <script src="/volunteering-project/js/register.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBN60HXzpDZYJem-0OnEHB91MkZH45qqqk&libraries=places&callback=initAutocomplete" async defer></script>  

    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/forms.css">
</head>
<body>
    <div id="menu"></div>
    <div class="content">
        <h1>Sign Up</h1>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="center_div scrolling-div" enctype="multipart/form-data">

            <div class="form-group">
                <label>Sign up as organization</label>
                <input id="isOrganizationCheckbox" type="checkbox" class="form-check-input" name="is_organization" value="true">
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div id="last-name" class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>
            
            <div id="organization-number" style="display:none" class="form-group <?php echo (!empty($organization_number_err)) ? 'has-error' : ''; ?>">
                <label>Organization Number</label>
                <input type="text" name="organization_number" class="form-control" value="<?php echo $organization_number; ?>">
                <span class="help-block"><?php echo $organization_number_err; ?></span>
            </div>
            <div id="organization-registrat-form" style="display:none" class="form-group <?php echo (!empty($registrar_form_err)) ? 'has-error' : ''; ?>">
                <label>Upload the Registrar of Asociations Form </label>
                <input type="file" name="registrar-form" id="registrar-form" class="form-control form-control-file">
                <span style="color: #a94442;"><?php echo $registrar_form_err; ?></span>
            </div>
            <div id="phone-number"  class="form-group <?php echo (!empty($phone_number_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <div style="display: flex; flex-direction: row;">
                    <input style="width: 90px" minlength="2" maxlength="3" placeholder="2-3 digits" type="text" name="phone-number-part-one" class="form-control" value="<?php echo $phone_part_one; ?>">
                    <span style="padding:8px">-</span>
                    <input style="width: 170px" minlength="7" maxlength="7" placeholder="7 digits" type="text" name="phone-number-part-two" class="form-control" value="<?php echo $phone_part_two; ?>">
                    <span class="help-block"><?php echo $phone_number_err; ?></span>
                </div>
                
            </div>
            <div id="association-website" style="display:none" class="form-group <?php echo (!empty($website_err)) ? 'has-error' : ''; ?>">
                <label>Association Website</label>
                <input type="url" name="website" class="form-control" placeholder="https://www.association-name.org.il" value="<?php echo $website; ?>">
                <span class="help-block"><?php echo $website_err; ?></span>
            </div>
             <div id="organization-foundation-year" style="display:none" class="form-group <?php echo (!empty($year_of_foundation_err)) ? 'has-error' : ''; ?>"> 
                <label >Year of association foundation</label>
                <input type="text" name="year_of_foundation" class="form-control" value="<?php echo $year_of_foundation; ?>">
                <span class="help-block"><?php echo $year_of_foundation_err; ?></span>
            </div>
            <div id="select-activity" style="display:none" class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                <label>Activity</label>
                <select class="select form-control" id="select-activity-type" name="type" >
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
                <span class="help-block"><?php echo $type_err; ?></span>
            </div>
            <div id="select-population" style="display:none" class="form-group <?php echo (!empty($population_err)) ? 'has-error' : ''; ?>">
                <label for="size">Population</label>
                
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
                <span class="help-block"><?php echo $population_err; ?></span>
            </div>

            
             
            <div id="year-of-birth" class="form-group <?php echo (!empty($year_of_birth_err)) ? 'has-error' : ''; ?>"> 
                <label >Year of Birth</label>
                <input type="text" name="year_of_birth" class="form-control" value="<?php echo $year_of_birth; ?>">
                <span class="help-block"><?php echo $year_of_birth_err; ?></span>
            </div>
            <div id="driver-lic-chk" class="form-group" >
                <label>Do you have a driving license?</label><br>
                <input type="radio" id="yes"  name="driver_lic" value="1" <?php if (isset($_POST['driver_lic']) && $_POST['driver_lic'] == 'Yes')  echo ' checked="checked"';?> />
                <label for="yes">Yes</label>
                <input type="radio" id="no" name="driver_lic" value="0" <?php if (isset($_POST['driver_lic']) && $_POST['driver_lic'] == 'No')  echo ' checked="checked"';?> />
                <label for="no">No</label>
                <span class="help-block"><?php echo $driver_lic_err; ?></span>
            </div> 

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                <label>Description</label>
                <textarea class="form-control" cols="40" id="description"  name="description" placeholder="Tell us about you, how would you like to contribute or to gain from joining Doing Good" rows="6"><?php if(isset($_POST['description'])) {echo htmlentities ($_POST['description']); }?></textarea>
                <span class="help-block"><?php echo $description_err; ?></span>
            </div>
            <div class="form-group" id="location-autocomplete" style="display: none;">
                <label>Office Address</label>
                <input id="autocomplete" class="form-control" id="location" name="location" placeholder="Enter address" type="text" value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>" />
                <span class="help-block"><?php echo $location_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <div class="form-group">
                <label>Choose Profile Picture</label>
                <input type="file" name="image" id="image"class="form-control form-control-file">
                <span style="color: #a94442;"><?php echo $profile_picture_err; ?></span>
            </div>

            
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>