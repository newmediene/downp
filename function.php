<?php

    function error_mysql($error_msql){

        if(file_exists("install.php")===true){
            die("
            <div style='margin: 0 auto; width: 100%; max-width: 1200px; font-size: 10pt;'>              
                <p style='width: 98%; padding:1%; background-color: #c72b2b; color:white;' >                
                <b>Error : </b>".$error_msql->getMessage()." + 
                <a href='install.php' style='color: black;' ><b>Iinstall Now</b></a></p>
            </div>" );

        }else{
            die("
            <div style='margin: 0 auto; width: 100%; max-width: 1200px; font-size: 10pt;'>              
                <p style='width: 98%; padding:1%; background-color: #c72b2b; color:white;' >                
                <b>Installation file not found + Error : </b>".$error_msql->getMessage()."</p>
            </div>" );            
        }
        
    }

?>