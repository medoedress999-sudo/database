<?php
// إخفاء التحذيرات لضمان استقرار المخرجات
error_reporting(E_ERROR | E_PARSE);

// 1. الاتصال بقاعدة البيانات باستخدام بياناتك
$host = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$db   = 'test';
$user = 'vS11ZywUoDEi23o.root';
$pass = 'rPbhCpEfs88WJe1T';
$port = 4000;

$conn = new mysqli($host, $user, $pass, $db, $port);

// التحقق من الاتصال
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// إعداد الـ Headers
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 2. استقبال البيانات (POST)
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_doctor') {
        $stmt = $conn->prepare("INSERT INTO doctors (full_name, email, phone, department, position, office_hours, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST['full_name'], $_POST['email'], $_POST['phone'], $_POST['department'], $_POST['position'], $_POST['office_hours'], $_POST['status']);
        $stmt->execute() ? print(json_encode(["status" => "success"])) : print(json_encode(["status" => "error", "message" => $conn->error]));
    }
    elseif ($action === 'add_student') {
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, phone, department, academic_year, gpa, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST['full_name'], $_POST['email'], $_POST['phone'], $_POST['department'], $_POST['academic_year'], $_POST['gpa'], $_POST['status']);
        $stmt->execute() ? print(json_encode(["status" => "success"])) : print(json_encode(["status" => "error", "message" => $conn->error]));
    }
    elseif ($action === 'add_course') {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, assigned_doctors, department, credits) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['course_name'], $_POST['course_code'], $_POST['assigned_doctors'], $_POST['department'], $_POST['credits']);
        $stmt->execute() ? print(json_encode(["status" => "success"])) : print(json_encode(["status" => "error", "message" => $conn->error]));
    }
}

// 3. عرض البيانات (GET)
elseif ($method === 'GET') {
    $type = $_GET['type'] ?? '';
    if ($type === 'get_doctors') {
        $res = $conn->query("SELECT * FROM doctors");
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    } elseif ($type === 'get_students') {
        $res = $conn->query("SELECT * FROM students");
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    } elseif ($type === 'get_courses') {
        $res = $conn->query("SELECT * FROM courses");
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    }
}
?>
