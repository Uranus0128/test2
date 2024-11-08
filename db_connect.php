<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sport_event";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 检查连接
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
