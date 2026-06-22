<?php
/*$host ="localhost";
$username ="root";
$password ="";
$dbname ="attendance_db";
$conn =new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعده البيانات") .$conn->connect_error;
}*/
$host ="gateway01.eu-central-1.prod.aws.tidbcloud.com";
$username ="vS11ZywUoDEi23o.root";
$password ="rPbhCpEfs88WJe1T";
$port =4000;
$dbname ="test";
/*$conn =new mysqli($host, $username, $password, $dbname,$port);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعده البيانات") .$conn->connect_error;
}*/
// إنشاء كائن الاتصال
$conn = mysqli_init();

// تفعيل خيارات الاتصال الآمن (SSL)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// الاتصال بالقاعدة
if (!$conn->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("فشل الاتصال الآمن: " . mysqli_connect_error());
}

echo "تم الاتصال بنجاح باستخدام اتصال آمن!";
?>