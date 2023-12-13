<?php try{ require_once 'config.php'; session_start(); 

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
		
		<title>بحث - <?php echo $Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene"/>
        
        <meta property="og:title" content="بحث - <?php echo $Admin->SITENAME ?>" />
        <meta property="og:description" content="<?php echo $Admin->DESCRIPTION ?>" />
        <meta property="og:image" content="<?php echo $Admin->PICTURELOGO ?>" />
        <meta property="og:image:width" content="1200" />  
        <meta property="og:image:height" content="630"/>
        <meta property="og:type" content="website" />
        
    </head>

    <body>

        <div id="content" >

            <?php require_once 'navbar.php'; ?>

                              
                <?php // SEARCH IN CLASS                
                if(!isset($_GET['submitH']) AND isset($_GET['class']) AND intval($_GET['class']) AND is_numeric($_GET['class'])){
                    
                    $getID = intval($_GET['class']);
                    $checkClass = $database->prepare("SELECT * FROM downp_class WHERE ID = :GETID ");
                    $checkClass->bindparam("GETID",$getID); $checkClass->execute();

                    if($checkClass->rowCount() > 0 ){

                        if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }

					    if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = $Admin->NUMBERPOSTS; }
                    
                        $ShowClass = $database->prepare("SELECT * FROM downp_class WHERE ID = :ID ");		
                        $ShowClass->bindParam("ID",$getID); $ShowClass->execute();
                        $FetClass = $ShowClass->fetchObject();

                        if($FetClass->ROLES === "POST" OR $FetClass->ROLES === "BASIC" ){
					        $Row = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND CLASSID = :CLASSID ");		
                            $Row->bindParam("CLASSID",$getID);
					        $Row->execute(); $All = $Row->rowCount();

                        }
                                                
                        if(!isset($_SESSION['user']) AND !isset($_SESSION['mail']) AND !isset($_SESSION['pass'])){
			
                            $updateView = $database->prepare("UPDATE downp_class SET VIEW = :VIEW WHERE ID = :ID");
                                        
                            $addView = $FetClass->VIEW + 1 ;
            
                            $updateView->bindparam("VIEW",$addView);
                            $updateView->bindparam("ID",$getID);
                            $updateView->execute();
            
                        }//END ADD VIEW
					
					    $start = $per_page * $page - $per_page; $pages = ceil($All / $per_page);

                        if($FetClass->ROLES === "POST" OR $FetClass->ROLES === "BASIC" ){    
                            $Show = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND CLASSID = :CLASSID ORDER BY ID DESC LIMIT $start,$per_page ");
                            $Show->bindParam("CLASSID",$getID);
                            $Show->execute();

                        } ?>
                    
                        <div class="bigImg" >
                            <img src="<?php echo $FetClass->PICTURE ?>" >
                            <p><?php echo $FetClass->TITLE ?></p>
                        </div>
                        
                        <div class="list" >
                            <p><img src="img/view.png" /><?php echo $FetClass->VIEW ?></p>
                            <p><img src="img/menu.png" /><?php echo $All ?></p>
                        </div>
                    
                        <div class="home" > 
                            <?php 
                                if($FetClass->ROLES === "POST" OR $FetClass->ROLES === "BASIC" ){

                                    foreach($Show AS $JQ){  ?>                    
                                        <div class="post" >

                                            <?php if($JQ['ACTIMGVID'] == 1 AND $JQ['URLVIDEO'] != "" ){ 
                            
                                                $picture = "https://i.ytimg.com/vi/".$JQ['URLVIDEO']."/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw"; 
                         
                                            }else{
                         
                                                $picture = $JQ['PICTURE'];

                                            } ?>

                                            <div class="image" ><a href="show.php?post=<?php echo $JQ['ID'] ?>" ><img src="<?php echo $picture ?>" /></a></div><!--- image --->
                                            <div class="info" >
                                                <div class="title" ><a href="show.php?post=<?php echo $JQ['ID'] ?>" ><?php echo $JQ['TITLE'] ?></a></div><!--- title --->
                                                    <?php $ShowUser = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
                                                    $ShowUser->bindParam("ID",$JQ['USERID']); $ShowUser->execute(); $UserP = $ShowUser->fetchObject(); ?>
                                                <div class="topic" ><p><?php echo $JQ['DATE'] ?> | <a href="profile.php?id=<?php echo $JQ['USERID'] ?>" ><?php echo $UserP->NAME ?></a></p></div><!--- topic --->
                                            </div>
                                        </div><!--- post --->
                                    <?php }// end posts post

                                }  ?>
                        </div><!--- home --->

                        <div class="pages" >
  
                            <?php if($pages > $page AND $page > 0){ ?>
                         
                                <a style="background-color:  #000000;" href="?class=<?php echo $getID ?>&page=<?php echo ($page+1) ?>" >المزيد</a>
                         
                            <?php }if($page > 1 AND $page < ($pages + 1)){  ?>	
  
                                <a style="background-color: #333333;" href="?class=<?php echo $getID ?>&page=<?php echo ($page-1) ?>" >الرجوع</a>
  
                            <?php }if($page < 0 OR !intval($page) OR $page > $pages ){ echo "<div class='error' >لا يوجد ما  تبحث عنه في هذا الرابط !</div>"; } ?>
                         
                        </div><!--- END CLASS PAGES --->
                    
                    <?php }else{header("location:index.php",true);}// check posts search

                }
                
                if(isset($_GET['submitH']) AND $_GET['submitH'] == "Ok" AND isset($_GET['search'])){

                    if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }

					if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = $Admin->NUMBERPOSTS; }

                    $likeSerch = "%".$_GET['search']."%";

					$Row = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND TITLE LIKE :TITLE ");		
                    $Row->bindParam("TITLE",$likeSerch );
					$Row->execute();

                    $fetchVideo = $Row->fetchObject();					
					$All   =  $Row->rowCount();
					
					$start = $per_page * $page - $per_page;

					$pages = ceil($All / $per_page);

                    $Show = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' AND TITLE LIKE :TITLE  ORDER BY ID DESC LIMIT $start,$per_page ");
                    $Show->bindParam("TITLE",$likeSerch ); $Show->execute(); ?>
                    <div class="titleR" ><p><img src="img/post.png" >بحث عن [ <?php echo $_GET['search'] ?> ] عددها : <?php echo $All ?></p></div><!--- title --->                    
                    <div class="home" >
                        <?php
                        foreach($Show AS $JQ){  ?>
                        <div class="post" >

                            <?php if($JQ['ACTIMGVID'] == 1 AND $JQ['URLVIDEO'] != "" ){ 
                            
                                $picture = "https://i.ytimg.com/vi/".$JQ['URLVIDEO']."/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw"; 
                             
                            }else{
                             
                                $picture = $JQ['PICTURE'];

                            } ?>

                            <div class="image" ><a href="show.php?post=<?php echo $JQ['ID'] ?>" ><img src="<?php echo $picture ?>" /></a></div><!--- image --->
                            <div class="info" >
                                <div class="title" ><a href="show.php?post=<?php echo $JQ['ID'] ?>" ><?php echo $JQ['TITLE'] ?></a></div><!--- title --->
                                <div class="topic" ><p><?php echo $JQ['DATE'] ?></p></div><!--- topic --->
                            </div>

                        </div><!--- post --->                    
                        <?php }/* end posts post */  ?>
                    </div><!--- home --->

                    <div class="pages" >
  
                        <?php if($pages > $page AND $page > 0){ ?>
                         
                            <a style="background-color:  #000000;" href="?search=<?php echo $_GET['search'] ?>&submitH=Ok&page=<?php echo ($page+1) ?>" >المزيد</a>
                         
                         <?php }if($page > 1 AND $page < ($pages + 1)){  ?>	
  
                            <a style="background-color: #333333;" href="?search=<?php echo $_GET['search'] ?>&submitH=Ok&page=<?php echo ($page-1) ?>" >الرجوع</a>
  
                        <?php }if($page < 0 OR !intval($page) OR $page > $pages ){

                            echo "<div class='error' >لا يوجد ما  تبحث عنه في هذا الرابط !</div>";
                        } ?>
                         
                    </div><!--- END CLASS PAGES --->
                    
                <?php } ?>

                <?php require_once 'home.php'; ?>

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>
        
<?php }catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>