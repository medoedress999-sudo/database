<?php
// استدعاء ملف الاتصال بالداتابيس
require_once 'db.php'; 

// إعداد الرأس ليكون JSON ليتعامل معه الفرونت اند بسهولة
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// --- 1. التعامل مع طلبات إضافة البيانات (POST) ---
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    // إضافة دكتور
    if ($action === 'add_doctor') {
        $stmt = $conn->prepare("INSERT INTO doctors (full_name, email, phone, department, position, office_hours, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST['full_name'], $_POST['email'], $_POST['phone'], $_POST['department'], $_POST['position'], $_POST['office_hours'], $_POST['status']);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "تم إضافة الدكتور بنجاح"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
    // إضافة طالب
    elseif ($action === 'add_student') {
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, phone, department, academic_year, gpa, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST['full_name'], $_POST['email'], $_POST['phone'], $_POST['department'], $_POST['academic_year'], $_POST['gpa'], $_POST['status']);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "تم إضافة الطالب بنجاح"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
    // إضافة كورس
    elseif ($action === 'add_course') {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, assigned_doctors, department, credits) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['course_name'], $_POST['course_code'], $_POST['assigned_doctors'], $_POST['department'], $_POST['credits']);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "تم إضافة الكورس بنجاح"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
}

// --- 2. التعامل مع طلبات عرض البيانات (GET) ---
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