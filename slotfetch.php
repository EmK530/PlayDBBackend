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
            $test = $conn->execute_query('SELECT data FROM sav'.$slot.' WHERE uploader = ?',[$_POST["uid"]]);
            if(!$test){
                die('{"success":false,"error":1}');
            }
            if($test->num_rows == 0){
                die('{"success":true,"data":null}');
            } else {
                $data = $test->fetch_assoc()["data"];
                if(strlen($data)==3){
                    die('{"success":true,"data":null}');
                }
                echo json_encode(["success" => true, "data" => $data]);
            }
        } else {
            die('{"success":false,"error":5}');
        }
    } else {
        http_response_code(400);
    }
?>