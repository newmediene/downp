<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" >

        <link rel="stylesheet" href="style/home.css">
        <link rel="icon" href="files/logo/icon.png" >
        <link rel="shortcut" href="files/logo/icon.png" >
        <link rel="apple-touch-icon" href="files/logo/icon.png" >
		
		<title>تثبيت سكريبت DZmyc</title>
        
    </head>

    <body>

        <div id="content" >

            <div id="header" >                
                
                <div class="topnav" >
                    <div class="leng" ><a target="_blank" href="http://www.dzworkers.com" ><img src="files/logo/icon.png" /></a></div>
                    <div class="logo" ><a href="index.php" ><img alt="DZmyc" title="DZmyc" src="files/logo/logo.jpg" /></a></div>
                </div><!--- topnav --->
                
                <div class="navbar" >
                    
                    <div class="buttons" >

                        <ul class="dropMenu" >                           

                            <li class="active" ><a href="index.php">الرئيسية</a></li>

                            <li><a href="http://www.dzworkers.com" >مجمع عمال الجزائر</a></li>

                        </ul>

                    </div>

                </div><!--- navbar -->
            
            </div><!--- header --->

            <?php try{ require_once 'config.php'; 
            
            echo "<div class='right' >أنت جاهز الآن يمكنك بدأ تعبأت بيانات الموقع و تثبيته.</div>"; 
            
            if(isset($_POST['register'])){                    

                $username    = strip_tags(trim(strtoupper($_POST['username']))); $correcteUn = str_replace(" ","",$username);
                $nepassword  = strip_tags(trim(sha1($_POST['nePassword'])));
                $repassword  = strip_tags(trim(sha1($_POST['rePassword'])));
                $email       = strip_tags(trim($_POST['email']));
                $sitename    = strip_tags(trim($_POST['sitename']));
                $name        = strip_tags(trim($_POST['name']));
                $description = strip_tags(trim($_POST['description']));
                $DateUp = date("d-m-Y");
                
                
                if(!empty($username) OR !empty($nepassword) OR !empty($email)  OR !empty($sitename) OR !empty($name) OR !empty($description)){

                    if($nepassword != $repassword){
                        echo "<div class='error' >كلمة السـر غير متشابهة في الخانتين</div>";

                    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        echo "<div class='error' >الإمـيل غير صحيح</div>";
                
                    }else{
                        
                        // Creat Table downp_admin
                        $Creat_admin = $database->exec("CREATE TABLE downp_admin (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            SITENAME     VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            NUMBERPOSTS  INT      NOT NULL,
                            NUMBERCLASS  INT      NOT NULL,
                            FILESIZE     INT      NOT NULL,
                            PICTURELOGO  VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            DESCRIPTION  TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            CONDITIONS   TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            CODEADSENSE  TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL )");

                        // Creat Table downp_ads
                        $Creat_ads = $database->exec("CREATE TABLE downp_ads (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            TITLE VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            TEXT  TEXT  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            TYPE  INT   NOT NULL,                            
                            PLACE VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL )");

                        // Creat Table downp_class
                        $Creat_class = $database->exec("CREATE TABLE downp_class (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            TITLE    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            PICTURE  VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            VIEW     INT NOT NULL,
                            ROLES    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL )");

                        // Creat Table downp_files
                        $Creat_files = $database->exec("CREATE TABLE downp_files (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            TITLE    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            FILENAME VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            USERID   BOOLEAN NOT NULL,
                            POSTID   BOOLEAN NOT NULL,
                            TYPE     VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ROLES    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL )");


                        // Creat Table downp_msg
                        $Creat_msg = $database->exec("CREATE TABLE downp_msg (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            NAME    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            EMAIL   VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            TEXT    TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            DATE    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            USERID  INT NOT NULL )");

                        // Creat Table downp_post
                        $Creat_post = $database->exec("CREATE TABLE downp_post (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            TITLE      VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            URLVIDEO   VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ACTIMGVID  BOOLEAN NOT NULL,
                            PICTURE    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            TOPIC      TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            CLASSID    INT NOT NULL,
                            VIEW       INT NOT NULL,
                            DATE       VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ALERT      INT NOT NULL,
                            ACTIVE     BOOLEAN NOT NULL, 
                            ACCEPTABLE BOOLEAN NOT NULL,
                            USERID     INT NOT NULL )");

                        // Creat Table downp_stat
                        $Creat_stat = $database->exec("CREATE TABLE downp_stat (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            VIEW INT NOT NULL NOT NULL,
                            DATE VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            TIME VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL )");

                        // Creat Table downp_users
                        $Creat_users = $database->exec("CREATE TABLE downp_users (                    
                            ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            PASSWORD   TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            USERNAME   VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ALERT      INT NOT NULL,
                            TEXT       TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            PROFILE    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            AGE        VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            EMAIL      VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            FCB        VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            PHONE      VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            NAME       VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            SEX        VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            WORKS      VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ADDRESS    VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            VIEW       INT NOT NULL,
                            ACTIVATED  BOOLEAN NOT NULL,
                            DATEUP     VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            ROLES      VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL  )");


                
                        // Add Post
                        $Add_post = $database->prepare("INSERT INTO downp_post (TITLE,URLVIDEO,ACTIMGVID,TOPIC,CLASSID,DATE,ACTIVE,ACCEPTABLE,USERID) VALUES 
                        ('طريقة التسجيب في موقع عمال الجزائر','d1UNekOaiHo','1','','2','29-06-2021','1','1','1'),
                        ('مجمع عمال الجزائر - dzworkers.com','Kmz-9d4Ybjs','1','<a href=\"www.dzworkers.com\"><b> [dzworkers.com] موقع جديد يسعى لجمع أكبر عدد ممكن من العمال </b></a> حتى تتسنى لهم الفرصة للحصول على عمل أو إظهار نشاطاتهم الحرفية . يسمح لك الموقع بالحصول على صفحة خاصة بك لإشهار عملك أو حرفتك كما يسمح لك أو بزائر موقعنـا تحميل السيرة الذاتية CV . <iframe class=\"infosite\" src=\"http://www.newmediene.com/info.php\" frameborder=\"0\" ></iframe>','3','12-01-2021','1','1','1') ");

                        // Add Class
                        $Add_class = $database->prepare("INSERT INTO downp_class (TITLE,PICTURE,ROLES) VALUES
                        ('هذا القسم يمكن التعديل عليه و لا يمكن حذفه لأنه أساسي !','files/class/class.jpg','BASIC'),
                        ('مواضيع متنوعة','files/class/02.jpg','POST'),
                        ('مواضيع خاصة بالمناظر الطبيعة','files/class/01.jpg','POST') ");

                        // Add Admin
                        $Add_admin= $database->prepare("INSERT INTO downp_admin (SITENAME,NUMBERPOSTS,NUMBERCLASS,FILESIZE,PICTURELOGO,DESCRIPTION) VALUES (:SITENAME,'20','6','5','files/logo/logo.jpg',:DESCRIPTION)");
                        $Add_admin->bindparam("SITENAME",$sitename);
                        $Add_admin->bindparam("DESCRIPTION",$description);

                        // Add Users
                        $Add_users= $database->prepare("INSERT INTO downp_users (PASSWORD,USERNAME,TEXT,AGE,EMAIL,NAME,SEX,ACTIVATED,DATEUP,ROLES) VALUES (:PASSWORD,:USERNAME,'Hello Word','1990',:EMAIL,:NAME,'MAN','1',:DATEUP,'ADMIN')");
                        $Add_users->bindparam("PASSWORD",$nepassword);
                        $Add_users->bindparam("USERNAME",$correcteUn);
                        $Add_users->bindparam("EMAIL",$email);
                        $Add_users->bindparam("NAME",$name);
                        $Add_users->bindparam("DATEUP",$DateUp);


                        if(isset($Creat_users) AND isset($Creat_admin) AND isset($Creat_ads) AND isset($Creat_class) AND isset($Creat_files) AND isset($Creat_msg) AND isset($Creat_post) AND isset($Creat_stat)){

                            if($Add_admin->execute() AND $Add_post->execute() AND $Add_class->execute() AND $Add_users->execute()){
                                echo "<div class='right' >تم تثبيت سكريبت DZmyc بنجاح يمكن إستعمال الموقع الآن !<br />يمكنك تعديل موقعك بالدخول إلى صفحة المدير عبر إسم المستخدم و كلمة المرور التي إخترتها - <a href='downp-admin/index.php' >هيا نبدأ بسم الله</a></div>";
                            }
                    
                        }else{
                            echo "<div class='error' >يوجد خطأ غير متوقع !</div>";
                        }

                    }

                }else{
                    echo "<div class='error' >يبدو أنك نسيت خانة فارغة !</div>";

                }

            }  ?> 
            
            <form class="forminput" method="POST" enctype="multipart/form-data"   >
                
                <div class="titleC" ><p >تثبيت الموقع</p></div><!--- title --->

                <div class="inputclass" ><p>إسم الموقع</p><input maxlength="100" type="text"  name="sitename" required></div>                
                <div class="inputclass" ><p>إسم الكاتب</p><input maxlength="100" type="text"  name="name" required></div>
                <div class="areaclass" ><p>وصف الموقع</p><textarea name="description" placeholder="إختر موضوع تصف به الموقع يضهر على الصفحة الرئيسية..." ></textarea></div>

                <div class="titleC" ><p >معلومات الدخول</p></div><!--- title --->
                

                <div class="inputclass" ><p>البريد الإلكتروني</p><input maxlength="50" type="email" name="email" required></div>
                <div class="inputclass" ><p>إسم المستخدم</p><input maxlength="50" type="text" value="" name="username" required></div>
                <div class="inputclass" ><p>كلمة المرور الجديدة</p><input maxlength="50" type="password" name="nePassword" required></div>
                <div class="inputclass" ><p>إعادة كلة المرور</p><input maxlength="50"    type="password" name="rePassword" required></div>  
                             

                <div id="conditions" >
                    <div class="chekclass" >                        
                        <input type="checkbox" required>
                        <p>أنا متأكد من إدخال المعلومات الصحيحة خاصة إسم المستخدم و كلمة المرور</p>
                    </div>
                </div><!--- conditions --->

                <div class="submitclass" >
                    <input type="submit" name="register" value="تثبيت المعلومات" >
                </div>
                
            </form><!--- forminput --->

            <?php
            }catch (PDOException $error_msql) { 
                echo "<div class='error' >يوجد خطأ تأكد من كتابة معلومات قاعدة البيانات بالشكل الصحيح في ملف confing.php</div>"; 

            } ?>

        </div><!--- content --->

        <div id="footer" >

            <div class="foot" >
                <p>سكريبت downp هو سكريبت مجاني 100% تم تصميمه من طرف <a href="http://www.newmediene.com" target="_blank" >عماني بومدين</a> لمساعدة أصحاب قنوات اليوتوب العربية الحصول على موقع خاص بقناتهم و تعريفها بشكل كبير يحتوي على مزايا متنوعة و بسيطة و لا يحتوي على إعلانات مزعجة .<br />
                <b style="background-color: red;">ملاحظة هامة :</b> لسنا المسؤولين على ما تنشره من فيديوهات نتمنى أن تستعمل السكريبت بما يرضي الله تعالى و أن تنشر أعمال يستفيد منها الناس .</p>
            </div><!--- foot --->
            
            <div class="bottomfoot" >
                <p><a href="http://www.newmediene.com" target="_blank" >سكريبتـDzmycـ</a> | تصميم : <a href="http://www.newmediene.com" target="_blank" >newmediene</a> | <?php echo date("Y"); ?></p>
            </div><!--- bottomfoot --->

        </div><!--- footer --->             
    
    <script src="style/dynamique.js" ></script>

    </body>

</html>