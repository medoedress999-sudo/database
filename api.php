<?php
// حل مشكلة الـ CORS نهائياً
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_db"; // اسم قاعدة البيانات الصح بتاعك

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array("status" => "error", "message" => "فشل الاتصال: " . $conn->connect_error));
    exit();
}

$conn->set_charset("utf8mb4");

// استقبال البيانات
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['student_name']) && !empty($data['course_name'])) {
    
    // ربط المتغيرات بأسماء العواميد الحقيقية في جدولك بالظبط
    $name = $conn->real_escape_string($data['student_name']);
    $course = $conn->real_escape_string($data['course_name']);
    $qr_token = isset($data['qr_code_scanned']) ? $conn->real_escape_string($data['qr_code_scanned']) : '';
    $latitude = isset($data['student_lat']) ? (float)$data['student_lat'] : 0.0;
    $longitude = isset($data['student_lon']) ? (float)$data['student_lon'] : 0.0;
    $stuts = "حاضر"; 

    // الاستعلام المظبوط على أسامي عواميدك في الـ phpMyAdmin
    $sql = "INSERT INTO students (name, stuts, course, latitude, longitude, qr_token) 
            VALUES ('$name', '$stuts', '$course', $latitude, $longitude, '$qr_token')";

    if ($conn->query($sql) === TRUE) {
        http_response_code(200);
        echo json_encode(array("status" => "success", "message" => "مبروك! سجلنا الحضور بنجاح في جدولك"), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(array("status" => "error", "message" => "خطأ في الاستعلام: " . $conn->error), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "البيانات المرسلة ناقصة"));
}

$conn->close();
?>