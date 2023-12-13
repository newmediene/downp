<div id="header" >

<div class="logo" ><a href="index.php" ><img alt="<?php echo $Admin->SITENAME ?>" title="<?php echo $Admin->SITENAME ?>" src="../<?php echo $Admin->PICTURELOGO ?>" /></a></div><!--- logo --->

<nav class="navbar" >
    
    <form class="search" method="GET" action="../search.php" >
        <input type="text" name="search" placeholder="إبحـث ..." >
        <input type="hidden" name="submitH" value="Ok" >
    </form>

    <div class="buttons" >

        <ul><li class="active" id="menu" onclick="openNav()" ><a href="javascript:void(0);">&#9776; <?php echo $Admin->SITENAME ?></a></li></ul>

        <ul id="dropMenu" >  

            <li class="active" ><a href="../index.php">الرئيسية</a></li>

            <li><a href="stat.php" >إحصائيات</a></li>
            
            <li id="dropDown" ><a href="javascript:void(0);" >إضافة <i>&#8595;</i></a>
                <div id="dropButton" >
                    <a href="post.php" ><img src="../img/post.png" >إضافة موضوع</a>
                    <?php if($Users->ROLES === "ADMIN"){ ?>
                        <a href="class.php" ><img src="../img/caty.png" >إضافة قسم</a>
                        <a href="ads.php" ><img src="../img/ads.png" >إضافة إعلان</a>
                        <a href="signup.php" ><img src="../img/signup.png" >إضافة مساعد</a>
                    <?php } ?>
                </div>
            </li>

            <li id="dropDown" ><a href="javascript:void(0);" >تعديل <i>&#8595;</i></a>
                <div id="dropButton" >
                    <a href="user.php?edit=login" ><img src="../img/login.png" >معلومات الدخول</a>
                    <a href="user.php?edit=info_user" ><img src="../img/user.png" >معلومات الشخصية</a>
                    <?php if($Users->ROLES === "ADMIN"){ ?>
                        <a href="admin.php?edit=site" ><img src="../img/edit.png" >معلومات الموقع</a>
                        <a href="admin.php?edit=adsense" ><img src="../img/adsense.png" >كود إعلانات أدسنس</a>
                    <?php } ?>
                </div>
            </li>

            <?php 
            if($Users->ROLES === "ADMIN"){
                $ShowMsgU = $database->prepare("SELECT * FROM downp_msg WHERE USERID = :USERID");
                $ShowMsgU->bindParam("USERID",$Users->ID); $ShowMsgU->execute(); ?>                
                <li><a href="msg.php" >الرسالئل الواردة [<?php echo $ShowMsgU->rowCount() ?>]</a></li>                
            <?php } ?>
            
            <li><a href="../logout.php?logout=active" >تسجيلـ الخروجـ</a></li> 
            <li id="clowsNav" ><a href="javascript:void(0);" onclick="closeNav()" ><img src="../img/top.png" ></a></li> 

        </ul>

    </div><!--- buttons --->

</nav><!--- navbar -->

</div><!--- header --->