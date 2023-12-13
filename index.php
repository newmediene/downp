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
		<?php echo $Admin->CODEADSENSE ?>
        <link rel="icon" href="files/logo/icon.png" >
        <link rel="shortcut" href="files/logo/icon.png" >
        <link rel="apple-touch-icon" href="files/logo/icon.png" >
		
		<title>الرئيسية - <?php echo $Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene"/>
        
        <meta property="og:title" content="الرئيسية - <?php echo $Admin->SITENAME ?>" />
        <meta property="og:description" content="<?php echo $Admin->DESCRIPTION ?>" />
        <meta property="og:image" content="<?php echo $Admin->PICTURELOGO ?>" />
        <meta property="og:image:width" content="1200" />  
        <meta property="og:image:height" content="630"/>
        <meta property="og:type" content="website" />
        
    </head>

    <body>

        <div id="content" >

            <?php require_once 'navbar.php'; ?>
            
            <div class="slideshow" >

                <?php $toDoPostAvtive = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND ACTIVE = '1' ");
                      $toDoPostAvtive->execute();
                      $PostAct = $toDoPostAvtive->fetchObject(); ?>
                <?php if($toDoPostAvtive->rowCount() != 0){ ?>
                <iframe src="https://www.youtube.com/embed/<?php echo $PostAct->URLVIDEO ?>?rel=0" title="<?php echo $PostAct->TITLE ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php } ?>

            </div><!--- slideshow --->
                
            <?php require_once 'home.php'; ?>

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>            
    
<?php }catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>