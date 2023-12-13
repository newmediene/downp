<?php // ADMIN
        $toDoAdmin = $database->prepare("SELECT * FROM downp_admin WHERE ID = '1' ");
        $toDoAdmin->execute();
        $Admin = $toDoAdmin->fetchObject();
        
        // USERS
        $toDoUsers = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
        $userID = $_SESSION["user"]->ID;
        $toDoUsers->bindparam("ID",$userID);
        $toDoUsers->execute();
        $Users = $toDoUsers->fetchObject();  ?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" >
        <link rel="stylesheet" href="../style/home.css">

        <link rel="icon" href="../files/logo/icon.png" >
        <link rel="shortcut" href="../files/logo/icon.png" >
        <link rel="apple-touch-icon" href="../files/logo/icon.png" >

        <title>المدير | <?php echo $Admin->SITENAME ?></title>
        <meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene"/>
    
    </head>

    <body onload="initDoc();" >