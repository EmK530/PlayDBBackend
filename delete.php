<?php
    error_reporting(0);
    if(isset($_POST["id"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        $id=$_POST['id'];
        $test = $conn->execute_query('SELECT uploader,name FROM levels WHERE id=?',[$id]);
        if(!$test){
            die('{"success":false,"error":1}');
        }
        $fetch = null;
        if ($test->num_rows > 0) {
            $fetch = $test->fetch_assoc();
            if($fetch['uploader']!=$_POST['uid']){
                die('{"success":false,"error":"You are not the owner of this level!"}');
                return;
            }
        } else {
            die('{"success":false,"error":2}');
            return;
        }
        $query = 'DELETE FROM `levels` ';
        if($conn->execute_query($query.'WHERE id=?',[$id])){
            echo '{"success":true}';
        } else {
            echo '{"success":false,"error":1}';
        }
    } else {
        http_response_code(400);
    }
?>
