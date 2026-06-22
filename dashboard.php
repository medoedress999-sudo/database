<?php
// 1. الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "اسم_قاعدة_بياناتك");

// 2. التحقق من وجود طلب لتقرير مادة معينة
// لو بعتوا اسم مادة في الـ URL، هنفلتر بيها، لو مبعتوش، هنجيب الكل
$sql = "SELECT student_name, student_id, course_name, created_at FROM attendance ORDER BY created_at DESC";

if (isset($_GET['course_name'])) {
    $course = mysqli_real_escape_string($conn, $_GET['course_name']);
    $sql = "SELECT student_name, student_id, course_name, created_at FROM attendance WHERE course_name = '$course' ORDER BY created_at DESC";
}

$result = mysqli_query($conn, $sql);
$attendance_data = array();

// 3. تحويل النتائج لمصفوفة
while($row = mysqli_fetch_assoc($result)) {
    $attendance_data[] = $row;
}

// 4. الرد بصيغة JSON عشان الفلاتر
header('Content-Type: application/json');
echo json_encode($attendance_data);

mysqli_close($conn);
?>