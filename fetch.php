<?php
    error_reporting(0);
    if(isset($_POST["id"])&&isset($_POST["uid"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        $attempt = false;
        $id=$_POST["id"];
        if(isset($_POST['edit']) && $_POST["edit"] == true){
            $attempt = true;
        }
        $test = $conn->execute_query('SELECT data,uploader,name,deleted FROM levels WHERE id = ?',[$id]);
        if(!$test){
            die('{"success":false,"error":1}');
        }
        $first = true;
        $owner = "false";
        $success = "false";
        $name = "";
        $data = "";
        if ($test->num_rows > 0) {
            $row = $test->fetch_assoc();
            if($_POST["uid"]==$row["uploader"]){
                $owner = "true";
            }
            if(!$attempt&&$_POST["uid"]!=$row["uploader"]){
                $conn->execute_query('UPDATE `levels` SET visits=visits+1 WHERE id=?',[$id]);
            }
            $name = $row["name"];
            $data = $row["data"];
            $success = "true";
        } else {
            die('{"success":false,"error":2}');
        }
        echo json_encode(["success" => $success, "owner" => $owner, "name" => $name, "data" => $data]);
    } else {
        http_response_code(400);
    }
?>