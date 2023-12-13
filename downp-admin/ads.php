<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['user']->ROLES === "ADMIN"){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php'; 

        // Delete class ---
        if(isset($_POST['delete']) AND isset($_POST['check'])){

            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){

                foreach($_POST['check'] AS $all){

                    $remov = $database->prepare("DELETE FROM downp_ads WHERE ID = :ID ");
                    $remov->bindparam("ID",$all); $remov->execute();
                }
                if(isset($remov)){
                    echo "<div class='right' >تم الحذف بنجاح !</div>";
                }

            }else{
                echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
            }
                        
        }// END Delete class ---
        
        if(isset($_GET['edit']) AND intval($_GET['edit']) AND $Users->ROLES === 'ADMIN' ){

            if(isset($_POST['edit'])){

                $updateData = $database->prepare("UPDATE downp_ads SET TITLE = :TITLE ,TEXT = :TEXT ,TYPE = :TYPE ,PLACE = :PLACE WHERE ID = :ID ");
                    
                $title = strip_tags(trim($_POST['title']));
                $place = strip_tags(trim($_POST['place']));
                $type  = strip_tags(trim($_POST['type']));
                $text  =  $_POST['text'];        
    
                $updateData->bindparam("TITLE",$title);
                $updateData->bindparam("TEXT",$text);
                $updateData->bindparam("TYPE",$type);
                $updateData->bindparam("PLACE",$place);
                $updateData->bindparam("ID",$_GET['edit']);
    
                if(!empty($title) OR !empty($place) OR !empty($type) OR !empty($text)){
                                                    
                   if($updateData->execute()){
                       echo "<div class='right' >تم التعديل بنجاح !</div>";

                    }

                }else { 
                    echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
                } 
                    
            }// END edit 
               
            $Show = $database->prepare("SELECT * FROM downp_ads WHERE ID = :ID ");
            $Show->bindparam("ID",$_GET['edit']);
            $Show->execute();

            foreach($Show AS $JQ){ ?>

            <form class="forminput" name="compForm" method="POST" onsubmit="if(validateMode()){this.text.value=oDoc.innerHTML;return true;}return false;" >                    
                <div class="titleC" ><p>تعديل إعلان</p></div><!--- title --->

                <div class="list" >
                        <p><img src="../img/city.png" /><?php if($JQ['PLACE'] == "BOTPOSTS"){echo"الإعلان موجود تحت المواضيع";}elseif($JQ['PLACE'] == "TOPPOSTS"){echo"الإعلان موجود فوق المواضيع";} ?></p>
                        <p><img src="../img/menu.png" /><?php if($JQ['TYPE'] == "50"){echo"حجم الإعلان 50%";}elseif($JQ['TYPE'] == "100"){echo"حجم الإعلان 100%";} ?></p>
                    </div>
                    
                    <div class="inputclass" ><p>إسم الإعلان</p><input type="text" name="title" value="<?php echo $JQ['TITLE'] ?>" required></div>

                    <div class="inputclass" >
                        <p>مكان الإعلان</p>		     
                        <select name="place" required>
                            <option value="" selected>إختر مكان للإعلان</option>
                            <option value="TOPPOSTS" >فوق المواضيع</option>
                            <option value="BOTPOSTS" >أسفل المواضيع</option>
                        </select>
                    </div>

                    <div id="compForm"  >
                        <p>النص</p>                    
                        <input type="hidden" name="text" >                    								
                        <?php require_once 'richeditor.php'; ?>
                        <div id="textBox" contenteditable="true" ><?php echo $JQ['TEXT'] ?></div>
                        <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
                    </div>

                    <div class="inputclass" >
                        <p>حجم الإعلان</p>		     
                        <select name="type" required>
                            <option value="" selected>إختر حجم الإعلان</option>                                
                            <option value="50" >العرض 50%</option>
                            <option value="100" >العرض 100%</option>
                        </select>
                    </div>

                    <div class="submitclass" >
                        <input type="submit" name="edit" value="تعديل" >
                    </div>
                </form><!--- forminput --->
            <?php }// end foreach

        }else{
        // end Get edit

        // Add Ads
        if(isset($_POST['add']) AND $Users->ROLES === 'ADMIN' ){
                    
            $title = strip_tags(trim($_POST['title']));
            $place = strip_tags(trim($_POST['place']));
            $type  = strip_tags(trim($_POST['type']));
            $text  =  $_POST['text'];
    
            $addData = $database->prepare("INSERT INTO downp_ads (TITLE,TEXT,TYPE,PLACE) VALUES (:TITLE,:TEXT,:TYPE,:PLACE)");
    
            $addData->bindparam("TITLE",$title);
            $addData->bindparam("PLACE",$place);
            $addData->bindparam("TYPE",$type);
            $addData->bindparam("TEXT",$text);			
                        
            if(!empty($title) OR !empty($place) OR !empty($type) OR !empty($text)){
                                                        
                if($addData->execute()){
                    echo "<div class='right' >تم إضافة الصورة !</div>";    
                }
    
            }else { 
                echo "<div class='error' >يوجد خانة فارغة تحقق من ذلك !</div>";
            }       
                                            
        }// END add ---  ?>

        <!--- form Add Ads --->
        <form class="forminput" name="compForm" method="POST" onsubmit="if(validateMode()){this.text.value=oDoc.innerHTML;return true;}return false;" >                    
            <div class="titleC" ><p>إضافة إعلان جديد</p></div><!--- title --->
                    
            <div class="inputclass" ><p>إسم الإعلان</p><input type="text" name="title" required></div>

            <div class="inputclass" >
                <p>مكان الإعلان</p>		     
                <select name="place" required>
                    <option value="" selected>إختر مكان للإعلان</option>
                    <option value="TOPPOSTS" >فوق المواضيع</option>
                    <option value="BOTPOSTS" >أسفل المواضيع</option>
                </select>
            </div>

            <div id="compForm"  >
                <p>النص</p>                    
                <input type="hidden" name="text" >                    								
                <?php require_once 'richeditor.php'; ?>
                <div id="textBox" contenteditable="true" ></div>
                <input type="hidden" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" />
             </div>

            <div class="inputclass" >
                <p>حجم الإعلان</p>		     
                <select name="type" required>
                    <option value="" selected>إختر حجم الإعلان</option>                                
                    <option value="50" >العرض 50%</option>
                    <option value="100" >العرض 100%</option>
                </select>
             </div>

            <div class="submitclass" >
                <input type="submit" name="add" value="إضافـة" >
            </div>

        </form><!--- end form --->

        <?php }//End else Edit Ads  ?>
        

        <form  method="POST" >

            <div class="submitclass" >
                <input type="submit" style="background-color: red;" name="delete" value="حذف الإعلان" >
                <input type="password" name="password" placeholder="كلمة المرور"  >
            </div>
                
            <table class="table" >
                <tr>
                    <th>العنوان</th>
                    <th>تعديل</th>
                    <th>حـذف</th>
                </tr>

                <?php
                if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }
                if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }
                        
                $Row = $database->prepare("SELECT * FROM downp_ads ");
                $Row->execute(); $all = $Row->rowCount();

                $start = $per_page * $page - $per_page; $pages = ceil($all / $per_page);
                    
                $Show = $database->prepare("SELECT * FROM downp_ads ORDER BY ID DESC LIMIT $start,$per_page");
                $Show->execute();  ?>

                <div class="titleC" style="margin-top: 15px;" ><p>إحصائيات الإعلانات [<?php echo $Show->rowCount() ?>]</p></div><!--- title --->

                <?php foreach($Show AS $JQ){ ?>
                <tr>
                    <th><?php echo substr($JQ['TITLE'],0 ,150) ?> ...</th>                            
                    <th><a href="?edit=<?php echo $JQ["ID"] ?>" >عـدل</a></th>                                
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