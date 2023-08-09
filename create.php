<?php
    error_reporting(0);
    if(isset($_POST["name"])&&isset($_POST["uploader"])&&isset($_POST["data"])){
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'robot64_playdbpublic';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('{"success":false,"error":0}');
        }
        $hashConflict = false;
        $hash = hash('md5', $_POST["data"]);
        $test = $conn->execute_query('SELECT hash FROM levels');
        if(!$test){
            die('{"success":false,"error":1}');
        }
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
        if($conn->execute_query('INSERT INTO `levels` (name,uploader,data,favorites,visits,hash,deleted) VALUES (?,?,?,?,?,?,?)',[$_POST["name"],$_POST["uploader"],$_POST["data"],0,0,$hash,$deleted])){
            echo '{"success":true}';
        } else {
            echo '{"success":false,"error":1}';
        }
    } else {
        http_response_code(400);
    }
?>