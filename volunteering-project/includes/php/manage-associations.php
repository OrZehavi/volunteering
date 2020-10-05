<?php
    // Include DB config file
    require_once "connectionToDB.php";
    
    //Check if the user is a logged with admin priviledges and if not, redirect to main page
    session_start();
    if (empty($_SESSION["user_id"]) or $_SESSION["role"] != "Admin")
    {
     echo ' <script> alert("Sorry, manage Associations is available only to logged in admin users");
            window.location = "https://snirza.mtacloud.co.il/volunteering-project/";
         </script>';
    } else {
                echo '
            <script>
                sessionStorage.setItem("is_user_exist", true);
            </script>';
        $is_user_exist = true;
        //The loggedin user is admin!
        //echo '<h4> Hello <a href="/volunteering-project/includes/php/manage_associations.php?user='.$_SESSION["user_id"].'">'.$_SESSION["name"].'</a><br> Associations Management</h3>';
    }


?>


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        
        <script src="/volunteering-project/js/common.js"></script>
        <script src="/volunteering-project/js/manage-associations.js"></script>
        <!--Style -->
        <link rel="stylesheet" type="text/css" href="/volunteering-project/css/common.css">
        <link rel="stylesheet" type="text/css" href="/volunteering-project/css/manage-associations.css">
        </head>
    
        <body>
            <div id="menu"></div>
            <div id="content" >  <!-------------When I use class="content" the buttons are disabled = not working ---------------------->
                <div class="btn-group" style="width:100%">
                    <button id="approved-associations-nav-button" class="button-reset navigation-button" style="width:50%" onclick=changeToggleBarSelection('approved')>Approved Associations</button>
                    <button id="new-associations-nav-button" class="button-reset navigation-button" style="width:50%" onclick=changeToggleBarSelection('new')>New Associations</button>
                </div> 
                <div class="scrolling-div" >
                    <div id="manageAssociations" style="display:block"  >
                        <?php 
                        echo '
                           <div class="container">
                               <table class="table" id="AssociationsTable">
                               <thead>
                               <tr>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Phone</th>
                                 <th>Address</th>
                                 <th>Type</th>
                                 <th>Population</th>
                               </tr>
                               </thead>
                               <tbody> ';
                               
                            //sql select
                            $sql = "SELECT * FROM `users` WHERE `role` = 'organization' AND `is_approved` = 1 ";
                            $approved_associations = $conn->query($sql);   
                           if ($approved_associations ->num_rows > 0)
                           {
                                      while ($row = $approved_associations->fetch_assoc())
                                      {
                                       $string_row = json_encode($row);
                                       $rows[] = $row;
                                       $id = $row['id'];
                                       $name = $row['name'];
                                       $_SESSION['user_name'] = $name;
                                       $email = $row['email'];
                                       $_SESSION['user_email'] = $email;
                                       $phone = $row['phone'];
                                       $location = $row['location'];
                                       $type = $row['type'];
                                       $population = $row['population'];
                                        
                                        //open association details
                                       echo '
                                           <tr onclick="redirectToAssociationgDetails('.$id.')">
                                           <td>' .$name. '</td>
                                           <td>' .$email. '</td>
                                           <td>' .$phone. '</td>
                                           <td>' .$location. '</td>
                                           <td>' .$type. '</td>
                                           <td>' .$population. '</td>
                                           </tr>';
                                      }
                                }
                            echo '
                                 </table>';
                        ?>
                    </div>          
                </div>     
                    <!-- Management of the New Associations -->
                    <div id="manageNewAssociations" style="display:none">
                        <?php 
                        echo '
                           <div class="container">
                               <table class="table" id="AssociationsTable">
                               <thead>
                               <tr>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Phone</th>
                                 <th>Address</th>
                                 <th>Type</th>
                                 <th>Population</th>
                               </tr>
                               </thead>
                               <tbody> ';
                               
                            //sql select
                            $sql = "SELECT * FROM `users` WHERE `role` = 'organization' AND `is_approved` = 0 ";
                            $approved_associations = $conn->query($sql);   
                           if ($approved_associations ->num_rows > 0)
                           {
                                      while ($row = $approved_associations->fetch_assoc())
                                      {
                                       $string_row = json_encode($row);
                                       $rows[] = $row;
                                       $id = $row['id'];
                                       $name = $row['name'];
                                       $email = $row['email'];
                                       $phone = $row['phone'];
                                       $location = $row['location'];
                                       $type = $row['type'];
                                       $population = $row['population'];
                                        
                                        //open association details
                                       echo '
                                           <tr onclick="redirectToAssociationgDetails('.$id.')">
                                           <td>' .$name. '</td>
                                           <td>' .$email. '</td>
                                           <td>' .$phone. '</td>
                                           <td>' .$location. '</td>
                                           <td>' .$type. '</td>
                                           <td>' .$population. '</td>
                                           </tr>';
                                      }
                                }
                            echo '
                                 </table>';
                        ?>
                    </div>
                   
                </div>       
            
</body>
</html>