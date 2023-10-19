<?php
error_reporting(0);
if (isset($_POST["sort"])&&isset($_POST["page"])) {
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'robot64_playdbpublic';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('{"success":false,"error":0}');
    }

    $extra = "WHERE deleted = 0 ORDER BY favorites DESC, visits DESC, dateofupload ASC";

    if ($_POST["sort"] == 2) {
        $extra = "WHERE deleted = 0 ORDER BY dateofupload DESC, id DESC";
    } elseif ($_POST["sort"] == 4) {
        $extra = "WHERE deleted = 0 ORDER BY visits DESC, favorites DESC, dateofupload ASC";
    } elseif ($_POST["sort"] == 7) {
        $extra = "WHERE deleted = 0 ORDER BY levelsize DESC, favorites DESC";
    } elseif ($_POST["sort"] == 10) {
        $extra = "WHERE deleted = 0 ORDER BY RAND()";
    } elseif ($_POST["sort"] == 3) {
        $extra = "WHERE uploader = ? AND deleted = 0 ORDER BY dateofupload DESC, id DESC";
    } elseif ($_POST["sort"] == 5) {
        $extra = "WHERE featured = 1 AND deleted = 0 ORDER BY favorites DESC, visits DESC, dateofupload ASC";
    } elseif ($_POST["sort"] == 6) {
        $extra = "WHERE name LIKE CONCAT('%',?,'%') AND deleted = 0 ORDER BY favorites DESC, visits DESC, dateofupload ASC";
    } elseif ($_POST["sort"] == 8) {
        $extra = "WHERE (id=?) AND deleted=0 ORDER BY featured DESC, favorites DESC, dateofupload ASC";
    } elseif ($_POST["sort"] == 9) {
        if (!isset($_POST['starred'])) {
            die('{"success":false,"error":3}');
        }
        $levels = explode(",", $_POST["starred"]);
        foreach ($levels as &$val) {
            if (!is_numeric($val)) {
                die('{"success":false,"error":4}');
            }
        }
        $extra = "WHERE id IN (" . $_POST["starred"] . ") AND deleted = 0 ORDER BY favorites DESC, visits DESC, dateofupload ASC";
    } elseif ($_POST["sort"] == 11) {
        $extra = "WHERE deleted = 1 ORDER BY dateofupload DESC, id DESC";
    } elseif ($_POST["sort"] == 12) {
        $extra = "WHERE uploader = ? AND deleted = 0 ORDER BY favorites DESC, visits DESC, dateofupload ASC";
    }
    $totalLevels = 0;
    if($_POST["sort"] != 10){
        $stmt = $conn->prepare('SELECT COUNT(*) FROM levels ' . $extra);
        if ($_POST["sort"] == 3) {
            if (!isset($_POST['uid'])) {
                die('{"success":false,"error":3}');
            }
            $stmt->bind_param('i', $_POST["uid"]);
        } elseif ($_POST["sort"] == 6) {
            if (!isset($_POST['search'])) {
                die('{"success":false,"error":3}');
            }
            $search = $_POST["search"];
            $stmt->bind_param('s', $search);
        } elseif ($_POST["sort"] == 8) {
            if (!isset($_POST['search'])) {
                die('{"success":false,"error":3}');
            }
            $search = $_POST["search"];
            $stmt->bind_param('i', $search);
        } elseif ($_POST["sort"] == 12) {
            if (!isset($_POST['search'])) {
                die('{"success":false,"error":3}');
            }
            $stmt->bind_param('i', $_POST["search"]);
        }

        if(!$stmt->execute()){
            die('{"success":false,"error":1}');
        }
        $stmt->bind_result($totalLevels);
        $stmt->fetch();
        $stmt->close();
    } else {
        $totalLevels = 1;
    }

    $stmt = $conn->prepare('SELECT id, name, favorites, uploader, visits, featured, levelsize FROM levels ' . $extra . ' LIMIT ?, 16');
    $pageOffset = $_POST['page'] * 16;
    if ($_POST["sort"] == 3) {
        $stmt->bind_param('ii', $_POST["uid"], $pageOffset);
    } elseif ($_POST["sort"] == 6) {
        $search = $_POST["search"];
        $stmt->bind_param('si', $search, $pageOffset);
    } elseif ($_POST["sort"] == 8) {
        $search = $_POST["search"];
        $stmt->bind_param('ii', $search, $pageOffset);
    } elseif ($_POST["sort"] == 12) {
        $stmt->bind_param('ii', $_POST["search"], $pageOffset);
    } else {
        $stmt->bind_param('i', $pageOffset);
    }

    if(!$stmt->execute()){
        die('{"success":false,"error":1}');
    }
    $result = $stmt->get_result();
    $levels = [];
    while ($row = $result->fetch_assoc()) {
        // Add necessary data manipulation here
        $id = $row['id'];
        $levels[] = [
            $id,
            $row['name'],
            $row['favorites'],
            $row['uploader'],
            $row['visits'],
            $row['levelsize']
        ];
    }

    echo json_encode(["success" => true, "total" => $totalLevels, "levels" => $levels]);
} else {
    http_response_code(400);
}
?>
