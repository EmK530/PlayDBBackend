<?php
    error_reporting(0);
    if(isset($_POST["slot"])&&isset($_POST["uid"])){
        $slot = strval($_POST['slot']);
        if($slot>=1&&$slot<=3){
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'robot64_playdbpublic';
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die('{"success":false,"error":0}');
            }
            if($conn->execute_query('REPLACE INTO `sav'.$slot.'` (uploader, data) VALUES(?,?)',[$_POST["uid"],$_POST["data"]])){
                echo '{"success":true}';
            } else {
                echo '{"success":false,"error":1}';
            }
        } else {
            die('{"success":false,"error":5}');
        }
    } else {
        http_response_code(400);
    }
?>