<?php try{ require_once '../config.php'; session_start(); 

if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass'])){  
    
    require_once 'header.php'; ?>

    <div id="content" >

        <?php require_once 'navbar.php'; 
            
            if(file_exists("../install.php")===true){ echo "<div class='right' >لقد تم حذف ملف تثبيت الموقع [install.php] بنجاح يرجى التأكد بنفسك للضمان فقط !</div>"; unlink("../install.php"); } ?>

                <div class="statistics" >

                    <?php
                    $dateStat = date("d-m-Y");

                    $toDoStat = $database->prepare("SELECT SUM(VIEW) FROM downp_stat ");
                    $toDoStat->execute();
                    foreach($toDoStat as $rowStat) { $totalView = $rowStat['SUM(VIEW)']; }

                    $toDoStatDay = $database->prepare("SELECT * FROM downp_stat WHERE DATE = :DATE");
                    $toDoStatDay->bindparam("DATE",$dateStat); $toDoStatDay->execute();    
                    $StatDay = $toDoStatDay->fetchObject();

                    $AllPosts = $database->prepare("SELECT * FROM downp_post "); $AllPosts->execute(); 
                    
                    $PostAlert = $database->prepare("SELECT * FROM downp_post ORDER BY ALERT DESC LIMIT 0,1");
                    $PostAlert->execute(); $ShowPostAlert = $PostAlert->fetchObject();

                    $Posts = $database->prepare("SELECT * FROM downp_post ORDER BY VIEW DESC LIMIT 0,1");
                    $Posts->execute(); $ShowPost = $Posts->fetchObject();
                    
                    $Class = $database->prepare("SELECT * FROM downp_class ORDER BY VIEW DESC LIMIT 0,1");
                    $Class->execute(); $ShowClass = $Class->fetchObject(); ?>

                    <span class="stat">
                        <img src="../img/day.png" />
                        <p>ظهور الموقع اليوم<br /><?php if($toDoStatDay->rowCount() != 0 ){echo $StatDay->VIEW;}else{echo "0";} ?></p>
                    </span>

                    <span class="stat">
                        <img src="../img/date.png" />
                        <p>ظهور الموقع مدة شهر<br /><?php echo $totalView ?></p>
                    </span>
                    <?php if($AllPosts->rowCount() > 0 ){ ?>
                    <span class="stat">
                        <img src="../img/post.png"  />
                        <p>عدد المواضيع<br /><?php echo $AllPosts->rowCount() ?></p>
                    </span>

                    <span class="stat">
                        <img src="https://i.ytimg.com/vi/<?php echo $ShowPostAlert->URLVIDEO ?>/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw" />
                        <a href="../show.php?post=<?php echo $ShowPostAlert->ID ?>" >الموضوع الأكثر إنذارا<br /><?php echo $ShowPostAlert->ALERT ?></a>
                    </span>

                    <span class="stat">
                        <img src="https://i.ytimg.com/vi/<?php echo $ShowPost->URLVIDEO ?>/hqdefault.jpg?sqp=-oaymwEbCKgBEF5IVfKriqkDDggBFQAAiEIYAXABwAEG&rs=AOn4CLC3R5kiRBS9OAjnrs58nd_LlfaPPw" />
                        <a href="../show.php?post=<?php echo $ShowPost->ID ?>" >الموضوع الأكثر مشاهدة<br /><?php echo $ShowPost->VIEW ?></a>
                    </span>
                    <?php } ?>
                    
                    <span class="stat">
                        <img src="<?php echo "../".$ShowClass->PICTURE ?>" />
                        <a href="../search.php?class=<?php echo $ShowClass->ID ?>" >القسم الأكثر مشاهدة<br /><?php echo $ShowClass->VIEW ?></a>
                    </span>
                    
                </div><!--- statistics --->
                
            <iframe class="infosite" src="http://www.newmediene.com/info.php" frameborder="0" ></iframe>             

    </div><!--- content --->

    <?php require_once 'footer.php'; ?>

<?php

}else{
    header("location:index.php",true);
    
}
}catch (PDOException $error_msql) {header("location:../index.php",true);} $database = null ?>