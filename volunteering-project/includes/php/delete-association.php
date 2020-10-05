<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $associationid = $_POST["associationid"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $delete_association = "DELETE FROM `users` WHERE `id`=$associationid";
      
        if (mysqli_query($conn, $delete_association)) {
                echo '<script>
                    alert("Association '.$association_id.' was deleted successfuly");
                    window.location = "/volunteering-project/includes/php/manage-associations.php";
                </script>';
        } 
        else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
       
    }
    
    $conn -> close();
?>