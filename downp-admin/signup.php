<?php try{ require_once '../config.php'; session_start();

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['user']->ROLES === "ADMIN"){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php'; 
        
        
            if(isset($_POST['deleteUser']) AND isset($_POST['checkUser']) AND $Users->ROLES === "ADMIN"){
                    
                    $password = strip_tags(trim(sha1($_POST['password'])));
                            
                    if(!empty($_POST['checkUser']) AND isset($_POST['checkUser']) AND $password == $Users->PASSWORD ){
        
                        foreach($_POST['checkUser'] AS $checkAllUsers){
        
                            //----
                            $ShowUs = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID");
                            $ShowUs->bindParam("ID",$checkAllUsers); $ShowUs->execute();                            
                            foreach($ShowUs AS $Prof){ if($Prof['PROFILE'] != ""){ if(file_exists("../".$Prof['PROFILE'])===true){ unlink("../".$Prof['PROFILE']); } } }
                            
                            //----
                            $ShowPt = $database->prepare("SELECT * FROM downp_post WHERE USERID = :USERID ");
                            $ShowPt->bindParam("USERID",$checkAllUsers);
                            $ShowPt->execute(); $Pst = $ShowPt->fetchObject();
                            if(file_exists("../".$Pst->PICTURE)===true){ unlink("../".$Pst->PICTURE); }

                            //----
                            $ShowFl = $database->prepare("SELECT * FROM downp_files WHERE USERID = :USERID ");
                            $ShowFl->bindParam("USERID",$checkAllUsers); 
                            $ShowFl->execute();
                            foreach($ShowFl AS $File){ if(file_exists("../".$File['FILENAME'])===true){ unlink("../".$File['FILENAME']); } }

                            //----
                            $removF = $database->prepare("DELETE FROM downp_files WHERE USERID = :USERID ");
                            $removF->bindParam("USERID",$checkAllUsers);
                            $removF->execute(); 

                            //----
                            $removP = $database->prepare("DELETE FROM downp_post WHERE USERID = :USERID");
                            $removP->bindParam("USERID",$checkAllUsers); $removP->execute();
                              
                            //----
                            $removU = $database->prepare("DELETE FROM downp_users WHERE ID = :ID");
                            $removU->bindParam("ID",$checkAllUsers); $removU->execute();                            
                                                            
                        }
        
                        if(isset($ShowUs) OR isset($ShowFl) OR isset($ShowPt) OR isset($removF) OR isset($removU) OR isset($removP)){
                            echo "<div class='right' >تم الحذف المشتركين بنجاح !</div>";
        
                        }else {
                            echo "<div class='error' >حدث خطأ غير متوقع !</div>";
                        }
        
                    }else{                            
                        echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
                    }
        
                }// END deleteUser
                        
                if(isset($_POST['acceptUser']) AND isset($_POST['checkUser']) AND $Users->ROLES === "ADMIN"){
        
                    $password   = strip_tags(trim(sha1($_POST['password'])));
        
                    if(!empty($_POST['checkUser']) AND isset($_POST['checkUser']) AND $password == $Users->PASSWORD ){                            
        
                        foreach($_POST['checkUser'] AS $checkAllUsers){
        
                            $ShowAccptU = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
                            $ShowAccptU->bindParam("ID",$checkAllUsers);
                            $ShowAccptU->execute(); $AccptU = $ShowAccptU->fetchObject();
                                    
                            if($AccptU->ACTIVATED == true){ 
                                $acceptUsersF = $database->prepare("UPDATE downp_users SET ACTIVATED = '0' WHERE ID = :ID ");
                                $acceptUsersF->bindParam("ID",$checkAllUsers); $acceptUsersF->execute();
                            }
                                
                            if($AccptU->ACTIVATED == false){ 
                                $acceptUsersT = $database->prepare("UPDATE downp_users SET ACTIVATED = '1' WHERE ID = :ID ");
                                $acceptUsersT->bindParam("ID",$checkAllUsers); $acceptUsersT->execute();
                            }
        
                        }//END FOREACH checkUser
                                
                        if(isset($acceptUsersT) OR isset($acceptUsersF)){
                            echo "<div class='right' >تم التعديل بنجاح !</div>";
                        }
                                
                    }else{
                        echo "<div class='error' >إختر ما يجب التعديل عليه و أدخل كلمة المرور الصحيحة</div>";
                    }
                            
                }// END acceptUser
                        
        
                // users show
                if(isset($_GET['profile']) AND intval($_GET['profile'])){
        
                    $ShowProRus = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID AND ROLES = 'USER' ");
                    $ShowProRus->bindParam("ID",$_GET['profile']);
                    $ShowProRus->execute();
        
                    if($ShowProRus->rowCount() > 0){ $profile = $ShowProRus->fetchObject(); ?>
                
                        <div class="bigImg" >
                            <img src="../files/class/class.jpg" >
                            <p><?php echo $profile->NAME ?></p>
                        </div>
                        <?php if($profile->SEX == "MAN"){echo"<style>.bigImg p{background-color: #054c5e60;}</style>";}elseif($profile->SEX == "WOMAN"){echo"<style>.bigImg p{background-color: #c72b2b60;}</style>";} ?>
                
                        <div class="profile" >
                            <?php if($profile->PROFILE === ""){$RusProfile = "img/profile.png";}else{$RusProfile = $profile->PROFILE;} ?>
                            <img src="../<?php echo $RusProfile ?>" >
                        </div><!--- profile --->                                                  
        
                        <div class="list" >
        
                            <?php if($profile->DATEUP != "" ){ ?><p><img src="../img/date.png" ><?php echo $profile->DATEUP ?></p><?php } ?>
        
                            <?php if($profile->AGE != "" AND $profile->USERNAME != "" ){ ?><p><img src="../img/user.png" ><?php echo $profile->USERNAME ?> [ <?php echo date("Y") - $profile->AGE ?> سنة ]</p><?php } ?>
                                    
                            <?php if($profile->ADDRESS != "" ){ ?><address><img src="../img/home.png" ><?php echo $profile->ADDRESS ?></address><?php } ?>
        
                            <?php if($profile->EMAIL != "" ){ ?><address><img src="../img/email.png" ><?php echo $profile->EMAIL ?></address><?php } ?>
        
                            <?php if($profile->FCB != "" ){ ?><a href="<?php echo $profile->FCB ?>" ><img src="../img/fcb.png" >صفحة الفيسبوك !</a><?php } ?>
        
                            <p><img src="../img/view.png" ><?php echo $profile->VIEW ?></p>
        
                            <?php if($profile->PHONE != "" ){ ?><a href="tel:<?php echo $profile->PHONE ?>" dir="ltr" ><img src="../img/tel.png" ><?php echo $profile->PHONE ?></a><?php } ?>
        
                            <a href="../profile.php?id=<?php echo $profile->ID ?>" ><img src="../img/plus.png" >معرفة المزيد</a>
        
                        </div><!--- infolist --->
        
                        <div class="topic" ><?php echo $profile->TEXT ?></div><!--- topic --->
        
                <?php   }

                }// end show User profile 


            if(isset($_POST['register']) AND $Users->ROLES === "ADMIN" ){
	
                // STAR POST
                $password   = strip_tags(trim(sha1($_POST['password'])));
                $repassword = strip_tags(trim(sha1($_POST['repassword'])));
                $username   = strip_tags(trim(strtoupper($_POST['username'])));
                $email      = strip_tags(trim($_POST['email']));
                $name       = strip_tags(trim($_POST['name']));
                $age        = strip_tags($_POST['age']);
                $sex        = strip_tags($_POST['sex']);
                $works      = strip_tags($_POST['works']);
                $address    = strip_tags($_POST['address']);
                $phone      = strip_tags($_POST['phone']);
                
                $correcteUn = str_replace(" ","",$username);
            
                $checkEmail = $database->prepare("SELECT * FROM downp_users WHERE EMAIL = :EMAIL");
                $checkUname = $database->prepare("SELECT * FROM downp_users WHERE USERNAME = :USERNAME");
            
                $checkEmail->bindparam("EMAIL",$email);
                $checkUname->bindparam("USERNAME",$correcteUn);
                
                $checkEmail->execute();
                $checkUname->execute();
            
                if(empty($password) OR empty($username) OR empty($email) OR empty($name) OR empty($age) OR empty($sex) OR empty($works)){
                    echo "<div class='error' >عبأ الخانة الفارغة</div>";
            
                }else if($checkEmail->rowCount()>0){
                    echo "<div class='error' >الإمــيل مستخدم في موقعنـا</div>";
            
                }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    echo "<div class='error' >الإمـيل غير صحيح</div>";
            
                }else if(isset($age) AND strlen($age) > 4 AND !is_numeric($age)){
                    echo "<div class='error' >أكتب سنة الميلاد بشكل صحيح</div>";
            
                }else if(isset($phone) AND strlen($phone) > 15){
                    echo "<div class='error' >أكتب رقم الهاتف بشكل صحيح !</div>";
            
                }else if($checkUname->rowCount()>0){
                    echo "<div class='error' >اسم المستخدم موجود اختر إسم مستخدم آخر</div>";
            
                }else if($password != $repassword){
                    echo "<div class='error' >كلمة السـر غير متشابهة في الخانتين</div>";
            
                }else{
            
                    $DateUp = date("d-m-Y");
                     
                    $addUser = $database->prepare("INSERT INTO downp_users (PASSWORD,USERNAME,AGE,EMAIL,PHONE,NAME,SEX,WORKS,ADDRESS,ACTIVATED,DATEUP,ROLES) 
                    VALUES
                    (:PASSWORD,:USERNAME,:AGE,:EMAIL,:PHONE,:NAME,:SEX,:WORKS,:ADDRESS,'1',:DATEUP,'USER')");
            
                    $addUser->bindparam("PASSWORD",$password);
                    $addUser->bindparam("USERNAME",$correcteUn);
                    $addUser->bindparam("AGE",$age);
                    $addUser->bindparam("EMAIL",$email);
                    $addUser->bindparam("PHONE",$phone);
                    $addUser->bindparam("NAME",$name);
                    $addUser->bindparam("SEX",$sex);
                    $addUser->bindparam("WORKS",$works);
                    $addUser->bindparam("ADDRESS",$address);
                    $addUser->bindparam("DATEUP",$DateUp);;
            
            
                    if($addUser->execute()){
                        
                        echo "<div class='right' >تم إضافة العضو المساعد بنجاح</div>";

                    }else{
                        
                        echo "<div class='error' >حدث خطأ غير متوقع يمكن أن تكون الأنترنت ضعيفة</div>";
                        
                        $removusers = $database->prepare("DELETE FROM downp_users WHERE EMAIL = :EMAIL AND USERNAME = :USERNAME AND PASSWORD = :PASSWORD ");
                                    
                        $removusers->bindparam("EMAIL",$email);
                        $removusers->bindparam("USERNAME",$correcteUn);
                        $removusers->bindparam("PASSWORD",$password);
                        $removusers->execute();

                    }
                }
                
            } ?>

            <form class="forminput" method="POST" >
                
                <div class="titleC" ><p>معلومات الدخول</p></div><!--- title --->
                <div class="inputclass" ><p>إسم المستخدم</p><input maxlength="50" type="text" name="username" required></div>
                <div class="inputclass" ><p>البريد الإلكتروني</p><input maxlength="50" type="email" name="email" required></div>
                <div class="inputclass" ><p>كلمة المرور</p><input maxlength="50" type="password" name="password" required></div>
                <div class="inputclass" ><p>إعادة كلة المرور</p><input maxlength="50" type="password" name="repassword" required></div>

                <div class="titleC" ><p >معلومات شخصية</p></div><!--- title --->
                <div class="inputclass" ><p>الإسم الكامل</p><input maxlength="50" type="text" name="name" required></div>
                <?php $maxY = date("Y") - 14 ; $minY = date("Y") - 80 ; ?>
                <div class="inputclass" ><p>سنة الميلاد</p><input  type="number"  max="<?php echo $maxY ?>" min="<?php echo $minY ?>" placeholder="مثال 1990" name="age" required></div>

                <div class="inputclass" > 
                    <p>الجنس</p>		     
					<select name="sex" required>
                        <option value="" selected>إختر الجـنس</option>
					    <option value="MAN" >ذكر</option>
					    <option value="WOMAN" >أنثى</option>
					</select>
								
				</div>

                <div class="inputclass" ><p>المهنة</p><input maxlength="80" placeholder="مثال كهربائي , صباغ ..." type="text" name="works" required></div>
                <div class="inputclass" ><p>عنوان المحل</p><input maxlength="50" type="text" placeholder="إختياري" name="address" ></div>
                <div class="inputclass" ><p>رقم الهاتف</p><input maxlength="15" type="tel" placeholder="إختياري" name="phone" ></div>

                <div class="submitclass" >
                    <input type="submit" name="register" value="إضافة مساعد" >                   
                </div>
                
            </form><!--- forminput --->


        <form  method="POST" >

            <div class="submitclass" >
                <input type="submit" style="background-color: red;" name="deleteUser" value="حذف الأعضاء" >
                <input type="submit"   name="acceptUser"    value="تفعيل - تعطيل" >
                <input type="password" name="password" placeholder="كلمة المرور"  >
            </div>
                    
            <table class="table" >
                <tr>
                    <th>-</th>
                    <th>الإسم</th>
                    <th>المشاهدة</th>
                    <th>المواضيع</th>
                    <th>الإنـذار</th>
                    <th>التاريخ</th>
                </tr>

                <?php
                if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }
                if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }

                $RowUsers = $database->prepare("SELECT * FROM downp_users WHERE ROLES = 'USER' ");
                $RowUsers->execute(); $allUsers = $RowUsers->rowCount();

                $start = $per_page * $page - $per_page; $pages = ceil($allUsers / $per_page);

                $ShowUsers = $database->prepare("SELECT * FROM downp_users WHERE ROLES = 'USER' ORDER BY ALERT DESC LIMIT $start,$per_page");
                $ShowUsers->execute(); ?>

                    <div class="titleC" style="margin-top: 15px;" ><p>جميع الأعضاء [<?php echo $ShowUsers->rowCount() ?>]</p></div><!--- title --->

                    <?php if($ShowUsers->rowCount() > 0){ echo "<div class='error' >عند حذف العضو المساعد سيتم حذف جميع المواضيع الخاصة به !</div>"; }// end row 
                    
                    foreach($ShowUsers AS $UsersJQ){ ?>
                        <tr>
                            <th style="width: 100px;" ><img src="<?php if($UsersJQ['PROFILE'] != "" ){ echo "../".$UsersJQ['PROFILE']; }else{ echo "../files/profiles/profile.png"; } ?>"/></th>                            
                            <th><?php                            
                                if($UsersJQ['ACTIVATED'] == true){
                                    echo "<span style='background-color: seagreen;'></span>";

                                }elseif($UsersJQ['ACTIVATED'] == false){ 
                                    echo "<span style='background-color: red;'></span>";
                                } ?>
                                <a href="?profile=<?php echo $UsersJQ['ID'] ?>" ><?php echo substr($UsersJQ['NAME'],0 ,150) ?></a>
                            </th>                            
                            <th><?php echo $UsersJQ['VIEW'] ?></th>

                            <th><?php 
                            $RowUserPost = $database->prepare("SELECT * FROM downp_post WHERE USERID = :USERID ");
                            $RowUserPost->bindParam("USERID",$UsersJQ['ID']); $RowUserPost->execute();                            
                            echo $RowUserPost->rowCount() ?></th>
                            
                            <th><?php echo $UsersJQ['ALERT'] ?></th>
                            <th><?php echo $UsersJQ['DATEUP'].""; ?>
                                <input name="checkUser[]" value="<?php echo $UsersJQ["ID"] ?>" type="checkbox" />
                            </th>
                        </tr>
                    <?php }//end foreach ?>

            </table>

        </form><!--- end form --->

        <div class="pages" >  
            <?php if($pages > $page AND $page > 0){ ?><a style="background-color:  #000000;" href="?page=<?php echo ($page+1) ?>" >المزيد</a>
            <?php }if($page > 1 AND $page < ($pages + 1)){  ?><a style="background-color: #333333;" href="?page=<?php echo ($page-1) ?>" >الرجوع</a>
            <?php }if($page < 0 OR !intval($page) OR $page > $pages ){ echo "<div class='error' >لا يوجد ما  تبحث عنه في هذا الرابط !</div>"; } ?>
        </div><!--- END CLASS PAGES --->

        </div><!--- content --->
        
        <?php require_once 'footer.php'; ?>

<?php

}else{
    header("location:index.php",true);

}
}catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>