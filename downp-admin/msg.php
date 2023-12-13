<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass']) AND $_SESSION['user']->ROLES === "ADMIN"){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php'; 


        if(isset($_POST['delete']) AND isset($_POST['check']) ){  
                    
            $password   = strip_tags(trim(sha1($_POST['password'])));

            if(!empty($_POST['check']) AND isset($_POST['check']) AND $password == $Users->PASSWORD){

                foreach($_POST['check'] AS $check){

                    if($Users->ROLES === "ADMIN"){
                        $remov = $database->prepare("DELETE FROM downp_msg WHERE ID = :ID ");
                        $remov->bindParam("ID",$check); $remov->execute();
                    }elseif($Users->ROLES === "USER"){
                        $remov = $database->prepare("DELETE FROM downp_msg WHERE ID = :ID AND USERID = :USERID");
                        $remov->bindParam("ID",$check);
                        $remov->bindParam("USERID",$Users->ID); $remov->execute();
                    }
                }

                if(isset($remov)){ echo "<div class='right' >تم الحذف بنجاح !</div>"; }

            }else{
                echo "<div class='error' >إختر ما يجب حذفه و أدخل كلمة المرور الصحيحة</div>";
            }
                    
        }// END delete 
        
        if(isset($_GET['msg']) AND intval($_GET['msg'])){

            if($Users->ROLES === "ADMIN"){
                $ShowRus = $database->prepare("SELECT * FROM downp_msg WHERE ID = :ID ");
                $ShowRus->bindParam("ID",$_GET['msg']);
                $ShowRus->execute();
            }elseif($Users->ROLES === "USER"){
                $ShowRus = $database->prepare("SELECT * FROM downp_msg WHERE ID = :ID AND USERID = :USERID");
                $ShowRus->bindParam("ID",$_GET['msg']); $ShowRus->bindParam("USERID",$Users->ID);
                $ShowRus->execute();
            }

            if($ShowRus->rowCount() > 0){ $message = $ShowRus->fetchObject();  ?>

                <div class="list" >

                    <p><img src="../img/user.png" /><?php echo $message->NAME ?></p>
                    <p><img src="../img/date.png" /><?php echo $message->DATE ?></p>
                    <a href="mailto:<?php echo $message->EMAIL ?>" dir="ltr" ><img src="../img/email.png"  ><?php echo $message->EMAIL ?></a>

                </div><!--- list --->

                <div class="topic" ><?php echo $message->TEXT ?></div><!--- topic --->

            <?php }//Row

        }// end show msg ?>

        <form class="messenger" method="POST" >

            <div class="submitclass" >
                <input type="submit" style="background-color: #c72b2b;" name="delete" value="حذف الرسائل" >
                <input type="password" name="password" placeholder="كلمة المرور"  >
            </div>

            <table class="table" >

                <tr>
                    <th>العنوان</th>
                    <th>حـذف</th>
                </tr>

                <?php
                if(isset($_GET['page'])){ $page = intval($_GET['page']); }else{ $page = 1; }
                if(isset($_GET['per_page'])){ $per_page = intval($_GET['per_page']); }else{ $per_page = 50; }

                if($Users->ROLES === "USER"){
                    $Row = $database->prepare("SELECT * FROM downp_msg WHERE USERID = :USERID "); 					
                    $Row->bindParam("USERID",$Users->ID); $Row->execute();
                    $All = $Row->rowCount(); 
                }elseif($Users->ROLES === "ADMIN"){
                    $Row = $database->prepare("SELECT * FROM downp_msg "); $Row->execute();					
                    $All = $Row->rowCount(); }

                $start = $per_page * $page - $per_page;$pages = ceil($All / $per_page);

                if($Users->ROLES === "USER"){
                   $Show = $database->prepare("SELECT * FROM downp_msg WHERE USERID = :USERID ORDER BY ID DESC LIMIT $start,$per_page");
                   $Show->bindParam("USERID",$Users->ID); $Show->execute();
                }elseif($Users->ROLES === "ADMIN"){
                   $Show = $database->prepare("SELECT * FROM downp_msg ORDER BY ID DESC LIMIT $start,$per_page");
                   $Show->execute();  } ?>
                    
                <div class="titleC" style="margin-top: 15px;" ><p>الرسائل الواردة [<?php echo $Show->rowCount() ?>]</p></div><!--- title --->
                
                <?php foreach($Show AS $JQ){  ?>
                </tr>
                    <th><a href="?msg=<?php echo $JQ["ID"] ?>" ><?php echo substr(strip_tags($JQ['TEXT']),0 ,150)." ..."; ?></a></th>

                    <th><?php echo $JQ['DATE'] ?> <input name="check[]" value="<?php echo $JQ["ID"] ?>" type="checkbox" /></th>
                </tr>
                <?php } ?>

            </table>

        </form><!--- messenger --->

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