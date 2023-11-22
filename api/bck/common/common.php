<?php

include("host_details.php");
include('Models/UserLogic.php');
include('Encryption.php');
date_default_timezone_set("Asia/Riyadh");
	try
	{
        $dbh = new PDO("mysql:host=$hostname;dbname=$db_name",$username,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
        $enc = new Encryption();
        $logic = new UserLogic($dbh,$enc);

	}
	catch(PDOException $e)
    {
        echo $e->getMessage();
    }


	$p_title = "Saudi Payments";



?>

