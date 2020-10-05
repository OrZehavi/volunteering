<!DOCTYPE html>

<?php
// Include DB config file
require_once "connectionToDB.php";

// Define variables and initialize with empty values
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(empty($email) )
    {
        $email_err = "Please enter email.";
    }

    if(empty($password)or $password=="")
    { 
        $password_err = "Please enter your password.";
    }

    if (!empty($password) && !empty($email))
    {
        $sql="SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            //if email exist, verify password
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];

            if(password_verify($password, $hashed_password))
            {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["email"] = $email;
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["last_name"] = $row["last_name"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["is_approved"] = $row["is_approved"];

                echo '
                    <script>
                        sessionStorage.setItem("loggedin",' .$_SESSION["loggedin"]. ');
                        sessionStorage.setItem("firstname","'.$_SESSION["name"].'");
                        sessionStorage.setItem("lastname","'.$_SESSION["last_name"].'");
                        sessionStorage.setItem("userid",' .$_SESSION["user_id"]. ');
                        sessionStorage.setItem("role","' .$_SESSION["role"]. '");
                        sessionStorage.setItem("is_approved","' .$_SESSION["is_approved"]. '");
                        window.location = "https://snirza.mtacloud.co.il/volunteering-project/";
                    </script>';
            }
            else
            {
                // Display an error message if password is not valid
                $password_err = "The password you entered was not valid.";
            }

        }
        else
        {
            // Display an error message if email doesn't exist
            $email_err = "No account found with that email.";
        }
    }
}
$conn -> close();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
    <script src="/volunteering-project/js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/forms.css">
</head>
<body>
    <div id="menu"></div>
    <div class="content">
        <h1>Login</h1>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="container center_div">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input style="width:240px" name="myButton" type="submit" class="btn btn-default" value="Login">
                </div>
                <br>
                <p>Don't have an account? <br><a href="register.php">Sign up now</a>.</p>
            </div>
        </form>
        <img src="/volunteering-project/media/images/hands.png" style="width: 100%; position: absolute; justify-content: center; display: flex; bottom: 0; z-index: -1;">
    </div>
</body>
</html>


