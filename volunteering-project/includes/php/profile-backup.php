<!DOCTYPE html>

<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $userId = htmlspecialchars($_GET["user"]);
    $get_user_details_query = "SELECT `id`,`name`,`last_name`,`email`,`profile_image`,`role` FROM `users` WHERE `id`=".$userId."";
    $result = $conn->query($get_user_details_query);
    $get_volunteerings_details = "SELECT vol.*, u.name as first_name, u.last_name, u.email FROM `volunteerings` vol JOIN `users` u ON (u.id= vol.user_id)";
    $volunteerings_details_result = $conn->query($get_volunteerings_details);
    $get_volunteerings_to_users_query = "SELECT * FROM volunteerings_to_users";
    $get_participants_of_volunteerings_query = "select COUNT(*) as count, volunteering_id from volunteerings_to_users GROUP BY volunteering_id";
    $volunteerings_to_users_result = $conn->query($get_volunteerings_to_users_query);
    $participants_of_volunteerings_result = $conn->query($get_participants_of_volunteerings_query);

    $query_param_user = $_GET['user'];
    if ($result->num_rows > 0) {
        echo '
            <script>
                sessionStorage.setItem("is_user_exist", true);
            </script>';
        $is_user_exist = true;
        $row = $result->fetch_assoc();
        $current_name = $row['name'];
        $current_last_name = $row['last_name'];
        $current_email = $row['email'];
        $current_image = $row['profile_image'];
        $user_role =  $row['role'];
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
$name_err = $last_name_err = $email_err = $confirm_password_err = "";
    // Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_FILES['image']['tmp_name'];

    if ($new_name != $current_name || $new_last_name != $current_last_name || $new_email != $current_email || is_uploaded_file($file)) {
        // Validate new name
        if (empty($new_name)) {
            $name_err = "Please enter a name.";
        }
    
        if (strlen($new_name) > 25) {
            $name_err = "Please enter a name shorter then 25 chars.";
        }
    
        if (empty($new_last_name)) {
            $last_name_err = "Please enter a last name.";
        }
    
        if (strlen($new_last_name) > 25) {
            $name_err = "Please enter a last name shorter then 25 chars.";
        }
    
        if (empty($new_email)) {
            $email_err = "Please enter Email.";
        } else {
            if ($new_email != $current_email) {
                // Prepare a select statement
                $sql = "SELECT * FROM users WHERE email = '$new_email'";
        
                $result = $conn->query($sql);
                // Check if email exists, if yes print error
                if ($result->num_rows > 0) {
                    $email_err = "Sorry, This email is already taken.";
                }
            }
        }
    
        if (is_uploaded_file($file)) {
            $check = getimagesize($file);
            if ($check !== false) {
                $data = file_get_contents($file);
                $profile_picture_base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
                
            } else {
                $profile_picture_err = "file is not an image";
            }                        
        } else {
            // Use old image
            $profile_picture_base64 = $current_image;
        }
        
        // Check input errors before inserting in database
        if (empty($name_err) && empty($last_name_err) && empty($email_err) && empty($profile_picture_err))
        {
            $userId = $_SESSION["user_id"];
            // Prepare an update statement
            $sql = "UPDATE `users` SET `name`='".$new_name."',`last_name`='".$new_last_name."',`email`='".$new_email."',`profile_image`='".$profile_picture_base64."' WHERE `id`='".$userId."'";

            if ($conn->query($sql) == FALSE)
            {
                echo "Something went wrong. Please try again later. Error is:".$conn->error;
                exit();
            } else {
                $get_user_details_query = "SELECT `id`,`name`,`last_name`,`email`,`profile_image` FROM `users` WHERE `id`=".$userId."";
                $result = $conn->query($get_user_details_query);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $current_name = $row['name'];
                    $current_last_name = $row['last_name'];
                    $current_email = $row['email'];
            
                    $current_image = $row['profile_image'];
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
            <div class="panel-heading">
            <h4 class="panel-title">User info</h4>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">First Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="<?php echo $current_name ?>">
                </div>
                <span class="help-block"><?php echo $name_err; ?></span>
    
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Last Name</label>
                <div class="col-sm-10">
                  <input type="text" name="last_name" class="form-control" value="<?php echo $current_last_name ?>">
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
              
              <?php
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
                            
                            echo '</span>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                              <button type="submit" class="btn btn-primary">Update profile</button>
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
                                        <th class="hide">Time</th>
                                        <th class="hide">Duration</th>
                                        <th class="hide">Population</th>
                                        <th class="hide">Activity Type</th>
                                        <th class="hide">Location</th>
                                        <th class="hide">Max participants</th>
                                        <th class="hide">Initiator</th>
                                        <th class="hide">email</th>
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
                                                        <td class="hide">' .$time. '</td>
                                                        <td class="hide">' .$duration. '</td>
                                                        <td class="hide">' .$population. '</td>
                                                        <td class="hide">' .$type. '</td>
                                                        <td class="hide">' .$location. '</td>
                                                        <td class="hide">' .$participants_num. '</td>
                                                        <td class="hide">' .$full_name.'</td>
                                                        <td class="hide">' .$email. '</td>
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
                                    <th class="hide">Time</th>
                                    <th class="hide">Duration</th>
                                    <th class="hide">Population</th>
                                    <th class="hide">Activity Type</th>
                                    <th class="hide">Location</th>
                                    <th class="hide">Max participants</th>
                                    <th class="hide">Initiator</th>
                                    <th class="hide">email</th>
                                    <th>Action</th>
                                    <td>Subscriptions</td>
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
                                                <td class="hide">' .$time. '</td>
                                                <td class="hide">' .$duration. '</td>
                                                <td class="hide">' .$population. '</td>
                                                <td class="hide">' .$type. '</td>
                                                <td class="hide">' .$location. '</td>
                                                <td class="hide">' .$participants_num. '</td>
                                                <td class="hide">' .$full_name.'</td>
                                                <td class="hide">' .$email. '</td>
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
    
    
    
          <!-- Modal -->
  <!--<div class="modal fade" id="volunteeringDetailsModal" role="dialog">-->
  <!--  <div class="modal-dialog">-->
    
      <!-- Modal content-->
  <!--    <div class="modal-content">-->
  <!--      <div class="modal-header">-->
  <!--        <button type="button" class="close" data-dismiss="modal">&times;</button>-->
  <!--        <h4 class="modal-title" id="modal-title"></h4>-->
  <!--      </div>-->
  <!--      <div class="modal-body">-->
  <!--          <div>-->
  <!--            <span>Duration: </span>-->
  <!--            <span id="modal-duration"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Population: </span>-->
  <!--            <span id="modal-population"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Type: </span>-->
  <!--            <span id="modal-type"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Location: </span>-->
  <!--            <span id="modal-location"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Participants Num: </span>-->
  <!--            <span id="modal-participants-num"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Created By: </span>-->
  <!--            <span id="modal-full-name"></span>-->
  <!--          </div>-->
  <!--          <div>-->
  <!--            <span>Email: </span>-->
  <!--            <span id="modal-email"></span>-->
  <!--          </div>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </div>-->
  <!--</div>-->
  
  
  
</body>
</html>