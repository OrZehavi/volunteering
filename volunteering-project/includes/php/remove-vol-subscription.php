<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $query_param_user = $_GET['user'];
    $query_param_source = $_GET['source'];
    $volunteering_id = $_POST["volunteeringid"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $delete_vol_subscription_query = "DELETE FROM volunteerings_to_users WHERE user_id=$query_param_user AND volunteering_id=$volunteering_id";

        if (mysqli_query($conn, $delete_vol_subscription_query)) {
            if ($query_param_source === 'list') {
                echo '<script>
                    alert("Subscription deleted successfuly");
                    window.location = "/volunteering-project/includes/php/get-volunteerings.php";
                </script>';
            } else {
                echo '<script>
                    alert("Subscription deleted successfuly");
                    window.location = "/volunteering-project/includes/php/profile.php?user='.$query_param_user.'";
                </script>';
            }

        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }

    }
    
    $conn -> close();
?>