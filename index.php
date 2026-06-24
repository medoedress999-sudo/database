<?php
// إعدادات الرد ليكون بصيغة JSON ليفهمه الفرونت إند
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include 'db.php';

// استقبال نوع الطلب (GET أو POST)
$method = $_SERVER['REQUEST_METHOD'];

// 1. لو الطلب POST (إضافة بيانات من الفرونت إند)
if ($method === 'POST') {
    $full_name = isset($_POST['full_name']) ? $conn->real_escape_string($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';

    if (!empty($full_name) && !empty($email)) {
        // تم استخدام full_name و email المطابقين لجدولك
        $sql = "INSERT INTO students (full_name, email) VALUES ('$full_name', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "تمت إضافة الطالب بنجاح"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "بيانات ناقصة"]);
    }
} 
// 2. لو الطلب GET (عرض بيانات الطلاب للفرونت إند)
else if ($method === 'GET') {
    $sql = "SELECT id, full_name, email FROM students";
    $result = $conn->query($sql);
    $students = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    echo json_encode($students);
}
?>
