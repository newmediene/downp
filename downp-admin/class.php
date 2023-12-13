<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['user']->ROLES === "ADMIN"){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php';

        // Delete class ---
        if(isset($_POST['delete']) AND isset($_POST['check'])){

            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){

                foreach($_POST['check'] AS $check){

                    $ShowPro = $database->prepare("SELECT * FROM downp_class WHERE ID = :ID");
                    $ShowPro->bindParam("ID",$check); $ShowPro->execute();

                    foreach($ShowPro AS $Pics){ 

                        if(file_exists("../".$Pics['PICTURE'])===true){ unlink("../".$Pics['PICTURE']); }
                                    
                        if($Pics['ROLES'] == "POST"){
                            $updatePostCid = $database->prepare("UPDATE downp_post SET CLASSID = '1' WHERE CLASSID = :CLASSID");
                            $updatePostCid->bindparam("CLASSID",$check); $updatePostCid->execute();

                        }
                        if($Pics['ROLES'] == "JOBS"){
                            $updateJobsCid = $database->prepare("UPDATE downp_users SET JOBS = '1' WHERE JOBS = :JOBS");
                            $updateJobsCid->bindparam("JOBS",$check); $updateJobsCid->execute();

                        }
                        if($Pics['ROLES'] == "CITY"){
                            $updateCityCid = $database->prepare("UPDATE downp_users SET CITY = '1' WHERE CITY = :CITY");
                            $updateCityCid->bindparam("CITY",$check); $updateCityCid->execute();
                        }                                    
                    }
                    
                    $remov = $database->prepare("DELETE FROM downp_class WHERE ID = :ID AND ROLES != 'BASIC' ");
                    $remov->bindparam("ID",$check); $remov->execute();
                }
                if(isset($remov) OR isset($updatePostCid) OR isset($updateJobsCid) OR isset($updateCityCid)){
                    echo "<div class='right' >تم الحذف بنجاح !</div>";
                }

            }else{
                echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
            }
                        
        }// END Delete class ---


        if(isset($_GET['edit']) AND intval($_GET['edit']) AND $Users->ROLES === 'ADMIN' ){
                
            if(isset($_POST['edit'])){
                    
                $ShowClass = $database->prepare("SELECT * FROM downp_class WHERE ID = :ID ");
                $ShowClass->bindparam("ID",$_GET['edit']); $ShowClass->execute(); 

                $class = $ShowClass->fetchObject();
                
                $title     = strip_tags(trim($_POST['title']));

                $photoName = $_FILES['photo']['name'];
                $photoType = $_FILES['photo']['type'];
                $photoSize = $_FILES['photo']['size']/1024/1024;
                $photoData = $_FILES['photo']['tmp_name'];

                $picture = "files/class/".time().str_replace("image/",".",$photoType);

                $max_size = $Admin->FILESIZE;//MO

                $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

                if(in_array($photoType, $whitelist_type) OR empty($_FILES['photo'])){ 

                    $updateClassData = $database->prepare("UPDATE downp_class SET TITLE = :TITLE ,PICTURE = :PICTURE WHERE ID = :ID");							

                    $updateClassData->bindparam("TITLE",$title);
                    $updateClassData->bindparam("PICTURE",$picture);
                    $updateClassData->bindparam("ID",$_GET['edit']);                     

                    if(file_exists("../".$class->PICTURE)===true){

                        if($photoSize > $max_size) {
                            echo "<div class='error' >حجم الصورة كبيرة لا يجب أن تتعدى ".$Admin->FILESIZE." ميغا !</div>";
                            
                        }else if($updateClassData->execute()){  
                            unlink("../".$class->PICTURE);
                            move_uploaded_file($photoData,"../".$picture);										
                            echo "<div class='right' >تم تعديـل الصورة و العنوان</div>";
                    
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }else if(!file_exists("../".$class->PICTURE)===true){

                        if($updateClassData->execute()){	
                            move_uploaded_file($photoData,"../".$picture);								
                            echo "<div class='right' >تم تعديـل الصورة بعد رفعها و العنوان</div>";
                    
                        }else{
                            echo "<div class='error' >فشل تعديل البيانات</div>";
                        }

                    }

                }else if(!empty($_FILES['photo'])){ 

                    $updateClassTitData = $database->prepare("UPDATE downp_class SET TITLE = :TITLE WHERE ID = :ID");							

                    $updateClassTitData->bindparam("TITLE",$title);
                    $updateClassTitData->bindparam("ID",$_GET['edit']);

                    if($updateClassTitData->execute()){
                        echo "<div class='right' >تم تعديـل العنوان فقط</div>";
                
                    }else{
                        echo "<div class='error' >فشل تعديل البيانات</div>";
                    }

                }

            }/// END edit 

            $Show = $database->prepare("SELECT * FROM downp_class WHERE ID = :ID ");
            $Show->bindparam("ID",$_GET['edit']);
            $Show->execute();

            foreach($Show AS $JQ){  ?>
            <form class="forminput" method="POST" enctype="multipart/form-data" >                    
                <div class="titleC" ><p>تعديل القسم</p></div><!--- title --->

                <div class="image" style="margin-top: 10px;" ><a href="search.php?class=<?php echo $JQ['ID'] ?>" ><img src="../<?php echo $JQ['PICTURE'] ?>" /></a></div><!--- image --->
                    
                <div class="showimg" ><img id="thumbnil" src="" alt="" ></div>
                            
                <div class="inputclass"  >
                    <p>إختر صورة خاصة بالقسم</p>
                    <input id="upfile" onchange="showMyImage(this)" type="file" name="photo" accept="image/*" >
                    <div id="Btn" onclick="getFile()" >رفع الصورة بصيغة JPEG </div>
                </div>

                <div class="inputclass" ><p>إسم القسم</p><input type="text" value="<?php echo $JQ['TITLE'] ?>" name="title" required></div>

                <div class="submitclass" >
                    <input type="submit" name="edit" value="تعديل" >
                    <a href="class.php" >إضافة قسم جديد</a>
                </div>
            </form><!--- forminput --->
            <?php } ?>

    <?php }else{

        // Add Class ---
        if(isset($_POST['add']) AND $Users->ROLES === 'ADMIN' ){
                        
            $photoName = $_FILES['photo']['name'];
            $photoType = $_FILES['photo']['type'];
            $photoSize = $_FILES['photo']['size']/1024/1024;
            $photoData = $_FILES['photo']['tmp_name'];
                        
            $title = strip_tags(trim($_POST['title']));
                        
            $picture = "files/class/".time().str_replace("image/",".",$photoType);

            $max_size = $Admin->FILESIZE;//MO

            $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

            $addData = $database->prepare("INSERT INTO downp_class (TITLE,PICTURE,ROLES) VALUES (:TITLE,:PICTURE,'POST')");

            $addData->bindparam("TITLE",$title);
            $addData->bindparam("PICTURE",$picture);			
                        
            if(!empty($title) AND !empty($_FILES['photo'])){
                                                        
                if (!in_array($photoType,$whitelist_type)) {
                    echo "<div class='error' >نوع الملف غير صالح يسمح بالصور ذات صيغة JPEG !</div>";

                }else if($photoSize > $max_size) {
                    echo "<div class='error' >حجم الصورة كبيرة لا يجب أن تتعدى ".$Admin->FILESIZE." ميغا !</div>";
                            
                }else if($addData->execute()){
                    move_uploaded_file($photoData,"../".$picture);
                    echo "<div class='right' >تم إضافة الصورة !</div>";

                }
            }else { 
                echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
            }       
                                            
        }// END add  ?>

        <!-- form Add class --->
        <form class="forminput" method="POST" enctype="multipart/form-data" >                    
            <div class="titleC" ><p>إضافة قسم جديد</p></div><!--- title --->    
            <div class="showimg" ><img id="thumbnil" src="" alt="" ></div>
                            
            <div class="inputclass"  >
                <p>إختر صورة خاصة بالقسم</p>
                <input id="upfile" onchange="showMyImage(this)" type="file" name="photo" accept="image/*"  required>
                <div id="Btn" onclick="getFile()" >رفع الصورة بصيغة JPEG </div>
            </div>
            <div class="inputclass" ><p>إسم القسم</p><input type="text" name="title" required></div>

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
                    <th>العنوان</th>
                    <th>المشاهدة</th>
                    <th>الفئة</th>
                    <th>تعديل</th>
                    <th>حـذف</th>
                </tr>

                    <?php
                    if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }                    
                    if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }
                        
                    $Row = $database->prepare("SELECT * FROM downp_class ");
                    $Row->execute(); $all = $Row->rowCount();

                    $start = $per_page * $page - $per_page; $pages = ceil($all / $per_page);
                    
                    $Show = $database->prepare("SELECT * FROM downp_class ORDER BY ID DESC LIMIT $start,$per_page");
                    $Show->execute();  ?>

                    <div class="titleC" style="margin-top: 15px;" ><p>إحصائيات الأقسـام [<?php echo $Show->rowCount() ?>]</p></div><!--- title --->

                    <?php foreach($Show AS $JQ){ ?>
                    <tr>
                        
                        <th style="width: 100px;" ><img src="../<?php echo $JQ['PICTURE'] ?>"/></th>
                        
                        <th><a href="<?php echo "../search.php?class=".$JQ['ID'] ?>" ><?php echo substr($JQ['TITLE'],0 ,150) ?> ...</a></th>                            
                        <th><?php echo $JQ['VIEW'] ?></th>
                        <th><?php echo $JQ['ROLES'] ?></th>
                        <th><a href="?edit=<?php echo $JQ["ID"] ?>" >عـدل</a></th>                                
                        <th>
                            <?php if($JQ['ID'] != "1" ){ ?>
                                <input name="check[]" value="<?php echo $JQ["ID"] ?>" type="checkbox" />
                            <?php }else{echo "أساسي";} ?>
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