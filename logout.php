<?php
    
    session_start();

    if(isset($_GET['logout']) AND $_GET['logout'] === "active" ){  session_unset();  session_destroy(); 
        echo "<div style='margin:30px; direction:rtl; font-weight: bold;' >يتم تسجيل الخروج بنجــاح ...</div>";
        header("location:index.php",true);
    }else{
        header("location:index.php",true);
    } ?>
