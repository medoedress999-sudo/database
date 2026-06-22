<?php
// 1. إعدادات الـ CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. التعامل مع طلبات الـ OPTIONS (للموبايل والـ API)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
$hostname = "gateway01.eu-central-1.prod.aws.tidbcloud.com";
$username = "vS11ZywUoDEi23o.root";
$password = "rPbhCpEfs88WJe1T";
$dbname   = "test";
$port     = 4000;

$conn = mysqli_init();

// السطر الجديد اللي هيجبر XAMPP يتجاهل فحص الشهادة ويفتح التشفير فوراً
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// الاتصال مع إجبار استخدام التشفير
if (!mysqli_real_connect($conn, $hostname, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    http_response_code(500);
    echo json_encode(array("status" => "error", "message" => "فشل الاتصال: " . mysqli_connect_error()));
    exit();
}

$conn->set_charset("utf8mb4");


// 5. استقبال ومعالجة البيانات
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['student_name']) && !empty($data['course_name'])) {
    $name = $conn->real_escape_string($data['student_name']);
    $course = $conn->real_escape_string($data['course_name']);
    
    $sql = "INSERT INTO students (name, stuts, course) VALUES ('$name', 'حاضر', '$course')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("status" => "success", "message" => "تم تسجيل الحضور بنجاح"));
    } else {
        echo json_encode(array("status" => "error", "message" => "خطأ في الاستعلام: " . $conn->error));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "البيانات ناقصة"));
}

$conn->close();
?>