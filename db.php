<?php
$host ="localhost";
$username ="root";
$password ="";
$dbname ="attendance_db";
$conn =new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعده البيانات") .$conn->connect_error;
}
?>