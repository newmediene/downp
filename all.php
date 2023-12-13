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
		
		<title>جميع - <?php echo $Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene" />
        
        <meta property="og:title" content="جميع امواضيع - <?php echo $Admin->SITENAME ?>" />
        <meta property="og:description" content="<?php echo $Admin->DESCRIPTION ?>" />
        <meta property="og:image" content="<?php echo $Admin->PICTURELOGO ?>" />
        <meta property="og:image:width" content="1200" />  
        <meta property="og:image:height" content="630"/>
        <meta property="og:type" content="website" />
        
    </head>

    <body>

        <div id="content" >

            <?php require_once 'navbar.php'; ?>
            
                <div class="home" >
                    
                <?php                    
					if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }
                    if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = $Admin->NUMBERPOSTS; }

					$RowPosts = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' "); $RowPosts->execute();					
					$AllPosts = $RowPosts->rowCount();					
					$start = $per_page * $page - $per_page;$pages = ceil($AllPosts / $per_page);

				    $ShowPosts = $database->prepare("SELECT * FROM downp_post WHERE ACCEPTABLE = '1' ORDER BY ID DESC LIMIT $start,$per_page");				
				    $ShowPosts->execute();			
				    foreach($ShowPosts AS $Posts){ ?>
                    <div class="post" >

                        <?php if($Posts['ACTIMGVID'] == 1 AND $Posts['URLVIDEO'] != "" ){ 
                            
                            $picture = "https://i.ytimg.com/vi/".$Posts['URLVIDEO']."/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw"; 
                             
                        }else{
                             
                            $picture = $Posts['PICTURE'];

                        } ?>

                        <div class="image" >

                            <a href="show.php?post=<?php echo $Posts['ID'] ?>" ><img src="<?php echo $picture ?>" /></a>
                        
                        </div><!--- image --->
                        
                        <div class="info" >
                            <div class="title" ><a href="show.php?post=<?php echo $Posts['ID'] ?>" ><?php echo $Posts['TITLE'] ?></a></div><!--- title --->
                            <?php $ShowUser = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
                                  $ShowUser->bindParam("ID",$Posts['USERID']); $ShowUser->execute(); $UserP = $ShowUser->fetchObject(); ?>
                            <div class="topic" style="font-size:8pt;" ><p><?php echo $Posts['DATE'] ?> | <a href="profile.php?id=<?php echo $Posts['USERID'] ?>" ><?php echo $UserP->NAME ?></a></p></div><!--- topic --->
                        </div>

                    </div><!--- post --->
                    <?php }/* end posts posts */ ?>
                    
                </div><!--- home --->

                <div class="pages" >
  
                    <?php if($pages > $page AND $page > 0){ ?>
                         
                        <a style="background-color:  #000000;" href="?page=<?php echo ($page+1) ?>" >المزيد</a>
                         
                    <?php }if($page > 1 AND $page < ($pages + 1)){  ?>	
  
                        <a style="background-color: #333333;" href="?page=<?php echo ($page-1) ?>" >الرجوع</a>
  
                    <?php }if($page < 0 OR !intval($page) OR $page > $pages ){

                        echo "<div class='error' >لا يوجد ما  تبحث عنه في هذا الرابط !</div>";
                    } ?>
                         
                </div><!--- END CLASS PAGES --->
                

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>           
        
<?php }catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>