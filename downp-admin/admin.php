<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['user']->ROLES === "ADMIN"){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php';
        
        if(isset($_POST['update_admin']) AND $Users->ROLES === 'ADMIN' ){
            
            $RowAdmin = $database->prepare("SELECT * FROM downp_admin ");
            $RowAdmin->execute();

            $sitename    = strip_tags(trim($_POST['sitename']));
            $description = strip_tags(trim($_POST['description']));
            $numberposts = strip_tags(trim($_POST['numberposts']));
            $numberclass = strip_tags(trim($_POST['numberclass']));
            $filesize    = strip_tags(trim($_POST['filesize']));
            $conditions  = $_POST['conditions'];

                $photoName = $_FILES['photo']['name'];
                $photoType = $_FILES['photo']['type'];
                $photoSize = $_FILES['photo']['size']/1024/1024;
                $photoData = $_FILES['photo']['tmp_name'];

                $pictureLogo = "files/logo/".time().str_replace("image/",".",$photoType);

                $max_size = $Admin->FILESIZE;//MO

                $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');                 

            if($RowAdmin->rowCount() != 0){

                if(in_array($photoType,$whitelist_type) OR empty($_FILES['photo'])){ 

                    $updateAdminData = $database->prepare("UPDATE downp_admin SET 
                    SITENAME = :SITENAME ,NUMBERPOSTS = :NUMBERPOSTS ,NUMBERCLASS = :NUMBERCLASS ,FILESIZE = :FILESIZE, 
                    PICTURELOGO = :PICTURELOGO ,DESCRIPTION = :DESCRIPTION ,CONDITIONS = :CONDITIONS WHERE ID = '1' ");

                    $updateAdminData->bindparam("SITENAME",$sitename);
                    $updateAdminData->bindparam("NUMBERPOSTS",$numberposts);
                    $updateAdminData->bindparam("NUMBERCLASS",$numberclass);
                    $updateAdminData->bindparam("FILESIZE",$filesize);
                    $updateAdminData->bindparam("PICTURELOGO",$pictureLogo);
                    $updateAdminData->bindparam("DESCRIPTION",$description);
                    $updateAdminData->bindparam("CONDITIONS",$conditions);                                        

                    if(file_exists("../".$Admin->PICTURELOGO)===true){

                        if($photoSize > $max_size) {
                            echo "<div class='error' >حجم اللوغو كبير لا يجب أن تتعدى ".$Admin->FILESIZE." ميغا !</div>";
                        
                        }else if($updateAdminData->execute()){  
                            unlink("../".$Admin->PICTURELOGO);
                            move_uploaded_file($photoData,"../".$pictureLogo);										
                            echo "<div class='right' >تم تعديل اللوغو و معلومات الموقع</div>";
                
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }else if(!file_exists("../".$Admin->PICTURELOGO)===true){

                        if($photoSize > $max_size) {
                            echo "<div class='error' >حجم اللوغو كبير لا يجب أن تتعدى ".$Admin->FILESIZE." ميغا !</div>";
                        
                        }else if($updateAdminData->execute()){	
                            move_uploaded_file($photoData,"../".$pictureLogo);								
                            echo "<div class='right' >تم تعديل اللوغو بعد رفعه و معلومات الموقع</div>";
                
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }

                }else if(!empty($_FILES['photo'])){ 

                    $updateAdminData = $database->prepare("UPDATE downp_admin SET 
                    SITENAME = :SITENAME ,NUMBERPOSTS = :NUMBERPOSTS ,NUMBERCLASS = :NUMBERCLASS ,FILESIZE = :FILESIZE ,DESCRIPTION = :DESCRIPTION ,CONDITIONS = :CONDITIONS WHERE ID = '1' ");

                    $updateAdminData->bindparam("SITENAME",$sitename);
                    $updateAdminData->bindparam("NUMBERPOSTS",$numberposts);
                    $updateAdminData->bindparam("NUMBERCLASS",$numberclass);
                    $updateAdminData->bindparam("FILESIZE",$filesize);
                    $updateAdminData->bindparam("DESCRIPTION",$description);
                    $updateAdminData->bindparam("CONDITIONS",$conditions);

                    if($updateAdminData->execute()){
                        echo "<div class='right' >تم تعديل معلومات الموقع بدون تعديل اللوغو</div>";
            
                    }else{
                        echo "<div class='error' >فشل تعديل البيانات</div>";
                    }

                }
            }

        }/// END update_admin

        if(isset($_POST['update_Adsense']) AND $Users->ROLES === 'ADMIN' ){

            $updateAdsense = $database->prepare("UPDATE downp_admin SET CODEADSENSE = :CODEADSENSE WHERE ID = '1' ");               
            
            $code = trim($_POST['code']);
            $password   = strip_tags(trim(sha1($_POST['password'])));

            $updateAdsense->bindparam("CODEADSENSE",$code);

            if(!strip_tags($code) AND $password == $Users->PASSWORD){                    

                if($updateAdsense->execute()){
                    echo "<div class='right' >تم التعديل بنجاح</div>";
            
                }else{
                    echo "<div class='error' >فشل تعديل البيانات</div>";
                }

            }else {
                echo "<div class='error' >كلمة المرور غير صحيحة أو الكود ليس مكتوب بشكل صحيح تأكد جيدا من ذلك !</div>";
            }
            
        }/// END update_Adsense

        
        $ShowAdmin = $database->prepare("SELECT * FROM downp_admin WHERE ID = '1' ");
        $ShowAdmin->execute();
        foreach($ShowAdmin AS $AdminJQ){

            if(isset($_GET['edit']) AND $_GET['edit'] === "adsense" AND $Users->ROLES === "ADMIN" ){ ?>
            
                <form class="forminput" method="POST" >                
                    <div class="titleC" ><p>تعديل كود إعلانات أدسنس</p></div><!--- title --->

                    <div class="error" ><b>ملاحظة 01 :</b> أي خطأ في الكود يأدي إلى تعطيل موقعك أو ظهور أخطاء يرجى التأكد جيدا بعد الظغط على زر التعديل !
                    <br /><b>ملاحظة 02 :</b> يمكنك حذف الكود بإفراغ الصندوق ثم الظغط على زر التعديل , عندما يكون الصندوق فارغ من الكتابة هذا يدل على أن الكود غير موجود .</div>

                    <div class="areaclass" ><p>كود أدسنس</p><textarea dir="ltr" name="code" placeholder="code : ..." ><?php echo $AdminJQ['CODEADSENSE'] ?></textarea></div>
                 
                    <div class="inputclass" ><p>كلمة المرور</p><input maxlength="100" type="password"  name="password" ></div>

                    <div id="conditions" >
                        <div class="chekclass" >                        
                            <input type="checkbox" required>
                            <p>أنا متأكد من أن كود أدسنس مكتوب بشكل الصحيحة</p>
                        </div>
                    </div><!--- conditions --->

                    <div class="submitclass" >
                        <input type="submit" name="update_Adsense" value="تعديــل" >
                        <a href="https://www.youtube.com/watch?v=74KD_JviOFE" target="_blank" >كيف أحصل على الكود ؟</a>
                    </div>                
                </form><!--- forminput --->
                
            <?php }// End edit Login ---
           
            if(isset($_GET['edit']) AND $_GET['edit'] === "site" AND $Users->ROLES === 'ADMIN' ){ ?>

                <form class="forminput" name="compForm" method="POST" enctype="multipart/form-data"  onsubmit="if(validateMode()){this.conditions.value=oDoc.innerHTML;return true;}return false;" >
            
                    <div class="titleC" ><p >تعديل معلومات الموقع</p></div><!--- title --->

                    <div class="showimg" ><img id="thumbnil" src="" alt="" ></div>
                            
                    <div class="inputclass"  >
                        <p>إختر لوغو للموقع</p>
                        <input id="upfile" onchange="showMyImage(this)" type="file" name="photo" accept="image/*" >
                       <div id="Btn" onclick="getFile()" >رفع اللوغو بصيغة JPEG أو PNG</div>
                    </div>

                    <div class="inputclass" ><p>إسم الموقع</p><input maxlength="100" type="text" value="<?php echo $AdminJQ['SITENAME'] ?>" name="sitename" required></div>
                    <div class="inputclass" ><p>عدد الفيديوهات لكل صفحة</p><input  type="number" min="5" value="<?php echo $AdminJQ['NUMBERPOSTS'] ?>" name="numberposts"  required></div>
                    <div class="inputclass" ><p>عدد الأقسام لكل صفحة</p><input type="number" min="2" value="<?php echo $AdminJQ['NUMBERCLASS'] ?>"  name="numberclass"  required></div>
                    <div class="inputclass" ><p>الحد الأقصى لحجم الملفات و الصور (mo)</p><input type="number" min="2" value="<?php echo $AdminJQ['FILESIZE'] ?>"  name="filesize"  required></div>
            
                    <div class="titleC" ><p >تعديل وصف الموقع</p></div><!--- title --->

                    <div class="areaclass" ><p>وصف الموقع</p><textarea name="description" placeholder="إختر موضوع تصف به الموقع يضهر على الصفحة الرئيسية..." ><?php echo $AdminJQ['DESCRIPTION'] ?></textarea></div>

                    <div id="compForm"  >
                        <p>صفحة سياسة الموقع</p>
                        <input type="hidden" name="conditions">                    								
                        <?php require_once 'richeditor.php'; ?>
                        <div id="textBox" contenteditable="true" ><?php echo $AdminJQ['CONDITIONS'] ?></div>
                        <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
                    </div>             

                    <div id="conditions" >
                        <div class="chekclass" >                        
                            <input type="checkbox" required>
                            <p>أنا متأكد من إدخال المعلومات الصحيحة</p>
                        </div>
                    </div><!--- conditions --->

                    <div class="submitclass" >
                        <input type="submit" name="update_admin" value="تعديل المعلومات" >
                        <a href="../index.php" >الرئيسية</a>
                    </div>
            
                </form><!--- forminput --->

            <?php }// End edit site ---

        }// end foreach $AdminJQ --- ?>


                

    </div><!--- content --->

    <?php require_once 'footer.php'; ?>

<?php

}else{
    header("location:index.php",true);
    
}
}catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>