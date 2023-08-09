<?php
    error_reporting(0);
    if(isset($_POST["favorites"])&&isset($_POST["id"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        $id = $_POST['id'];
        $fav = $_POST["favorites"]
        if($fav<-1||$fav>1){
            die('{"success":false,"error":6}');
        }
        if(!$conn->execute_query('UPDATE `levels` SET favorites=favorites+? WHERE id=?',[$fav,$id])){
            echo '{"success":false,"error":1}';
        } else {
            echo '{"success":true}';
        }
    } else {
        http_response_code(400);
    }
?>