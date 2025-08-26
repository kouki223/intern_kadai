<?php
// fuel/app/db_test.php
try {
    $mysqli = new mysqli('db', 'root', 'root', 'memoapp', 3306);
    if ($mysqli->connect_error) {
        throw new Exception('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    echo "DB接続成功!\n";
    $result = $mysqli->query("SHOW TABLES;");
    while($row = $result->fetch_array()) {
        echo $row[0] . "\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
