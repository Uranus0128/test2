<?php
// 数据库连接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sport_event";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kod_murid = $_POST['kod_murid'];
    $events = $_POST['events'];
    
    foreach ($events as $event) {
        // 判断事件类型
        $event_type = in_array($event, ['4x100m', '4x400m']) ? 'team' : 'personal';
        $stmt = $conn->prepare("INSERT INTO student_events (kod_murid, event_name, kategori, event_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $kod_murid, $event, $_POST['kategori'], $event_type);
        $stmt->execute();
    }

    echo "Events registered successfully!";
}

$conn->close();
?>
