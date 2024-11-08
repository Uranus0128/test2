<?php
// 连接数据库
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sport_event";

$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取 kod_murid 参数
if (isset($_GET['kod_murid'])) {
    $kod_murid = $_GET['kod_murid'];

    // 从 borangacara 表获取学生的基本信息
    $sql_student = "SELECT nama, rumah, kelas, kategori FROM borangacara WHERE kod_murid = '$kod_murid'";
    $result_student = $conn->query($sql_student);

    if ($result_student->num_rows > 0) {
        $student = $result_student->fetch_assoc();

        // 从 student_events 表获取学生已注册的活动
        $sql_events = "SELECT event1, event2, event3, event4, event5 FROM student_events WHERE kod_murid = '$kod_murid'";
        $result_events = $conn->query($sql_events);

        if ($result_events->num_rows > 0) {
            $events = $result_events->fetch_assoc();
            // 合并学生信息和活动信息
            $student = array_merge($student, $events);
        } else {
            // 如果没有找到已注册活动，返回默认值
            $student['event1'] = "";
            $student['event2'] = "";
            $student['event3'] = "";
            $student['event4'] = "";
            $student['event5'] = "";
        }

        // 返回 JSON 格式的学生信息和活动信息
        echo json_encode($student);
    } else {
        // 如果没有找到该学生，返回空结果
        echo json_encode(['error' => '学生信息未找到']);
    }
}

// 关闭数据库连接
$conn->close();
?>
