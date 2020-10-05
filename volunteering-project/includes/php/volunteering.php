<!DOCTYPE html>

<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $volunteeringId = htmlspecialchars($_GET["id"]);
    $get_volunteerings_details = "SELECT vol.*, u.name as first_name, u.last_name, u.email FROM `volunteerings` vol JOIN `users` u ON (u.id= vol.user_id) where vol.id = $volunteeringId";
    $logged_in_user_id = $_SESSION["user_id"];
    $logged_in_user_role = $_SESSION["role"];

    $volunteering_details_result = $conn->query($get_volunteerings_details);
               
    if ($volunteering_details_result->num_rows > 0) {
        echo '
            <script>
                sessionStorage.setItem("is_volunteering_exist", true);
            </script>';
        $row =  $volunteering_details_result ->fetch_assoc();
        $id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
        $date = $row['date'];
        $time = $row['time'];
        $duration = $row['duration'];
        //$duration = $duration == 1 ? $duration . "hr" : $duration . "hrs";
        $population = $row['population'];
        $type = $row['type'];
        $location = $row['location'];
        $participants_num = $row['num_of_participants'];
        $full_name = $row['first_name']." ".$row['last_name'];
        $email = $row['email'];
        $vol_owner_user_id = $row['user_id'];
        

        if ($logged_in_user_id === $vol_owner_user_id) {
            // check if the logged in user is the owner of this volunteering
            $is_user_volunteering_owner = true;
        } else {
            $is_user_volunteering_owner = false;
            
            // The logged in user is not the owner of this volunteering. Lets check if the user already subscribed to this volunteering.
            $get_volunteerings_to_users_table = "SELECT * FROM volunteerings_to_users WHERE volunteering_id=$volunteeringId AND user_id=$logged_in_user_id";
            $get_volunteerings_to_users_result = $conn->query($get_volunteerings_to_users_table);
            if ($get_volunteerings_to_users_result->num_rows > 0) {
                $is_already_subscribed = true;
                // The logged in user is already subscribed to this volunteering
            } else {

                $is_already_subscribed = false;
                // The logged in user is not subscribed to this volunteering. Lets check if the volunteering is full of participants.
                $get_participants_of_volunteerings = "select COUNT(*) as count, volunteering_id from volunteerings_to_users GROUP BY volunteering_id";
                if ($get_participants_of_volunteerings_result = $conn-> query($get_participants_of_volunteerings)) {
                    $is_volunteering_full = false;
                    while ($participants_of_volunteerings_row = $get_participants_of_volunteerings_result-> fetch_assoc()) {
                        if ($participants_of_volunteerings_row["volunteering_id"] == $volunteeringId && $participants_of_volunteerings_row["count"] >= $participants_num) {
                            $is_volunteering_full = true;
                        }
                    }
                }

            }

            
        }
    } else {
        $is_volunteering_exist = false;

        echo '
            <script>
                sessionStorage.setItem("is_volunteering_exist", false);
            </script>';
    }
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="/volunteering-project/js/common.js"></script>
    <script src="/volunteering-project/js/volunteering-details.js"></script>
    <title>Volunteering Platform</title>
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/volunteering-details.css">

</head>

<body>
    <div id="menu"></div>
    <div class="content" style="text-align: left">
        <div id="volunteering-not-exist" style="display:none">
            <h1>Error</h2>
    
            <h2>Volunteering does not exist</h2>
        </div>
        <div id ="volunteering-details-content" class="volunteering-details-content scrolling-div">
            <div class="volunteering-details-header">
                <div class="wrapper">
                    <span class="title"><?php echo $title; ?></span>
                </div>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Description: </span>
                <span class="volunteering-data-content"><?php echo $description; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Date: </span>
                <span class="volunteering-data-content"><?php echo $date; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Time: </span>
                <span class="volunteering-data-content"><?php echo $time; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Duration: </span>
                <span class="volunteering-data-content"><?php echo $duration; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Population: </span>
                <span class="volunteering-data-content"><?php echo $population; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Type: </span>
                <span class="volunteering-data-content"><?php echo $type; ?></span>
            </div>
                        <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Location: </span>
                <span class="volunteering-data-content"><?php echo $location; ?></span>
            </div>
                        <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Max Participants Number: </span>
                <span class="volunteering-data-content"><?php echo $participants_num; ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Created By: </span>
                <span class="volunteering-data-content"><?php echo "<a href='/volunteering-project/includes/php/profile.php?user=$vol_owner_user_id'>$full_name</a>" ?></span>
            </div>
            <div class="volunteering-data-wrapper">
                <span class="volunteering-data-title">Contact: </span>
                <span class="volunteering-data-content"><?php echo $email; ?></span>
            </div>
        </div>
        
        <?php
            if ($logged_in_user_id) { 
                if ($is_user_volunteering_owner) {
                // does the logged in user created this volunteering? 
                    echo '<form action="delete-volunteering.php?user='.$_SESSION["user_id"].'" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Delete Volunteering"> </form>';
                } else if ($logged_in_user_role !== "Organization") {
                    if ($is_already_subscribed) {
                        // The logged in user is already subscribed to this volunteering
                        echo '<input class="btn btn-primary" value="Subscribed" title="You already subscribed this volunteering" disabled>';
                    } else if ($is_volunteering_full) {
                        // This volunteering is full
                        echo '<input class="btn btn-primary" value="Full" title="This volunteering is full" disabled> </form>';
                    } else {
                                
                         echo '<form action="subscribe-volunteering.php" method="post"><input name="volunteeringid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Subscribe"></form>';
                    }
                }
            }
        ?>
    </div>
</body>
</html>
