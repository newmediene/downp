<?php try{ require_once '../config.php'; session_start(); if(!isset($_SESSION['user']) AND !isset($_SESSION['mail']) AND !isset($_SESSION['pass'])){
    
    $toDoAdmin = $database->prepare("SELECT * FROM downp_admin WHERE ID = '1' ");
    $toDoAdmin->execute();
    $Admin = $toDoAdmin->fetchObject(); ?>

<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" >

        <link rel="stylesheet" href="../style/home.css">
		
        <link rel="icon" href="../files/logo/icon.png" >
        <link rel="shortcut" href="../files/logo/icon.png" >
        <link rel="apple-touch-icon" href="../files/logo/icon.png" >
		
		<title>الدخول</title>
        
    </head>

    <body>

        <div id="box" >

            <div class="centerlogo" ><a href="../index.php" ><img alt="<?php echo $Admin->SITENAME ?>" src="../<?php echo $Admin->PICTURELOGO ?>" ></a></div><!--- centerlogo --->

            <?php
			
			if(isset($_POST['login'])){
				
				$password   = strip_tags(trim(sha1($_POST['password'])));
				$username   = strip_tags(trim(strtoupper($_POST['username'])));
				
				$correcteUn = str_replace(" ","",$username);

				$login = $database->prepare("SELECT * FROM downp_users WHERE USERNAME = :USERNAME AND PASSWORD = :PASSWORD OR EMAIL = :USERNAME AND PASSWORD = :PASSWORD");
				
				$login->bindParam("USERNAME",$correcteUn);
				$login->bindParam("PASSWORD",$password);
				$login->execute();

				if($login->rowCount()===1){
					
					$user = $login->fetchObject();
					
					if($user->ACTIVATED === "1"){

						
						$_SESSION['user'] = $user;
						$_SESSION['mail'] = $user;
						$_SESSION['pass'] = $user;

						if($user->ROLES === "USER" OR $user->ROLES === "ADMIN"){
							header("location:stat.php",true);

						}

					}else if($user->ACTIVATED === "0"){
						echo "<div class='error' >تم تعطيل حسابك بسبب مخالفتك لقوانين الموقع</div>";
				    }else{
						echo "<div class='error' >حسابك لم يتم تفعيله بعد , لقد سبق و أرسلنا رمز التحقق من حسابك إلى بريدك الإلكتروني</div>";
					}

				}else{
					echo "<div class='error' >المعلومات خاطئة</div>";
				}

			} ?>

            
            <form class="forminput" method="POST" >

                <div class="titleC" ><p>معلومات الدخول</p></div><!--- title --->
                <div class="inputclass" ><p>إسم المستخدم أو البريد الإلكتروني</p><input maxlength="50" type="text" name="username" required></div>
                <div class="inputclass" ><p>كلة المرور</p><input maxlength="50" type="password" name="password" required></div>

                <div class="submitclass" >
                    <input type="submit" name="login" value="الدخــول" >
                    <a href="../index.php" >الرئيسية</a>
                </div>
                
            </form><!--- forminput --->

        </div><!--- box ---> 

    </body>

</html>
<?php }else { header("location:stat.php",true); } }catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>