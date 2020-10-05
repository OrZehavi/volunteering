<?php

$host="localhost";
$user="snirza_admin";
$pass="123456";
$db="snirza_Volunteering_DB";

//create connection to the db
$conn=new mysqli($host,$user,$pass,$db);

if($conn->connect_error)
{
    die("Connection to the DB failed: ".$conn->connect_error);
}
//echo "Connection successful";


?>
