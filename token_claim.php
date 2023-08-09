<?php
    error_reporting(0);
    if(isset($_POST["recipient"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        if(!$conn->execute_query('UPDATE `tokens` SET amount=0 WHERE recipient IN ('.$_POST["recipient"].')')){
            echo '{"success":false,"error":1}';
        } else {
            echo '{"success":true}';
        }
    } else {
        http_response_code(400);
    }
?>