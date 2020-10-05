<!DOCTYPE HTML>
// <?php
//     require_once "connectionToDB.php";
//     $volunteering_id = $_POST["volunteeringid"];
//     session_start();

//     $user_id = $_SESSION["user_id"];
//     if ($_SERVER["REQUEST_METHOD"] == "POST")
//     {
//         $sql= "INSERT INTO volunteerings_to_users(volunteering_id, user_id) VALUES ('".$volunteering_id."', '".$user_id."')";
//         if (mysqli_query($conn, $sql)) {
//             echo '<script>alert("Thanks '.$_SESSION["name"].'! You successfully subscribe to this volunteering");</script>';
//         } else {
//             echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
//         }

//         //$conn -> close();
//     }
// ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjnUJqJ3Du8HDlXAJaZvb14oyJuxtfmKQ&callback=initMap&libraries=&v=weekly" defer></script>
    <!-- AIzaSyDjnUJqJ3Du8HDlXAJaZvb14oyJuxtfmKQ -->
    <script src="/volunteering-project/js/common.js"></script>
    <script src="/volunteering-project/js/want-to-help.js"></script>
    <title>Volunteering Platform</title>
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/want-to-help.css">
</head>

<body>

    
      <!-- Modal -->
  <div class="modal fade" id="volunteeringDetailsModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="modal-title"></h4>
        </div>
        <div class="modal-body">
            <div>
              <span>Duration: </span>
              <span id="modal-duration"></span>
            </div>
            <div>
              <span>Population: </span>
              <span id="modal-population"></span>
            </div>
            <div>
              <span>Type: </span>
              <span id="modal-type"></span>
            </div>
            <div>
              <span>Location: </span>
              <span id="modal-location"></span>
            </div>
            <div>
              <span>Participants Num: </span>
              <span id="modal-participants-num"></span>
            </div>
            <div>
              <span>Created By: </span>
              <span id="modal-full-name"></span>
            </div>
            <div>
              <span>Email: </span>
              <span id="modal-email"></span>
            </div>
        </div>
      </div>
    </div>
  </div>
 
    <div id="menu"></div>
    <div class="content">
        <div class="btn-group" style="width:100%">
          <button id="map-button" class="button-reset" style="width:50%" onclick=changeToggleBarSelection('map')>Map view</button>
          <button id="list-button" class="button-reset" style="width:50%" onclick=changeToggleBarSelection('list')>List view</button>
        </div>
        
        <div id="map-element">
            <div id="map"></div>
        </div>
        
        
        
        <div id="list-element">
            <h1> Search Volunteerings </h1>
            <div class="scrolling-div">
                <?php
                    session_start();
                    if (isset($_SESSION["name"])) {
                        echo '<h3> Hello <a href="/volunteering-project/includes/php/profile.php?user='.$_SESSION["user_id"].'">'.$_SESSION["name"].'</a> ! Lets help you find volunteerings</h3>';
                    } else {
                        echo '<h3>Subscribing to volunteering is available only to logged in users</h3>';
                    }
                ?>
            <br>
            <div class="btn-group">
                <button id="get-today-volunteers" type="button" class="btn btn-primary">Today</button>
                <button id="get-this-month-volunteers" type="button" class="btn btn-primary">This Month</button>
                <button id="get-this-year-volunteers" type="button" class="btn btn-primary">This Year</button>
                <?php
                    if (isset($_COOKIE['selectedTime'])) {
                        $time = $_COOKIE['selectedTime'];
                        echo '<script type="text/javascript">setSelectedTimeByCookie("'.$time.'");</script>';
                    }
                ?>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <label for="size">Population: </label>
                    <select id="select-population" style="width:165px; display:inline-block;" class="select form-control" id="population" name="population">
                        <option value="1" selected disabled>Choose population</option>
                        <option>Seniors</option>
                        <option>Teens</option>
                        <option>Holocaust Survivors</option>
                        <option>Patients</option>
                        <option>People with Special Needs</option>
                        <option>Families</option>
                        <option>Minorities/Migrant Workers</option>
                        <option>Animals</option>
                        <option>Other</option>
                    </select>
                    <?php
                        if (isset($_COOKIE['selectedPopulation'])) {
                            $population = $_COOKIE['selectedPopulation'];
                            echo '<script type="text/javascript">setSelectedPopulationByCookie("'.$population.'");</script>';
                        }
                    ?>
                </div>
            </div>
            <div class="activity-select form-inline">
                <div class="form-group">
                    <label for="size">Activity: </label>
                    <select id="select-activity-type" style="width:165px; display:inline-block;" class="select form-control" id="activity-type" name="activity-type">
                        <option value="1" selected disabled>Choose activity</option>
                        <option>Lectures</option>
                        <option>Helping the needy</option>
                        <option>Mentoring</option>
                        <option>Volunteering from home</option>
                        <option>People with Special Needs</option>
                        <option>Security & Medicine</option>
                        <option>Maintenance and renovation</option>
                        <option>Animals</option>
                        <option>Other</option>
                    </select>
                    <?php
                        if (isset($_COOKIE['selectedActivityType'])) {
                            $activiryType = $_COOKIE['selectedActivityType'];
                            echo '<script type="text/javascript">setSelectedActivityTypeByCookie("'.$activiryType.'");</script>';
                        }
                    ?>
                </div>
            </div>
            <br>
            <button id="get-all-volunteers" type="button" class="btn btn-primary">Reset filters</button>
            
            <div id="volunteerings-content" class="wrapper">
                <br>
                <table class="tableBodyScroll" id="volunteeringTable">
                    <thead>
                        <tr style="background-color: #f1f1f1;">
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="hide">Duration</th>
                            <th class="hide">Population</th>
                            <th class="hide">Activity Type</th>
                            <th class="hide">Location</th>
                            <th class="hide">Max participants</th>
                            <th class="hide">Initiator</th>
                            <th class="hide">email</th>
    
                            <?php
                                    session_start();
                                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                                        echo '<th>Subscribe</th>';
                                    }
                                    echo '
                            </tr>
                        </thead>
                        <tbody id="volunteerings">';
                            // Include DB config file
                            require_once "connectionToDB.php";
                            
                            $get_volunteerings_details = "SELECT vol.*, u.name as first_name, u.last_name, u.email FROM `volunteerings` vol JOIN `users` u ON (u.id= vol.user_id)";
                            if ($get_volunteerings_details_result = $conn->query($get_volunteerings_details)) {
                                $get_volunteerings_to_users_table = "SELECT * FROM volunteerings_to_users";
                                $get_participants_of_volunteerings = "select COUNT(*) as count, volunteering_id from volunteerings_to_users GROUP BY volunteering_id";

                                if ($volunteerings_to_users_result = $conn-> query($get_volunteerings_to_users_table)) {
                                    if ($get_participants_of_volunteerings_result = $conn-> query($get_participants_of_volunteerings)) {
                                        while ($row = $get_volunteerings_details_result->fetch_assoc()) {
                                            $string_row = json_encode($row);
                                            $rows[] = $row;
                                            $id = $row['id'];
                                            $title = $row['title'];
                                            $description = $row['description'];
                                            $date = $row['date'];
                                            $time = $row['time'];
                                            $duration = $row['duration'];
                                            $duration = $duration == 1 ? $duration . "hr" : $duration . "hrs";
                                            $population = $row['population'];
                                            $type = $row['type'];
                                            $location = $row['location'];
                                            $participants_num = $row['num_of_participants'];
                                            $full_name = $row['first_name']." ".$row['last_name'];
                                            $email = $row['email'];
    
                                            echo '<tr id="'.$id.'">
                                                    <td>' .$title. '</td>
                                                    <td>' .$description. '</td>
                                                    <td>' .$date. '</td>
                                                    <td>' .$time. '</td>
                                                    <td class="hide">' .$duration. '</td>
                                                    <td class="hide">' .$population. '</td>
                                                    <td class="hide">' .$type. '</td>
                                                    <td class="hide">' .$location. '</td>
                                                    <td class="hide">' .$participants_num. '</td>
                                                    <td class="hide">' .$full_name.'</td>
                                                    <td class="hide">' .$email. '</td>';
    
                                            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
                                            {
                                                $is_user_already_subscribed = false;
                                                $is_volunteering_full = false;
    
                                                while ($volunteering_to_user_row = $volunteerings_to_users_result->fetch_assoc()) {
                                                    if ($volunteering_to_user_row["user_id"] == $_SESSION["user_id"] && $volunteering_to_user_row["volunteering_id"] == $id) {
                                                        $is_user_already_subscribed = true;
                                                    }
                                                }
                                                
                                                while ($participants_of_volunteerings_row = $get_participants_of_volunteerings_result-> fetch_assoc()) {
                                                    if ($participants_of_volunteerings_row["volunteering_id"] == $id && $participants_of_volunteerings_row["count"] >= $participants_num) {
                                                        $is_volunteering_full = true;
                                                    }
                                                }
                                                
                                                $volunteerings_to_users_result->data_seek(0);
                                                $get_participants_of_volunteerings_result->data_seek(0);
    
                                                if ($is_user_already_subscribed)
                                                {
                                                    // User is already subscribed to this volunteering, disabling button
                                                    echo '<td><form action="get-volunteerings.php" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Subscribed" title="You already subscribed this volunteering" disabled> </form></td>';
                                                } else if ($is_volunteering_full) {
                                                    echo '<td><form action="get-volunteerings.php" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Full" title="This volunteering is full" disabled> </form></td>';
                                                } else {
                                                    echo '<td><form action="subscribe-volunteering.php" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Subscribe"></form></td>';
                                                }
                                            }
                                        }
                                        
                                        if (isset($_COOKIE['selectedTime']) && isset($_COOKIE['selectedPopulation'])) {
                                            echo '<script type="text/javascript">filterAllRows();</script>';
                                        }
                                    }
                                }
                            }
                            
                            $volunteerings_details_json = json_encode($rows);
                            echo '</tr>';
                                echo '
                                    <script>
                                        sessionStorage.setItem("get_volunteerings_details",JSON.stringify('.$volunteerings_details_json.'));
                                    </script>
                                ';
                            $conn -> close();
                        ?>
                        </tbody>
                </table>
            </div>
            </div>
            
        </div>
    </div>
</body>
</html>