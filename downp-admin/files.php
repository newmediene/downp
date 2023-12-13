<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND isset($_GET['role']) AND isset($_GET['upId']) AND intval($_GET['upId']) AND is_numeric($_GET['upId'])){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php';

        // Delete class ---
        if(isset($_POST['delete']) AND isset($_POST['check'])){

            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){

                foreach($_POST['check'] AS $check){

                    $ShowPro = $database->prepare("SELECT * FROM downp_files WHERE ID = :ID");
                    $ShowPro->bindparam("ID",$check); $ShowPro->execute();

                    foreach($ShowPro AS $File){ 

                        if(file_exists("../".$File['FILENAME'])===true){ unlink("../".$File['FILENAME']); }
                                                         
                    }

                    $remov = $database->prepare("DELETE FROM downp_files WHERE ID = :ID ");
                    $remov->bindparam("ID",$check); $remov->execute();

                }

                if(isset($remov)){
                    echo "<div class='right' >تم الحذف بنجاح !</div>";
                }
                

            }else{
                echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
            }
                        
        }// END Delete class ---
        
        $whitelist_typeImg = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

        $whitelist_typeApp = array('application/x-zip-compressed', 'application/octet-stream' );

        $whitelist_type    = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/x-zip-compressed', 'application/octet-stream' );

        if(isset($_GET['edit']) AND intval($_GET['edit'])){
                
            if(isset($_POST['edit'])){
                    
                $ShowFiles = $database->prepare("SELECT * FROM downp_files WHERE ID = :ID ");
                
                $ShowFiles->bindparam("ID",$_GET['edit']);
                $ShowFiles->execute(); 

                $files = $ShowFiles->fetchObject();
                
                $title     = strip_tags(trim($_POST['title']));

                $photoName = explode(".", $_FILES["fileUp"]["name"]);
                $photoType = $_FILES['fileUp']['type'];
                $photoSize = $_FILES['fileUp']['size']/1024/1024;
                $photoData = $_FILES['fileUp']['tmp_name'];

                $max_size = $Admin->FILESIZE;//MO

                if (in_array($photoType,$whitelist_typeImg)) {
                    $folderUp = "img";

                }else if (in_array($photoType,$whitelist_typeApp)) {
                    $folderUp = "down";

                }else{ $folderUp = "img"; }

                $fileName = "files/".$folderUp."/ID".$Users->ID."-".round(microtime(true)).".".end($photoName);


                if(in_array($photoType, $whitelist_type) OR empty($_FILES['fileUp'])){ 

                    if($Users->ROLES === "ADMIN"){
                        $updateClassData = $database->prepare("UPDATE downp_files SET TITLE = :TITLE, USERID = '1' ,FILENAME = :FILENAME, TYPE = :TYPE WHERE ID = :ID ");							

                    }else if($Users->ROLES === "USER"){
                        $updateClassData = $database->prepare("UPDATE downp_files SET TITLE = :TITLE, USERID = '".$Users->ID."' ,FILENAME = :FILENAME, TYPE = :TYPE WHERE ID = :ID ");							

                    }
                    
                    $updateClassData->bindparam("TITLE",$title);
                    $updateClassData->bindparam("FILENAME",$fileName);
                    $updateClassData->bindparam("TYPE",$photoType);
                    $updateClassData->bindparam("ID",$_GET['edit']);

                    if(file_exists("../".$files->FILENAME)===true){

                        if($photoSize > $max_size) {
                            echo "<div class='error' >حجم الصورة كبيرة لا يجب أن تتعدى ".$max_size." ميغا !</div>";
                            
                        }else if($updateClassData->execute()){  
                            unlink("../".$files->FILENAME);
                            move_uploaded_file($photoData,"../".$fileName);										
                            echo "<div class='right' >تم تعديـل الصورة و العنوان</div>";
                    
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }else if(!file_exists("../".$files->FILENAME)===true){

                        if($updateClassData->execute()){	
                            move_uploaded_file($photoData,"../".$fileName);								
                            echo "<div class='right' >تم تعديـل الصورة بعد رفعها و العنوان</div>";
                    
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }

                }else if(!empty($_FILES['fileUp'])){ 

                    if($Users->ROLES === "ADMIN"){
                        $updateClassTitData = $database->prepare("UPDATE downp_files SET TITLE = :TITLE, USERID = '1' WHERE ID = :ID ");							

                    }elseif($Users->ROLES === "USER"){
                        $updateClassTitData = $database->prepare("UPDATE downp_files SET TITLE = :TITLE, USERID = '".$Users->ID."' WHERE ID = :ID ");
                    }

                    $updateClassTitData->bindparam("TITLE",$title);
                    $updateClassTitData->bindparam("ID",$_GET['edit']);

                    if($updateClassTitData->execute()){
                        echo "<div class='right' >تم تعديـل العنوان فقط</div>";
                
                    }else{
                        echo "<div class='error' >فشل تعديل البيانات</div>";
                    }

                }

            }/// END edit 

            $Show = $database->prepare("SELECT * FROM downp_files WHERE ID = :ID ");
            $Show->bindparam("ID",$_GET['edit']);
            $Show->execute();

            foreach($Show AS $JQ){  ?>
            <form class="forminput" method="POST" enctype="multipart/form-data" >                    
                <div class="titleC" ><p><?php echo $JQ['FILENAME'] ?></p></div><!--- title --->

                <div class="image" style="margin-top: 10px;" ><a href="search.php?class=<?php echo $JQ['ID'] ?>" ><img src="../<?php if(in_array($JQ['TYPE'],$whitelist_typeImg)){ echo $JQ['FILENAME']; }else{ echo "img/bgrUp.png"; } ?>" /></a></div><!--- image --->
                    
                            
                <div class="inputclass"  >
                    <p>إختر صورة</p>
                    <input type="file" name="fileUp" >
                </div>

                <div class="inputclass" ><p>الإسم</p><input type="text" value="<?php echo $JQ['TITLE'] ?>" name="title" required></div>

                <div class="submitclass" >
                    <input type="submit" name="edit" value="تعديل" >
                    <a href="files.php?role=POST&upId=<?php echo $_GET['upId'] ?>" >إرفاق ملف أو صورة</a>
                </div>
            </form><!--- forminput --->
            <?php } ?>

    <?php }else{

        // Add Class ---
        if(isset($_POST['add'])){
                        
            $photoName = explode(".", $_FILES["fileUp"]["name"]);
            $photoType = $_FILES['fileUp']['type'];
            $photoSize = $_FILES['fileUp']['size']/1024/1024;
            $photoData = $_FILES['fileUp']['tmp_name'];
                        
            $title = strip_tags(trim($_POST['title']));

            $max_size = $Admin->FILESIZE;//MO           

            if (in_array($photoType,$whitelist_typeImg)) {
                $folderUp = "img";

            }else if (in_array($photoType,$whitelist_typeApp)) {
                $folderUp = "down";

            }else{ $folderUp = "img"; }

            $fileName = "files/".$folderUp."/ID".$Users->ID."-".round(microtime(true)).".".end($photoName);


            if(isset($_GET["role"]) AND $_GET["role"] == "POST" ){

                $role = "POST";

            }else if(isset($_GET["role"]) AND $_GET["role"] == "USER" ){

                $role = "USER";

            }else { $role = ""; }
    
            $addData = $database->prepare("INSERT INTO downp_files (TITLE,FILENAME,USERID,POSTID,TYPE,ROLES) VALUES (:TITLE,:FILENAME,:USERID,:POSTID,:TYPE,:ROLES)");

            $addData->bindparam("TITLE",$title);
            $addData->bindparam("FILENAME",$fileName);
            $addData->bindparam("USERID",$Users->ID);
            $addData->bindparam("POSTID",$_GET['upId']);
            $addData->bindparam("TYPE",$photoType);
            $addData->bindparam("ROLES",$role);
                        
            if(!empty($title) AND !empty($_FILES['fileUp'])){
                                                        
                if (!in_array($photoType,$whitelist_typeImg) AND !in_array($photoType,$whitelist_typeApp)) {
                    echo "<div class='error' >نوع الملف غير صالح يسمح بالصور ذات صيغة JPEG !</div>";

                }else if($photoSize > $max_size) {
                    echo "<div class='error' >حجم الصورة كبيرة لا يجب أن تتعدى ".$max_size." ميغا !</div>";
                            
                }else if($addData->execute()){
                    move_uploaded_file($photoData,"../".$fileName);
                    echo "<div class='right' >تم إضافة الصورة !</div>";

                }
            }else { 
                echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
            }       
                                            
        }// END add  ?>

        <!-- form Add class --->
        <form class="forminput" method="POST"  enctype="multipart/form-data" >

            <div class="titleC" ><p>إرفاق ملف أو صورة خاصة بـ : </p></div><!--- title --->
                            
            <div class="inputclass"  >
                <p>إختر ملفـ أو صورة</p>
                <input type="file" name="fileUp" >
            </div>
            
            <div class="inputclass" ><p>الإسم</p><input type="text" name="title" required></div>

            <div class="submitclass" >
                <input type="submit" name="add" value="إضافـة" >
                <a href="post.php" >إضافة موضوع جديد</a>

            </div>

        </form><!--- end form --->

    <?php }//else add class ?>

        <!-- form show edit delete class --->
        <form  method="POST" >

            <div class="submitclass" >
                <input type="submit" style="background-color: red;" name="delete" value="حذف" >
                <input type="password" name="password" placeholder="كلمة المرور"  >
            </div>
                
            <table class="table" >
                <tr>
                    <th>-</th>
                    <th>العنوان</th>
                    <th>النوغ</th>
                    <th>العضو</th>
                    <th>تعديل</th>
                    <th>حـذف</th>
                </tr>

                    <?php
                    if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }                    
                    if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }
                    
                    $Row = $database->prepare("SELECT * FROM downp_files WHERE POSTID = '".$_GET['upId']."' AND ROLES = '".$_GET['role']."' ");
                    $Row->execute(); $all = $Row->rowCount();

                    $start = $per_page * $page - $per_page; $pages = ceil($all / $per_page);
                    
                    $Show = $database->prepare("SELECT * FROM downp_files WHERE POSTID = '".$_GET['upId']."' AND ROLES = '".$_GET['role']."' ORDER BY ID DESC LIMIT $start,$per_page ");
                    $Show->execute();  ?>

                    <div class="titleC" style="margin-top: 15px;" ><p>إحصائيات [<?php echo $Show->rowCount() ?>]</p></div><!--- title --->

                    <?php foreach($Show AS $JQ){ ?>
                    <tr>
                        <th style="width: 100px;" ><img src="../<?php if(in_array($JQ['TYPE'],$whitelist_typeImg)){ echo $JQ['FILENAME']; }else{ echo "img/bgrUp.png"; } ?>"/></th>
                        <th><?php echo substr($JQ['TITLE'],0 ,150) ?> ...</th>
                        <th><?php echo $JQ['TYPE'] ?></th>
                        
                        <?php 
                        $ShowUn = $database->prepare("SELECT * FROM downp_users WHERE ID = :ID ");
                        $ShowUn->bindparam("ID",$JQ['USERID']); $ShowUn->execute(); $UserName = $ShowUn->fetchObject();
                        echo"<th><a href='../profile.php?id=".$UserName->ID."' >".$UserName->NAME."</a></th>";  ?>
                        
                        <th><a href="<?php echo "?role=".$_GET["role"]."&upId=".$_GET["upId"]."&edit=".$JQ["ID"] ?>" >عـدل</a></th>                                                       
                        <th>
                            <input name="check[]" value="<?php echo $JQ["ID"] ?>" type="checkbox" />
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