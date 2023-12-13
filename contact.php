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
		
		<title>الرئيسية - <?php echo $Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene" />
        
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

            <?php
                if(isset($_GET['send']) AND $_GET['send'] == "msg"){
                    
                    if(isset($_POST['send_msg'])){
                        
                        $name  = strip_tags(trim($_POST['name']));
                        $email = strip_tags(trim($_POST['email']));
                        $text  = strip_tags(trim($_POST['text']));
                        $date  = date("d-m-Y"); 

                        $addMsg = $database->prepare("INSERT INTO downp_msg (NAME,EMAIL,TEXT,DATE) VALUES (:NAME,:EMAIL,:TEXT,:DATE)");

                        $addMsg->bindparam("NAME",$name);
                        $addMsg->bindparam("EMAIL",$email);
                        $addMsg->bindparam("TEXT",$text);
                        $addMsg->bindparam("DATE",$date);
                        
                        if(!empty($name) AND !empty($email) AND !empty($text)){
                                                        
                            if(isset($text) AND strlen($text) > 5000){
                                echo "<div class='error' >الرسالة الذي كتبتها أكبر من اللازم</div>";

                            }else if(isset($name) AND strlen($name) > 50){
                                echo "<div class='error' >الإسم الذي إخترته أكبر من اللازم</div>";

                            }else if(isset($email) AND !filter_var($email, FILTER_VALIDATE_EMAIL)){
                                echo "<div class='error' >البريد الإلكتروني غير صحيح !</div>";

                            }else if($addMsg->execute()){
                                echo "<div class='right' >تم الإرسال بنجاح !</div>";
                            }

                        }else { 
                            echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
                        }       
                                            
                    }// END send_msg ?>

                    <form class="forminput" method="POST" >                    
                        <div class="titleC" ><p>أرسل رسالة لنـا !</p></div><!--- title --->

                        <div class="inputclass" ><p>إسم المرسل</p><input type="text" name="name" required></div>
                        <div class="inputclass" ><p>البريد الإلكتروني</p><input type="email" name="email" required></div>
                        <div class="areaclass" ><p>الرسالة</p><textarea name="text" placeholder="أترك رسالة ..." required></textarea></div>

                        <div class="submitclass" >
                            <input type="submit" name="send_msg" value="إرسـال" >
                        </div>
                    </form><!--- forminput --->                  
                <?php }// end contact msg get  ?>

                <div class="topic" style="margin: 20px 0px;" ><p><?php if($Admin->CONDITIONS == ""){ echo "<div class='error' >صفحة سياسة الخصوصية لم يتم إضافتها بعد !</div>"; }else{ echo $Admin->CONDITIONS; } ?></p></div><!--- topic --->
                
                <?php require_once 'home.php'; ?>

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>           
    
<?php }catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>