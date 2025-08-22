<?php
// إعدادات قاعدة البيانات
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'username'); // استبدل باسم المستخدم
define('DB_PASSWORD', 'password'); // استبدل بكلمة المرور
define('DB_NAME', 'alhayam_design');

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// التحقق من الاتصال
if($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// تعيين الترميز ليدعم اللغة العربية
$conn->set_charset("utf8");

// دالة لتنظيف بيانات الإدخال
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}
?>
