<?php try { require_once 'config.php'; session_start();

$toDoAdmin = $database->prepare("SELECT * FROM downp_admin WHERE ID = '1' ");
$toDoAdmin->execute();
$Admin = $toDoAdmin->fetchObject(); ?>

<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" >

        <link rel="stylesheet" href="style/home.css">
        <link rel="icon" href="files/logo/icon.png" >
        <link rel="shortcut" href="files/logo/icon.png" >
        <link rel="apple-touch-icon" href="files/logo/icon.png" >
		
		<title>صغحة الخطأ</title>
        
    </head>

    <body>

        <div id="content" >
        
        <?php require_once 'navbar.php'; ?>

			<div class="page404" >
			    <img src="img/404.png" />
			    <p>أنت الآن في صفحة الخطأ - قد تكون أخطأت في كتابة رابط على موقعنا إرجع للصفحة الرئيسية و أعد المحاولة</p>
			    <a href='index.php' >الصفحة الرئيسية</a>
			</div>	
		
        </div><!--- content --->

        <?php require_once 'footer.php'; ?>   
		
<?php }catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>