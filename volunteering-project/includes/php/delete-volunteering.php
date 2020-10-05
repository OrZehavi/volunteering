<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $query_param_user = $_GET['user'];
    $volunteering_id = $_POST["volunteeringid"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $delete_voluteering_to_user_query = "DELETE FROM `volunteerings_to_users` WHERE `volunteering_id`=".$volunteering_id."";
        $delete_voluteering_query = "DELETE FROM `volunteerings` WHERE `id`=".$volunteering_id."";
        if (mysqli_query($conn, $delete_voluteering_to_user_query)) {
            if (mysqli_query($conn, $delete_voluteering_query)) {
                echo '<script>
                    alert("Volunteering deleted successfuly");
                    window.location = "/volunteering-project/includes/php/profile.php?user='.$query_param_user.'";
                </script>';
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }

    }
    
    $conn -> close();
?>