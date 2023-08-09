<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'robot64_playdbpublic';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('{"success":false,"error":"Could not connect to MySQL."}');
    }
    $total = $conn->execute_query('SELECT id FROM levels');
    echo 'Robot 64 PlayDB API<br><br>Analytics:<br>Total levels: '.$total->num_rows;
    echo '<br><br>Apache Version 2.4.54';
    echo '<br>PHP Version '.phpversion();
    echo '<br>System: Windows Server 2022 Version 21H2 Build 20348.768';
    echo '<br><br>Hosted with love by EmK530 <3';
    http_response_code(200);
?>