<?php try{ require_once 'config.php'; session_start();

    $toDoAdmin = $database->prepare("SELECT * FROM downp_admin WHERE ID = '1' ");
    $toDoAdmin->execute();
    $Admin = $toDoAdmin->fetchObject();

    $checkPosts = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND ID = :ID ");
    $checkPosts->bindparam("ID",$_GET['post']);
	$checkPosts->execute();
	
	if(isset($_GET['post']) AND intval($_GET['post']) AND is_numeric($_GET['post']) AND $checkPosts->rowCount() > 0 ){ 
		
		$ShowPosts = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND ID = :ID ");
		
		$GetId = intval($_GET['post']);

		$ShowPosts->bindparam("ID",$GetId);
		$ShowPosts->execute();
            
		$Posts = $ShowPosts->fetchObject(); ?>

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
		
		<title><?php echo $Posts->TITLE." - ".$Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo substr(strip_tags($Posts->TOPIC),0 ,400)." ..."; ?>"/>
        <meta name="author" content="newmediene"/>
        
        <meta property="og:title" content="<?php echo $Posts->TITLE." - ".$Admin->SITENAME ?>" />
        <meta property="og:description" content="<?php echo substr(strip_tags($Posts->TOPIC),0 ,400)." ..."; ?>" />
        <meta property="og:image" content="<?php echo $Admin->PICTURELOGO ?>" />
        <meta property="og:image:width" content="1200" />  
        <meta property="og:image:height" content="630"/>
        <meta property="og:type" content="website" />
        
    </head>

    <body>

        <div id="content" >
        <?php //----------------------
        if(!isset($_SESSION['user']) AND !isset($_SESSION['mail']) AND !isset($_SESSION['pass'])){
			
			$updateView = $database->prepare("UPDATE downp_post SET VIEW = :VIEW WHERE ID = :ID");
							
			$addView = $Posts->VIEW + 1 ;

			$updateView->bindparam("VIEW",$addView);
			$updateView->bindparam("ID",$GetId);
			$updateView->execute();

		}//END ADD VIEW ?>

            <?php require_once 'navbar.php'; ?>
            
            <?php if($Posts->URLVIDEO != ""){ ?>
            <div class="slideshow" >                
                
                <iframe src="<?php echo "https://www.youtube.com/embed/".$Posts->URLVIDEO ?>?rel=0" title="<?php echo $Posts->TITLE ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                
            </div><!--- slideshow ---><?php } ?>


            <?php   

                if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND isset($_GET['Active']) AND $_SESSION['pass']->ROLES === "ADMIN" ){

                    $checkPost = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND ACTIVE = '1' ");
                    $checkPost->execute();

                    if($checkPost->rowCount() == 0 ){

                        $updateActivePost = $database->prepare("UPDATE downp_post SET ACTIVE = '1' WHERE ID = :ID ");
                        $updateActivePost->bindparam("ID",$_GET['Active']);
                        
                        if($updateActivePost->execute()){
                            echo "<div class='right' >تم تنشيط الموضوع على الصفحة الرئيسية !</div>";

                        }

                    }else if($checkPost->rowCount() > 0 ){

                        $updateDeactivePost = $database->prepare("UPDATE downp_post SET ACTIVE = '0' WHERE ACTIVE = '1' ");
                        
                        $updateActivePost = $database->prepare("UPDATE downp_post SET ACTIVE = '1' WHERE ID = :ID ");
                        $updateActivePost->bindparam("ID",$_GET['Active']);

                        if($updateDeactivePost->execute() AND $updateActivePost->execute()){
                            echo "<div class='right' >تم تنشيط الموضوع على الصفحة الرئيسية !</div>";

                        }
                    }
                }// end Active Post 
                
                if(isset($_GET['alert']) AND intval($_GET['alert'])){

                    $updateAlert = $database->prepare("UPDATE downp_post SET ALERT = :ALERT WHERE ID = :ID");
                    $addAlert = $Posts->ALERT + 1 ; $updateAlert->bindparam("ALERT",$addAlert); $updateAlert->bindparam("ID",$_GET['alert']);

                    if($updateAlert->execute()){
                        echo "<div class='right' >تم إنذار هذه الصفحة سنتحقق من ذلك شكرا !</div>";
                    }

                }// end alert ?>
                
                <div class="titleR" ><p style="font-size: 14pt;"><?php echo $Posts->TITLE ?></p></div><!--- title --->
                
                <div class="list" >
                    <?php if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['pass']->ROLES === "ADMIN"){ ?>
                        <a href="show.php?post=<?php echo $Posts->ID ?>&Active=<?php echo $Posts->ID ?>" ><img src="img/home.png" />تعيين رئيسي</a>
                    <?php } ?>
                    <a href="show.php?post=<?php echo $Posts->ID ?>&alert=<?php echo $Posts->ID ?>"><img src="img/alert.png" />تبليغـ عن مشكل</a>
                    <p><img src="img/date.png" /><?php echo $Posts->DATE ?></p>
                    <p><img src="img/view.png" /><?php echo $Posts->VIEW ?></p>
                    <?php 
                    $toDoClass = $database->prepare("SELECT * FROM downp_class WHERE ID = :ID ");
                    $toDoClass->bindparam("ID",$Posts->CLASSID); $toDoClass->execute(); $Class = $toDoClass->fetchObject(); ?>
                    <a href="search.php?class=<?php echo $Class->ID ?>"><img src="img/menu.png" /><?php echo $Class->TITLE ?></a>
                    <?php 
                    $toDoUser = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
                    $toDoUser->bindparam("ID",$Posts->USERID); $toDoUser->execute(); $User = $toDoUser->fetchObject(); ?>
                    <a href="profile.php?id=<?php echo $User->ID ?>"><img src="img/user.png" /><?php echo $User->NAME ?></a>
                </div>

                <?php // VIEWED imgHrzn
                    $imgHrzn = $database->prepare("SELECT * FROM downp_files WHERE POSTID = :POSTID AND ROLES = 'POST' ORDER BY ID ASC ");
                    $imgHrzn->bindparam("POSTID",$Posts->ID);
                    $imgHrzn->execute();
                    
                if($imgHrzn->rowCount() > 0 OR $Posts->PICTURE != ""){ ?>

                <div class="imgHrzn">
                    
                    <img src="<?php echo $Posts->PICTURE ?>" />
                    
                    <?php foreach($imgHrzn AS $imgH){ 
                            
                        $whitelist_typeImg = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

                        if(in_array($imgH['TYPE'],$whitelist_typeImg)){ ?>
                        
                            <img src="<?php echo $imgH["FILENAME"] ?>" />
                            
                    <?php }} ?>

                </div><!--- end imgHrzn --->                                
                <?php }// end imgHrzn ?>

                <div class="topic" ><p><?php echo $Posts->TOPIC ?></p></div><!--- topic --->

                <?php // VIEWED fileHrzn
                    $fileHrzn = $database->prepare("SELECT * FROM downp_files WHERE POSTID = :POSTID AND ROLES = 'POST' ORDER BY ID ASC ");
                    $fileHrzn->bindparam("POSTID",$Posts->ID);
                    $fileHrzn->execute(); ?>

                <div class="list" >

                    <?php if($fileHrzn->rowCount() > 0){
                        
                        foreach($fileHrzn AS $fileH){ 
                            
                            $whitelist_typeApp = array('application/x-zip-compressed', 'application/octet-stream' );

                            if(in_array($fileH['TYPE'],$whitelist_typeApp)){ ?>

                            <a href="<?php echo $fileH["FILENAME"] ?>" download><img src="img/down.png" /><?php echo $fileH["TITLE"] ?></a>

                    <?php }}} ?>

                </div><!--- end list --->
                
                <?php require_once 'home.php'; ?>

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>
        
<?php
	}else{
		header("location:index.php",true);

	}
}catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>