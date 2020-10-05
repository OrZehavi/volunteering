<!DOCTYPE html>

<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $userId = htmlspecialchars($_GET["user"]);
    $get_user_details_query = "SELECT * FROM `users` WHERE `id`=".$userId."";
    $result = $conn->query($get_user_details_query);
    
    $get_volunteerings_details = "SELECT vol.*, u.name as first_name, u.last_name, u.email FROM `volunteerings` vol JOIN `users` u ON (u.id= vol.user_id)";
    $volunteerings_details_result = $conn->query($get_volunteerings_details);
    $get_volunteerings_to_users_query = "SELECT * FROM volunteerings_to_users";
    $get_participants_of_volunteerings_query = "select COUNT(*) as count, volunteering_id from volunteerings_to_users GROUP BY volunteering_id";
    $volunteerings_to_users_result = $conn->query($get_volunteerings_to_users_query);
    $participants_of_volunteerings_result = $conn->query($get_participants_of_volunteerings_query);
    $query_param_user = $_GET['user'];
    $user_role = $_SESSION["role"];
    
    if ($result->num_rows > 0) {
        echo '
            <script>
                sessionStorage.setItem("is_user_exist", "true");
            </script>';
        $is_user_exist = true;
        $row = $result->fetch_assoc();
        $role =  $row['role'];
        if ($role == "Organization")
        {
            echo '
            <script>
                sessionStorage.setItem("display_fields_for", "Organization");
                </script>';
        } else {
            echo '
            <script>
                sessionStorage.setItem("display_fields_for", "User");
            </script>';
        }
        
        $current_name = $row['name'];
        $current_last_name = $row['last_name'];
        $current_email = $row['email'];
        $current_year_of_birth = $row['year_of_birth'];
        $current_driver_lic = $row['driver_lic'];
        $current_phone = $row["phone"];
        $current_description= $row['description'];
        //phone number manipulation
        $arr = explode("-", $current_phone);
        $current_phone_part_one = $arr[0];
        $current_phone_part_two = $arr[1];
        $current_image = $row['profile_image'];
        $current_registrar_form = $row['registrar_form'];

         function  myfunction()
            {
                return $current_registrar_form;
            }

        // Organization only fields
        $current_organization_number = $row['organization_number'];
        $current_type = $row['type'];
        $current_population = $row['population'];
        $current_location = $row['location'];
        $current_password = $current_confirm_password = $row["password"];
        $current_year_of_foundation = $row['year_of_foundation'];
        $current_website = $row['website'];

    } else {
        $is_user_exist = false;

        echo '
            <script>
                sessionStorage.setItem("is_user_exist", false);
            </script>';
    }
    
    $new_name = trim($_POST["name"]);
    $new_last_name = trim($_POST["last_name"]);
    $new_email = trim($_POST["email"]);
    $new_year_of_birth = trim($_POST["year_of_birth"]);
    $new_driver_lic = $_POST["driver_lic"];
    $new_description=trim($_POST["description"]);
    $new_phone_part_one = $_POST["phone-number-part-one"];
    $new_phone_part_two = $_POST["phone-number-part-two"];
    //organization only fields
    $new_organization_number = trim($_POST["organization_number"]);
    $new_type = trim($_POST['type']);
    $new_population = trim($_POST['population']);
    $new_location = $_POST["location"];
    $new_password = $_POST["password"];
    $new_confirm_password = trim($_POST["confirm_password"]);
    $new_year_of_foundation = trim($_POST["year_of_foundation"]);
    $new_website = $_POST["website"];

    $phone_number_err = $organization_number_err = $name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = $year_of_birth_err = $driver_lic_err = $year_of_foundation_err =  $website_err = "" ;

// Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $file = $_FILES['image']['tmp_name']; // prifile image
        $form = $_FILES['registrar-form']['tmp_name'];  //resistrat association form
        
        if (password_verify($new_password, $current_password)) {
            $new_old_pass_match = true;
        } else {
            $new_old_pass_match = false;
        }
        
        if (password_verify($new_confirm_password, $current_confirm_password)) {
            $new_old_confirm_pass_match = true;
        } else {
            $new_old_confirm_pass_match = false;
        }
         //check if anything was changed
        if ($new_name != $current_name || $new_last_name != $current_last_name || $new_email != $current_email || is_uploaded_file($file) || $new_year_of_birth != $current_year_of_birth  || $new_driver_lic != $current_driver_lic || $new_description != $current_description  || $new_organization_number !=$current_organization_number || $new_type != $current_type || $new_population != $current_population || $new_location != $current_location ||  $new_year_of_foundation != $current_year_of_foundation || $new_website !=$current_website || $new_old_confirm_pass_match || $new_old_pass_match) 
        {
            echo $new_driver_lic;
            echo "-";
            echo $current_driver_lic;
            // Organization fields
            if ($role === "Organization") {
               if (empty($new_organization_number) || !is_numeric($new_organization_number)) {
                   $organization_number_err = "Please enter valid organization number";
               }
               if (empty($new_type) || $new_type == "Select Type") {
                   $type_err = "Please select organization type";
               }
               if (empty($new_population) || $new_population == "Select Population") {
                   $population_err = "Please select organization population";
               }
               if (empty($new_location) || $new_location=="Select Location") {
                   $location_err="Please enter Address location";
               }
               if ( empty($new_year_of_foundation)|| $new_year_of_foundation >= "2020" || $new_year_of_foundation <= "1900" || !is_numeric($new_year_of_foundation)){
                   $year_of_foundation_err = "Please enter a valid date of association foundation";
               }
               if ( empty($new_website)) {
                   $website_err = "Please enter a valid assocoation website";
               }
            } else {
            // User fields 
                if (empty($new_last_name)) {
                    $last_name_err = "Please enter a last name.";
                }
                if (empty($new_year_of_birth) || $new_year_of_birth >= "2015" || $new_year_of_birth <= "1929" || !is_numeric($new_year_of_birth) )  {
                    $year_of_birth_err = "Please enter a valid year of birth";
                }

                if (strlen($new_last_name) > 25) {
                $name_err = "Please enter a last name shorter then 25 chars.";
                }
            }
            //fields related to both - association and user
            if (empty($new_phone_part_one) || empty($new_phone_part_two)) {
                $phone_number_err = "Please enter phone number";
            } else {
                    if (is_numeric($new_phone_part_one) == "0" || is_numeric($new_phone_part_two) == "0")
                        $phone_number_err = "Invalid phone number1";
                    // ==3 for cell phoned & ==1 for home phones like: 03-9545821
                    else if ((ceil(log10((double)$new_phone_part_one)) == 2 )|| ((ceil(log10((double)$new_phone_part_one)) == 1) && ceil(log10((double)$new_phone_part_two)) == 7))   
                        $new_phone = $new_phone_part_one.'-'.$new_phone_part_two;
                    else
                        $phone_number_err = "Invalid phone number2 ";
            }
    
            if (empty($new_name)) {
                $name_err = "Please enter a name.";
            }
            
            if (strlen($new_name) > 25) {
                $name_err = "Please enter a name shorter then 25 chars.";
            }
            
            if (empty($new_email)) {
                $email_err = "Please enter Email.";
            } else {
                if ($new_email != $current_email){
                    $sql = "SELECT * FROM users WHERE email = '$new_email'";
                    $result = $conn->query($sql);
                    // Check if email exists, if yes print error
                    if ($result->num_rows > 0) {
                        $email_err = "Sorry, This email is already taken.";
                    }
                }
            }
            // Validate password
            if (empty($new_password) && empty($new_confirm_password)){
                //if no new pass was entered, use the original one
                $new_password = $current_password;
                $new_confirm_password = $current_confirm_password;
            } else {
                if (empty($new_password)) {
                    $password_err = "Please enter a password.";
                } elseif(strlen($new_password) < 6) {
                    $password_err = "Password must have at least 6 characters.";
                }
                // Validate password strength
                //preg_match — Perform a regular expression match
                $uppercase = preg_match('@[A-Z]@', $new_password);
                $lowercase = preg_match('@[a-z]@', $new_password);
                $number    = preg_match('@[0-9]@', $new_password);
                $specialChars = preg_match('@[^\w]@', $new_password);
        
                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($new_password) < 6) {
                    $password_err ="Password should be at least 6 characters  length <br> At least one upper case letter <br> At least one lower case letter <br> At least one number <br> At least one special character.";
                }
                // Validate confirm password
                if (empty($new_confirm_password)) {
                    $confirm_password_err = "Please confirm password.";
                } else {
                    if (empty(password_err) && ($new_password != $new_confirm_password)){
                        $confirm_password_err = "Password did not match.";
                    }
                }
                
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            }
            $file = $_FILES['image']['tmp_name']; // prifile image
            $form = $_FILES['registrar-form']['tmp_name'];  //resistrat association form
        
            //Profile picture handling
                                    
            if (is_uploaded_file($file)) {
                $check = getimagesize($file);
                if ($check !== false) {
                    $data = file_get_contents($file);
                    $profile_picture_base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
                    
                } else {
                    //use old image
                    $profile_picture_base64 = $current_image;
                }                        
            } else {
                //profile pic was not uploaded and no previous picture exist -> than use default image, else use original image
                if (empty ($current_image)) {
                    $profile_picture_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAScAAAEnCAMAAADchHVzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDFGODkyNjU2NTFCMTFFNTg1Q0JDM0U2MTgzNTQyMUQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDFGODkyNjY2NTFCMTFFNTg1Q0JDM0U2MTgzNTQyMUQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0MUY4OTI2MzY1MUIxMUU1ODVDQkMzRTYxODM1NDIxRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0MUY4OTI2NDY1MUIxMUU1ODVDQkMzRTYxODM1NDIxRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnmMEbAAAACxUExURcXF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';
                } else {
                    $profile_picture_base64 = $current_image;
                }
            }
    
            // Registrat form handling
             if (is_uploaded_file($form)) {
                 $check = filesize($form);
                 if ($check !== false) {
                     $data = file_get_contents($form);
                     $registrar_form = 'data:' . $file_type . ';base64,' . base64_encode($data);
                 } 
             } else {
                 //new file was not uploaded, than,if old form exist, use it
                 if (!empty($current_registrar_form))
                    $registrar_form = $current_registrar_form;
             }                   
             
    
            // Check input errors before inserting in database
            if (empty($name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($profile_picture_err) && empty($year_of_birth_err) && empty($driver_lic_err) && empty($year_of_foundation_err) && empty($organization_number_err) && empty ($website_err)) {
                $userId = $_SESSION["user_id"];
                //echo $new_password;
                
    
                // Prepare an update statement
                //$sql = "UPDATE `users` SET `name`='".$new_name."',`last_name`='".$new_last_name."',`email`='".$new_email."',`profile_image`='".$profile_picture_base64."' WHERE `id`='".$userId."'";
                $sql = "UPDATE `users` SET `name`='".$new_name."',`last_name`='".$new_last_name."',`email`='".$new_email."',`profile_image`='".$profile_picture_base64."',`password`='".$new_password."', `organization_number`='".$new_organization_number."', `phone`='".$new_phone."', `population`='".$new_population."', `year_of_birth`='".$new_year_of_birth."', `driver_lic`='".$new_driver_lic."', `year_of_foundation`='".$new_year_of_foundation."', `description`='".$new_description."', `registrar_form`='".$registrar_form."', `location`='".$new_location."', `website`='".$new_website."' WHERE `id`='".$userId."'";
                //echo "$sql";
                if ($conn->query($sql) == FALSE)
                {
                    echo "Something went wrong. Please try again later. Error is:".$conn->error;
                    exit();
                } else {
                    //Reload the updated data
                    $get_user_details_query = "SELECT * FROM `users` WHERE `id`=".$userId."";
                    $result = $conn->query($get_user_details_query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $current_name = $row['name'];
                        $current_last_name = $row['last_name'];
                        $current_email = $row['email'];
                        $current_year_of_birth = $row['year_of_birth'];
                        $current_driver_lic = $row['driver_lic'];
                        $current_phone = $row["phone"];
                        $current_description= $row['description'];
                        //phone number manipulation
                        $arr = explode("-", $current_phone);
                        $current_phone_part_one = $arr[0];
                        $current_phone_part_two = $arr[1];
                        $current_image = $row['profile_image'];
                        $current_registrar_form = $row['registrar_form'];
                        // Organization only fields
                        $current_organization_number = $row['organization_number'];
                        $current_type = $row['type'];
                        $current_population = $row['population'];
                        $current_location = $row['location'];
                        $current_password = $current_confirm_password = $row["password"];
                        $current_year_of_foundation = $row['year_of_foundation'];
                        $current_website = $row['website'];
                    }
                echo "<script>
                        alert('Updated user details');
                     </script>";
                }
            }
        }
    }
    $conn -> close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteering Project</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
    <script src="/volunteering-project/js/common.js"></script>
    <script src="/volunteering-project/js/profile.js"></script>

    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/profile.css">
</head>
<body>
    <div id="menu"></div>
    <div class="content" id="user-not-exist" style="display:none">
        <h1>Error</h2>
        <h2>User does not exist</h2>
    </div>
    <div class="content" id="content">
        <div class="btn-group" style="width:100%">
          <button id="user-profile-nav-button" class="button-reset navigation-button" style="width:50%" onclick=changeToggleBarSelection('profile')>User Profile</button>
          <button id="user-vol-nav-button" class="button-reset navigation-button" style="width:50%" onclick=changeToggleBarSelection('vol')>User Volunteerings</button>
        </div>
        <div class="scrolling-div">
            <form id="updateProfile" style="display:block" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] .$query_string; ?>" method="post" class="center_div scrolling-div" enctype="multipart/form-data">
                <div class="panel panel-default">
                  <div class="panel-body text-center">
                   <img src="<?php echo $current_image; ?>" class="img-circle profile-avatar" alt="User avatar">
              </div>
            </div>
            
            <div class="panel panel-default">
            <div class="panel-body">
                
              <div  class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="<?php echo $current_name ?>">
                </div>
                <span class="help-block"><?php echo $name_err; ?></span>
              </div>

              <div id="last-name" class="form-group">
                <label class="col-sm-2 control-label">Last Name</label>
                <div  class="col-sm-10">
                  <input  type="text" name="last_name" class="form-control" value="<?php echo $current_last_name ?>">
                </div>
                <span class="help-block"><?php echo $last_name_err; ?></span>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="text" name="email" class="form-control" value="<?php echo $current_email ?>">
                </div>
                <span class="help-block"><?php echo $email_err; ?></span>
              </div>

             <div id="phone" class="form-group">
                 <label class="col-sm-2 control-label">Phone number</label>
                <div style="display: flex; flex-direction: row;" class="col-sm-10">
                    <input style="width: 90px" minlength="2" maxlength="3" placeholder="2-3 digits" type="text" name="phone-number-part-one" class="form-control" value="<?php echo $current_phone_part_one; ?>"> 
                    <span style="padding:8px"> - </span> 
                    <input style="width: 170px" minlength="7" maxlength="7" placeholder="7 digits" type="text" name="phone-number-part-two" class="form-control" value="<?php echo $current_phone_part_two; ?>">
                </div>
                <span class="help-block"><?php echo $phone_number_err; ?></span>
            </div>

            <div id="year-of-birth" class="form-group">
                 <label class="col-sm-2 control-label">Year of Birth</label>
                <div id="year-of-birth" class="col-sm-10" <?php echo (!empty($current_year_of_birth_err)) ? 'has-error' : ''; ?>"> 
                    <input type="text" name="year_of_birth"  class="form-control" value="<?php echo $current_year_of_birth; ?>">
                </div>
                <span class="help-block"><?php echo $year_of_birth_err; ?></span>
            </div>

            <div id="driver-lic-chk" class="form-group">
                <label class="col-sm-2 control-label">Do you have a driving license?</label>
                <div id="driver-lic-chk" class="col-sm-10" >
                    <input type="radio" id="yes"  name="driver_lic" value="1" <?php if ($current_driver_lic == 1)  echo ' checked="checked"';?> />
                    <label for="yes">Yes</label>
                    <input type="radio" id="no" name="driver_lic" value="0" <?php if ($current_driver_lic == 0)  echo ' checked="checked"';?> />
                    <label for="no">No</label>
                </div> 
                <span class="help-block"><?php echo $driver_lic_err; ?></span>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                <textarea class="form-control" cols="50" rows="6" id="description" maxlength=350 name="description" placeholder="Tell us about you, how would you like to contribute or to gain from joining Doing Good"><?php if($current_description){ echo htmlentities ($current_description); }?></textarea>
                </div>
                <span class="help-block"><?php echo $description_err; ?></span>
            </div>
            
            <div id="organization-number"  class="form-group" >
                <label class="col-sm-2 control-label">Organization Number</label>
                <div  class="col-sm-10" <?php echo (!empty($organization_number_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="organization_number" class="form-control" value="<?php echo $current_organization_number; ?>">
                </div>
                <span class="help-block"><?php echo $organization_number_err; ?></span>
            </div>
            
            <div id="association-website" class="form-group">
                 <label class="col-sm-2 control-label">Association Website</label>
                <div   class="col-sm-10"<?php echo (!empty($website_err)) ? 'has-error' : ''; ?>">
                    <input type="url" name="website" class="form-control" placeholder="https://www.association-name.org.il" value="<?php echo $current_website; ?>">
                </div>
                <span class="help-block"><?php echo $website_err; ?></span>
            </div>   
             <div id="organization-foundation-year"  class="form-group <?php echo (!empty($year_of_foundation_err)) ? 'has-error' : ''; ?>"> 
                <label class="col-sm-2 control-label">Year of association foundation</label>
                <div  class="col-sm-10">
                    <input type="text" name="year_of_foundation" class="form-control" value="<?php echo $current_year_of_foundation; ?>">
                </div>
                <span class="help-block"><?php echo $year_of_foundation_err; ?></span>
            </div>
            
            <div id="location-autocomplete" class="form-group">
                <label class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                    <input  class="form-control" id="location" name="location" placeholder="Enter address" type="text" value="<?php echo isset($current_location) ? $current_location : '' ?>" />
                </div>
                <span class="help-block"><?php echo $location_err; ?></span>
            </div> 
            
            <div id="select-activity"  class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                <label class="col-sm-2 control-label">Activity</label>
                <div  class="col-sm-10">
                    <select class="select form-control" id="select-activity-type" name="type" >
                        <option selected disabled hidden style='display: none' value="Select Type" >Select Type</option>
                        <option value="Lectures" <?php echo (isset($current_type) && $current_type === "Lectures") ? 'selected' : ''; ?>>Lectures</option>
                        <option value="Helping the needy" <?php echo (isset($current_type) &&$current_type === "Helping the needy") ? 'selected' : ''; ?>>Helping the needy</option>
                        <option value="Mentoring" <?php echo (isset($current_type) && $current_type === "Mentoring") ? 'selected' : ''; ?>>Mentoring</option>
                        <option value="Volunteering from home" <?php echo (isset($current_type) && $current_type === "Volunteering from home") ? 'selected' : ''; ?>>Volunteering from home</option>
                        <option value="People with Special Needs" <?php echo (isset($current_type) && $current_type === "People with Special Needs") ? 'selected' : ''; ?>>People with Special Needs</option>
                        <option value="Security &amp; Medicine" <?php echo (isset($current_type) && $current_type === "Security and Medicine") ? 'selected' : ''; ?>>Security and Medicine</option>
                        <option value="Maintenance and renovation" <?php echo (isset($current_type) && $current_type === "Maintenance and renovation") ? 'selected' : ''; ?>>Maintenance and renovation</option>
                        <option value="Animals" <?php echo (isset($current_type) && $current_type === "Animals") ? 'selected' : ''; ?>>Animals</option>
                        <option value="Other" <?php echo (isset($current_type) && $current_type === "Other") ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <span class="help-block"><?php echo $type_err; ?></span>
            </div>
            
            
            <div id="select-population" style="display:none" class="form-group <?php echo (!empty($population_err)) ? 'has-error' : ''; ?>">
                <label for="size" class="col-sm-2 control-label">Population</label>
                <div  class="col-sm-10">
                    <select class="select form-control" id="population" name="population">
                        <option  selected disabled hidden style='display: none' value="Select Population">Select Population</option>
                        <option value="Seniors" <?php echo (isset($current_population) && $current_population === "Seniors") ? 'selected' : ''; ?>>Seniors</option>
                        <option value="Teens and Kids" <?php echo (isset($current_population) && $current_population === "Teens and Kids") ? 'selected' : ''; ?>>Teens and Kids</option>
                        <option value="Holocaust Survivors" <?php echo (isset($current_population) && $current_population === "Holocaust Survivors") ? 'selected' : ''; ?>>Holocaust Survivors</option>
                        <option value="Patients" <?php echo (isset($current_population) && $current_population === "Patients") ? 'selected' : ''; ?>>Patients</option>
                        <option value="People with Special Needs" <?php echo (isset($current_population) && $current_population === "People with Special Needs") ? 'selected' : ''; ?>>People with Special Needs</option>
                        <option value="Families" <?php echo (isset($current_population) && $current_population === "Families") ? 'selected' : ''; ?>>Families</option>
                        <option value="Minorities/Migrant Workers" <?php echo (isset($current_population) && $current_population === "Minorities/Migrant Workers") ? 'selected' : ''; ?>>Minorities/Migrant Workers</option>
                        <option value="Animals" <?php echo (isset($current_population) && $current_population === "Animals") ? 'selected' : ''; ?>>Animals</option>
                        <option value="Other" <?php echo (isset($current_population) && $current_population === "Other") ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <span class="help-block"><?php echo $population_err; ?></span>
            </div>
            

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label class="col-sm-2 control-label">Password</label>
                <div  class="col-sm-10">
                    <input type="password" name="password" class="form-control" > 
                </div>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            
            
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label class="col-sm-2 control-label">Confirm Password</label>
                <div class="col-sm-10">
                    <input type="password" name="confirm_password" class="form-control" >
                </div>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <div id="organization-registrat-form" class="form-group">
                <label class="col-sm-2 control-label">Upload the Registrar of Asociations Form </label>
                <div   class="col-sm-10" <?php echo (!empty($registrar_form_err)) ? 'has-error' : ''; ?>">
                    <input type="file" name="registrar-form" id="registrar-form" class="form-control form-control-file">
                </div> 
                <span style="color: #a94442;"><?php echo $registrar_form_err; ?></span>
            </div> 

        <!-- <script>
             function test(file)
             {   
                  window.open(data:application/pdf;base64,' +$file);
             }
         </script> -->
        
            <!-- <a href=$current_registrar_form<?php echo $current_registrar_form; ?> "link" </a> -->
          <button onclick="test(<?php myfunction() ?>)"> Association Form</button> 
          
              <?php
              
               //echo'<script>window.location = $current_registrar_form;</script>';
               
                if ($is_user_exist) {
                    if ($_SESSION["user_id"] == $query_param_user) {
                        // The profile belong to the signed in user
                        echo '
                          <div class="form-group">
                            <label class="col-sm-2 control-label">Profile Picture</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" id="image"class="form-control">
                            </div>
                            <span style="color: #a94442;">';
                            echo $profile_picture_err;
                            //update user profile
                            echo '</span>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                              <button type="submit" class="btn-default">Update profile</button>
                            </div>
                          </div>';
                    }
                }
              ?>
            </div>
          </div>
          </form>
        
            <div id="manageVolunteerings" style="display:none">
            <?php 
                    if ($user_role === "User" || $user_role === "Admin") {
                           if ($_SESSION["user_id"] == $query_param_user) {
                                // The profile belong to the signed in user
                                echo '<h4 class="title">My subscriptions</h4>';
                            } else {
                                echo '<h4 class="title">User subscriptions</h4>';
                            }
                                        
                                echo '
                                <div class="container">
                                  <table class="table" id="volunteeringTable">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>date</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>';
                        // show list of the user volunteerings subscriptions:
                            if ($volunteerings_to_users_result->num_rows > 0) {
                                while ($volunteering_details_row = $volunteerings_details_result->fetch_assoc()) {
                                    while ($volunteering_to_user_row = $volunteerings_to_users_result->fetch_assoc()) {
                                        if ($query_param_user === $volunteering_to_user_row['user_id'] && 
                                            $volunteering_details_row['id'] ===  $volunteering_to_user_row['volunteering_id']) {
                                                $title = $volunteering_details_row['title'];
                                                $description = $volunteering_details_row['description'];
                                                $date = $volunteering_details_row['date'];
                                                $id = $volunteering_details_row['id'];
                                                $title = $volunteering_details_row['title'];
                                                $description = $volunteering_details_row['description'];
                                                $date = $volunteering_details_row['date'];
                                                $time = $volunteering_details_row['time'];
                                                $duration = $volunteering_details_row['duration'];
                                                $duration = $duration == 1 ? $duration . "hr" : $duration . "hrs";
                                                $population = $volunteering_details_row['population'];
                                                $type = $volunteering_details_row['type'];
                                                $location = $volunteering_details_row['location'];
                                                $participants_num = $volunteering_details_row['num_of_participants'];
                                                $full_name = $row['first_name']." ".$volunteering_details_row['last_name'];
                                                $email = $volunteering_details_row['email'];
            
                                                echo '
                                                   <tr onclick="redirectToVolunteeringDetails('.$id.')">
                                                        <td>' .$title. '</td>
                                                        <td>' .$description. '</td>
                                                        <td>' .$date. '</td>
                                                        <td><form action="remove-vol-subscription.php'.$query_string.'" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Unsubscribe"> </form></td>
                                                    </tr>
                                                ';
                                        }
                                    }
                                    $volunteerings_to_users_result->data_seek(0);
                                }
                                
                                $volunteerings_details_result->data_seek(0);

                            }
                              echo '
                                    </tbody>
                                  </table>
                                </div>';
                    }
                            // show list of volunteerings that were created by this user:
                            if ($_SESSION["user_id"] == $query_param_user) {
                                // The profile belong to the signed in user
                                echo '<h4 class="title">The volunteerings that I created:</h4>';
                            } else {
                                echo '<h4 class="title">The volunteerings that this user created:</h4>';
                            }                
                            echo '
                            <div class="container">
                              <table class="table" id="volunteeringTable">
                                <thead>
                                  <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Subscriptions</th>
                                  </tr>
                                </thead>
                                <tbody>';
                            if ($volunteerings_details_result->num_rows > 0) {
                                while ($volunteering_details_row = $volunteerings_details_result->fetch_assoc()) {
                                    if ($query_param_user === $volunteering_details_row['user_id']) {
                                        $title = $volunteering_details_row['title'];
                                        $description = $volunteering_details_row['description'];
                                        $date = $volunteering_details_row['date'];
                                        $id = $volunteering_details_row['id'];
                                        $title = $volunteering_details_row['title'];
                                        $description = $volunteering_details_row['description'];
                                        $date = $volunteering_details_row['date'];
                                        $time = $volunteering_details_row['time'];
                                        $duration = $volunteering_details_row['duration'];
                                        $duration = $duration == 1 ? $duration . "hr" : $duration . "hrs";
                                        $population = $volunteering_details_row['population'];
                                        $type = $volunteering_details_row['type'];
                                        $location = $volunteering_details_row['location'];
                                        $participants_num = $volunteering_details_row['num_of_participants'];
                                        $full_name = $row['first_name']." ".$volunteering_details_row['last_name'];
                                        $email = $volunteering_details_row['email'];
                                        echo '<tr id="'.$id.'" onclick="redirectToVolunteeringDetails('.$id.')">
                                                <td>' .$title. '</td>
                                                <td>' .$description. '</td>
                                                <td>' .$date. '</td>
                                                <td><form action="delete-volunteering.php'.$query_string.'" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Delete"></form></td>
                                                <td><input id="'.$id.'" type="submit" class="btn btn-primary" value="View Subscriptions"></td>
                                            </tr>';
                                    }
                                }
                                
                                $volunteerings_details_result->data_seek(0);
                            
                              echo '
                                    </tbody>
                                  </table>
                                </div>';
                            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>