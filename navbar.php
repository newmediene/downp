            <div id="header" >                
                
                <div class="topnav" >
                    <div class="leng" ><a target="_blank" href="http://www.dzworkers.com" ><img src="files/logo/icon.png" /></a></div>
                    <div class="logo" ><a href="index.php" ><img alt="<?php echo $Admin->SITENAME ?>" title="<?php echo $Admin->SITENAME ?>" src="<?php echo $Admin->PICTURELOGO ?>" /></a></div><!--- logo --->
                </div><!--- topnav --->
                
                <nav class="navbar" >
                    
                    <form class="search" method="GET" action="search.php" >
                        <input type="text" name="search" placeholder="إبحـث ..." >
                        <input type="hidden" name="submitH" value="Ok" >
                    </form>
                
                    <div class="buttons" >
                        
                        <ul><li class="active" id="menu" onclick="openNav()" ><a href="javascript:void(0);" >&#9776; <?php echo $Admin->SITENAME ?></a></li></ul>

                        <ul id="dropMenu" >

                            <li class="active" ><a href="index.php">الرئيسية</a></li>
                            
                            <li id="dropDown" ><a href="javascript:void(0);" >تعـرف علينا <i>&#8595;</i></a>
                                <div id="dropButton" >
                                    <a href="contact.php" ><img src="img/question.png" >من نحـن !</a>
                                    <a href="contact.php?send=msg" ><img src="img/tel.png" >إتصل بنـا !</a>
                                </div>
                            </li>
                            
                            <li><a href="all.php" >المواضيع</a></li>                    
                 
                            <?php if(isset($_SESSION['user']) AND isset($_SESSION['mail']) AND isset($_SESSION['pass'])){ ?>
                            <li><a href="logout.php?logout=active" >تسجيلـ الخروجـ</a></li>
                            <?php } ?>

                            <li id="clowsNav" ><a href="javascript:void(0);" onclick="closeNav()" ><img src="img/top.png" ></a></li>
                                             
                        </ul>

                    </div>

                </nav><!--- navbar -->
            
            </div><!--- header --->
