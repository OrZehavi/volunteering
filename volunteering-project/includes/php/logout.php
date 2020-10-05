<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

echo '
    <script>
        sessionStorage.setItem("loggedin", "0");
        sessionStorage.setItem("firstname", null);
        sessionStorage.setItem("lastname", null);
        sessionStorage.setItem("userid", null);
        sessionStorage.setItem("role", null);
        sessionStorage.setItem("is_organization_register", null);
        sessionStorage.setItem("is_approved", null);
        sessionStorage.setItem("is_volunteering_exist", null);
        sessionStorage.setItem("is_association_exist", null);
        sessionStorage.setItem("get_volunteerings_details", null);
        window.location = "https://snirza.mtacloud.co.il/volunteering-project/";
    </script>';

// Redirect to login page
//header("location: ../../index.html");
exit;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        function deleteSessionStorage() {

        }
    </script>
</head>
<body>

</body>
</html>