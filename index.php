<?php
include 'db.php';

echo "<div style='direction: rtl; text-align: right; font-family: Arial; padding: 20px;'>";
echo "<h1>📋 نظام الحضور الذكي - لوحة التحكم</h1>";

// 2. كود استقبال اسم الطالب من الفورم وإضافته للمخزن
if (isset($_POST['add_student'])) {
    $name = isset($_POST['student_name']) ? $conn->real_escape_string($_POST['student_name']) : '';
    
    if (!empty($name)) {
        // بنضيف الطالب بحالة افتراضية 'حضور'
        $insert_sql = "INSERT INTO students (name, stuts) VALUES ('$name', 'حضور')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "<p style='color: green; font-weight: bold;'>✔️ تم إضافة الطالب ($name) بنجاح!</p>";
        } else {
            echo "<p style='color: red;'>❌ خطأ في الإضافة: " . $conn->error . "</p>";
        }
    }
}

// 3. استقبال الفلاتر (المادة والتاريخ) أول ما الـ Frontend يبعتها
$selected_course = isset($_GET['course']) ? $_GET['course'] : '';
$selected_date = isset($_GET['date']) ? $_GET['date'] : '';

echo "<h3>🔍 الفلاتر النشطة حالياً:</h3>";
echo "<p>🔹 المادة: " . ($selected_course ? $selected_course : 'كل المواد') . "</p>";
echo "<p>🔹 التاريخ: " . ($selected_date ? $selected_date : 'كل التواريخ') . "</p>";

// 4. عرض الجدول بناءً على الفلتر
$sql = "SELECT * FROM students WHERE 1=1";
if (!empty($selected_course)) {
    $sql .= " AND course = '$selected_course'";
}
if (!empty($selected_date)) {
    $sql .= " AND attendance_date = '$selected_date'";
}

$result = $conn->query($sql);

echo "<h3>📋 قائمة الطلاب والغياب:</h3>";
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; text-align: center; margin-bottom: 30px;'>";
    echo "<tr style='background-color: #f2f2f2;'><th>الـ ID</th><th>اسم الطالب</th><th>حالة الحضور (Stuts)</th><th>المادة (Course)</th><th>التاريخ</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . (isset($row['id']) ? $row['id'] : '') . "</td>";
        echo "<td>" . (isset($row['name']) ? $row['name'] : '') . "</td>";
        echo "<td>" . (isset($row['stuts']) ? $row['stuts'] : '') . "</td>";
        echo "<td>" . (isset($row['course']) && $row['course'] ? $row['course'] : 'غير محدد') . "</td>";
        // هنا السطر رقم 53 اللي كان عامل مشكلة، عملنا عليه تشيك أوتوماتيك عشان يختفي التحذير
        echo "<td>" . (isset($row['attendance_date']) && $row['attendance_date'] ? $row['attendance_date'] : 'غير محدد') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ لا توجد بيانات حضور مسجلة تطابق هذا الفلتر حالياً.</p>";
}

// 5. الفورم بتاعك لإضافة طالب جديد
echo "<hr>";
echo "<h3>➕ إضافة طالب جديد:</h3>";
echo "<form method='POST' action='index.php'>";
echo "  <input type='text' name='student_name' placeholder='اكتب اسم الطالب هنا' required style='padding: 5px; width: 200px;'> ";
echo "  <button type='submit' name='add_student' style='padding: 5px 15px; cursor: pointer;'>إرسال</button>";
echo "</form>";

echo "</div>";
?>