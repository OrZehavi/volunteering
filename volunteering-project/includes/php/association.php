<!DOCTYPE html>

<?php
    // Include DB config file
    require_once "connectionToDB.php";
    session_start();

    $query_string = '?'.$_SERVER['QUERY_STRING'];
    $associationId = htmlspecialchars($_GET["id"]);
    $get_association_details = "SELECT * FROM `users` WHERE `id` = $associationId";
    $association_details_result = $conn->query($get_association_details);
               
    if ($association_details_result->num_rows > 0) {
        echo '
            <script>
                sessionStorage.setItem("is_association_exist", true);
            </script>';
            
        $row =  $association_details_result ->fetch_assoc();
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $profile_image = $row['profile_image'];
        $creation_time = $row['date'];
        $organization_number = $row['organization_number'];
        $phone = $row['phone'];
        $location = $row['location'];
        $type = $row['type'];
        $population = $row['population'];
        $association_is_approved=$row['is_approved'];

    }
    else 
    {
        $is_association_exist = false;

        echo '
            <script>
                sessionStorage.setItem("is_association_exist", false);
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
    <script src="/volunteering-project/js/association-details.js"></script>
    <title>Volunteering Platform</title>
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
    <link rel="stylesheet" type="text/css" href="/volunteering-project/css/association-details.css">
</head>

<body>
    <div id="menu"></div>
    <div class="content" style="text-align: left">
        <div id="association-not-exist" style="display:none">
            <h1>Error</h2>
    
            <h2>Association does not exist</h2>
        </div>
        <div id ="association-details-content" class="association-details-content scrolling-div">
            <div class="association-details-header">
                <div class="wrapper">
                    <span class="title"><?php echo $name; ?></span>
                </div>
            </div>
            <div class="association-data-wrapper">
                <span class="association-data-title">ID: </span>
                <span class="association-data-content"><?php echo $id; ?></span>
            </div>
            <div class="association-data-wrapper">
            <img src="<?php echo $current_image; ?>">                   <!--  ------------   Unable to see the photo ----------------->
          </div>
            <div class="association-data-wrapper">
                <span class="association-data-title">Email: </span>
                <span class="association-data-content"><?php echo $email; ?></span>
            </div>
            <div class="association-data-wrapper">
                <span class="association-data-title">Registration Time: </span>
                <span class="association-data-content"><?php echo $creation_time; ?></span>
            </div>

            <div class="association-data-wrapper">
                <span class="association-data-title">Organization Number: </span>
                <span class="association-data-content"><?php echo $organization_number; ?></span>
            </div>
                        <div class="association-data-wrapper">
                <span class="association-data-title">Phone: </span>
                <span class="association-data-content"><?php echo $phone; ?></span>
            </div>
                        <div class="association-data-wrapper">
                <span class="association-data-title">Address: </span>
                <span class="association-data-content"><?php echo $location; ?></span>
            </div>
            <div class="association-data-wrapper">
                <span class="association-data-title">Type: </span>
                <span class="association-data-content"><?php echo $type; ?></span>
            </div>
            <div class="association-data-wrapper">
                <span class="association-data-title">Population: </span>
                <span class="association-data-content"><?php echo $population; ?></span>
            </div>
        </div>
        <?php
            if ($association_is_approved == 0)
            {
                //approve association
                echo '<form action="approve-association.php" method="post"><input name="associationid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Approve Association registration"></form>';
            }
            else
            {
                //delete association
                echo '<form action="delete-association.php" method="post"><input name="associationid" type="text" style="display:none;" value="'.$id.'"><input id="'.$id.'" type="submit" class="btn btn-primary" value="Delete Association" ></form>';
            }
        ?>
        
    </div>
</body>
</html>
