<?php
    error_reporting(0);
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'robot64_playdbpublic';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('{"success":false,"error":0}');
    }
    $test = $conn->execute_query('SELECT recipient,amount FROM tokens WHERE amount != 0');
    if(!$test){
        die('{"success":false,"error":1}');
    }
    echo '[';
    $first=true;
    if ($test->num_rows > 0) {
        while($row = $test->fetch_assoc()){
            if(!$first){echo ',';}else{$first=false;}
            echo '['.$row['recipient'].','.$row['amount'].']';
        }
    }
    echo ']';
?>