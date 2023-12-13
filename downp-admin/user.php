<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass'])){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php';


        if(isset($_POST['update_user'])){
            
            $RowUser = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
            $RowUser->bindparam("ID",$Users->ID);
            $RowUser->execute();

            $name    = strip_tags(trim($_POST['name']));
            $age     = strip_tags(trim($_POST['age']));
            $sex     = strip_tags(trim($_POST['sex']));
            $address = strip_tags(trim($_POST['address']));
            $text   = $_POST['text'];
            $works   = strip_tags(trim($_POST['works']));
            $fcb     = strip_tags(trim($_POST['fcb']));
            $phone   = strip_tags(trim($_POST['phone']));

            if(preg_match("/facebook.com/i",$fcb) == 1 OR preg_match("/fb.com/i",$fcb) == 1){$RuFcb = $fcb;}else{$RuFcb = "";}

                $photoName = $_FILES['photo']['name'];
                $photoType = $_FILES['photo']['type'];
                $photoSize = $_FILES['photo']['size']/1024/1024;
                $photoData = $_FILES['photo']['tmp_name'];

                $ProPhotoName = "files/profiles/".$Users->ID."_".time().str_replace("image/",".",$photoType);

                $max_size = $Admin->FILESIZE;//MO

                $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');                 

            if($RowUser->rowCount() != 0){

                if(in_array($photoType,$whitelist_type) OR empty($_FILES['photo'])){ 

                    $updateUserData = $database->prepare("UPDATE downp_users SET NAME = :NAME ,AGE = :AGE ,SEX = :SEX ,ADDRESS = :ADDRESS ,TEXT = :TEXT, PROFILE = :PROFILE ,WORKS = :WORKS ,FCB = :FCB ,PHONE = :PHONE WHERE ID = :ID");

                    $updateUserData->bindparam("NAME",$name);
                    $updateUserData->bindparam("AGE",$age);
                    $updateUserData->bindparam("SEX",$sex);
                    $updateUserData->bindparam("ADDRESS",$address);
                    $updateUserData->bindparam("TEXT",$text);
                    $updateUserData->bindparam("PROFILE",$ProPhotoName );
                    $updateUserData->bindparam("WORKS",$works);
                    $updateUserData->bindparam("FCB",$RuFcb);
                    $updateUserData->bindparam("PHONE",$phone);
                    $updateUserData->bindparam("ID",$Users->ID);                    

                    if(file_exists("../".$Users->PROFILE)===true){

                        if($photoSize > $max_size) {
                            echo "<div class='error' >حجم اللوغو كبير لا يجب أن تتعدى ".$Admin->FILESIZE." ميغا !</div>";
                        
                        }else if($updateUserData->execute()){
                            if($Users->PROFILE != ""){unlink("../".$Users->PROFILE);}
                            move_uploaded_file($photoData,"../".$ProPhotoName);										
                            echo "<div class='right' >تم رفع صورة البروفايل و تعديل معلومات العضو</div>";
                
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }else if(!file_exists("../".$Users->PROFILE)===true){

                        if($updateUserData->execute()){	
                            move_uploaded_file($photoData,"../".$ProPhotoName);								
                            echo "<div class='right' >تم تعديل صورة البروفايل بعد رفعها و معلومات العضو</div>";
                
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }

                }else if(!empty($_FILES['photo'])){ 

                    $updateUserData = $database->prepare("UPDATE downp_users SET NAME = :NAME ,AGE = :AGE ,SEX = :SEX ,ADDRESS = :ADDRESS ,TEXT = :TEXT,WORKS = :WORKS, FCB = :FCB, PHONE = :PHONE  WHERE ID = :ID");

                    $updateUserData->bindparam("NAME",$name);
                    $updateUserData->bindparam("AGE",$age);
                    $updateUserData->bindparam("SEX",$sex);
                    $updateUserData->bindparam("ADDRESS",$address);
                    $updateUserData->bindparam("TEXT",$text);
                    $updateUserData->bindparam("WORKS",$works);
                    $updateUserData->bindparam("FCB",$RuFcb);
                    $updateUserData->bindparam("PHONE",$phone);
                    $updateUserData->bindparam("ID",$Users->ID);

                    if($updateUserData->execute()){
                        echo "<div class='right' >تم تعديل معلومات العضو بدون تعديل صورة البروفايل</div>";
            
                    }else{
                        echo "<div class='error' >فشل تعديل البيانات</div>";
                    }

                }
            }

        }/// END update_user


        if(isset($_POST['update_login'])){

            $updateUsersLogin = $database->prepare("UPDATE downp_users SET USERNAME = :USERNAME ,PASSWORD = :PASSWORD WHERE ID = :ID ");
            
            $username    = strip_tags(trim(strtoupper($_POST['username'])));                
            
            $oldpassword = strip_tags(trim(sha1($_POST['oldPasswod'])));
            $nepassword  = strip_tags(trim(sha1($_POST['nePassword'])));
            $repassword  = strip_tags(trim(sha1($_POST['rePassword'])));

            $updateUsersLogin->bindparam("USERNAME",$username);
            $updateUsersLogin->bindparam("PASSWORD",$nepassword);
            $updateUsersLogin->bindparam("ID",$Users->ID);

            if(empty($oldpassword) OR empty($nepassword) OR empty($repassword) OR empty($username)){
                echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";

            }else if($oldpassword != $Users->PASSWORD){
                echo "<div class='error' >كلمة السر القديمة غير صحيحة</div>";
                    
            }else if(strlen($nepassword) > 100 ){
                echo "<div class='error' >لقد تعدية عدد الأحرف المسموح بها !</div>";
    
            }else if($nepassword != $repassword){
                    echo "<div class='error' >كلمة السـر غير متشابهة في الخانتين</div>";

            }else if($updateUsersLogin->execute()){
                echo "<div class='right' >تم التعديل بنجاح</div>";
            
            }else{
                echo "<div class='error' >فشل تعديل البيانات</div>";
            }
            
        }/// END update_login


        // STAR Foreach User Show
        $ShowUsers = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
        $ShowUsers->bindparam("ID",$Users->ID);
        $ShowUsers->execute();
        foreach($ShowUsers AS $UsersJQ){

            if(isset($_GET['edit']) AND $_GET['edit'] === "info_user" ){ ?>

                <form class="forminput" name="compForm" method="POST" enctype="multipart/form-data"  onsubmit="if(validateMode()){this.text.value=oDoc.innerHTML;return true;}return false;" >
            
                    <div class="titleC" ><p >تعديل المعلومات الشخصية</p></div><!--- title --->

                    <div class="showimg" ><img id="thumbnil" src="<?php echo "../".$UsersJQ['PROFILE'] ?>" alt="" ></div>
                            
                    <div class="inputclass"  >
                        <p>إختر صورتك الشخصية</p>
                        <input id="upfile" onchange="showMyImage(this)" type="file" name="photo" accept="image/*" >
                        <div id="Btn" onclick="getFile()" >رفع الصورة بصيغة JPEG أو PNG</div>
                    </div>

                    <div class="inputclass" ><p>الإسم الكامل</p><input maxlength="50" type="text" value="<?php echo $UsersJQ['NAME'] ?>" name="name" required></div>
                    <?php $maxY = date("Y") - 14 ; $minY = date("Y") - 80 ; ?>
                    <div class="inputclass" ><p>سنة الميلاد</p><input  type="number" value="<?php echo $UsersJQ['AGE'] ?>" max="<?php echo $maxY ?>" min="<?php echo $minY ?>" placeholder="مثال 1990" name="age" required></div>

                    <div class="inputclass" >
                        <p>الجنس</p>		     
                        <select name="sex" required>
                            <option value="<?php echo $UsersJQ['SEX'] ?>" selected><?php echo $UsersJQ['SEX'] ?></option>
                            <option value="MAN" >ذكر</option>
                            <option value="WOMAN" >أنثى</option>
                        </select>
                            
                    </div>

                    <div class="inputclass" ><p>المهنة</p><input maxlength="80" value="<?php echo $UsersJQ['WORKS'] ?>" placeholder="مثال كهربائي , صباغ ..." type="text" name="works" required></div>
                    <div class="inputclass" ><p>عنوان المحل</p><input maxlength="50" value="<?php echo $UsersJQ['ADDRESS'] ?>" type="text" placeholder="إختياري" name="address" ></div>
                    <div class="inputclass" ><p>رقم الهاتف</p><input maxlength="15" value="<?php echo $UsersJQ['PHONE'] ?>" type="tel" placeholder="إختياري" name="phone" ></div>
                    <div class="inputclass" ><p>رابط الفيسبوك</p><input placeholder="ex: https://facebook.com/ammani.boumediene" type="url" value="<?php echo $UsersJQ['FCB'] ?>" name="fcb" ></div>

                    <div id="compForm"  >
                        <p>وصف</p>
                        <input type="hidden" name="text">                    								
                        <?php require_once 'richeditor.php'; ?>
                        <div id="textBox" contenteditable="true" ><?php echo $UsersJQ['TEXT'] ?></div>
                       <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
                    </div>   

                    <div class="submitclass" >
                       <input type="submit" name="update_user" value="تعديل المعلومات" >
                       <a href="files.php?role=USER&upId=<?php echo $Users->ID ?>" >إرفاقـ ملفـ أو صور</a>
                    </div>
            
                </form><!--- forminput --->

            <?php }// End edit Users ---

            if(isset($_GET['edit']) AND $_GET['edit'] === "login" ){ ?>

                <form class="forminput" method="POST" >
            
                <div class="titleC" ><p>تعديل معلومات الدخول</p></div><!--- title --->
                <div class="inputclass" ><p>إسم المستخدم</p><input maxlength="50" type="text" value="<?php echo $UsersJQ['USERNAME'] ?>" name="username" required></div>
                            
                <div class="inputclass" ><p>كلمة المرور القديمة</p><input maxlength="50" type="password" name="oldPasswod" required></div>
                <div class="inputclass" ><p>كلمة المرور الجديدة</p><input maxlength="50" type="password" name="nePassword" required></div>
                <div class="inputclass" ><p>إعادة كلة المرور</p><input maxlength="50"    type="password" name="rePassword" required></div>                

                <div class="submitclass" >
                    <input type="submit" name="update_login" value="تعديــل" >
                 </div>
            
                </form><!--- forminput --->

            <?php }/* End edit Login */
        
        }//End foreach UsersJQ ?>        
                

    </div><!--- content --->

    <?php require_once 'footer.php'; ?>

<?php

}else{
    header("location:index.php",true);
    
}
}catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>