<?php
    error_reporting(0);
    if(isset($_POST["name"])&&isset($_POST["uploader"])&&isset($_POST["data"])&&isset($_POST["id"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        $id = $_POST['id'];
        $hashConflict = false;
        $hash = hash('md5', $_POST["data"]);
        $test = $conn->execute_query('SELECT hash FROM levels WHERE id != ?',[$id]);
        if ($test->num_rows > 0) {
            while($row = $test->fetch_assoc()){
                if($row['hash']==$hash){
                    $hashConflict=true;
                    echo '{"success":false,"error":7}';
                    return;
                    break;
                }
            }
        }
        $test2 = $conn->execute_query('SELECT uploader FROM levels WHERE id=?',[$id]);
        if($test2->num_rows == 0){
            echo '{"success":false,"error":2}';
            return;
        }
        $row = $test2->fetch_assoc();
        if(!isset($row)){
            echo '{"success":false,"error":1}';
            return;
        }
        if($row["uploader"]!=$_POST["uploader"]){
            echo '{"success":false,"error":8}';
            return;
        }
        $size = strlen($_POST["data"]);
        if($conn->execute_query('UPDATE `levels` SET name=?,data=?,levelsize=? WHERE id=?',[$_POST["name"],$_POST["data"],$size,$id])){
            echo '{"success":true}';
        } else {
            echo '{"success":false,"error":1}';
        }
    } else {
        http_response_code(400);
    }
?>