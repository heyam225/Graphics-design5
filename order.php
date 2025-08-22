<?php
// جلب ملف الاتصال بقاعدة البيانات
require_once 'includes/database.php';

// الرد الافتراضي
$response = array('success' => false, 'message' => 'حدث خطأ غير معروف');

// التحقق من أن الطلب من نوع POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جمع البيانات من النموذج
    $client_name = clean_input($_POST['client_name']);
    $client_email = clean_input($_POST['client_email']);
    $client_phone = clean_input($_POST['client_phone']);
    $project_type = clean_input($_POST['project_type']);
    $project_name = clean_input($_POST['project_name']);
    $project_description = clean_input($_POST['project_description']);
    $project_deadline = !empty($_POST['project_deadline']) ? clean_input($_POST['project_deadline']) : NULL;
    $additional_notes = !empty($_POST['additional_notes']) ? clean_input($_POST['additional_notes']) : '';

    try {
        // إدخال بيانات العميل في جدول clients
        $sql = "INSERT INTO clients (name, email, phone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $client_name, $client_email, $client_phone);
        $stmt->execute();
        $client_id = $stmt->insert_id;
        $stmt->close();

        // إدخال بيانات الطلب في جدول design_orders
        $sql = "INSERT INTO design_orders (client_id, project_type, project_name, description, deadline, additional_notes) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $client_id, $project_type, $project_name, $project_description, $project_deadline, $additional_notes);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // معالجة الملفات المرفقة إذا وجدت
        if (!empty($_FILES['project_files']['name'][0])) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            foreach ($_FILES['project_files']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['project_files']['error'][$key] === UPLOAD_ERR_OK) {
                    $file_name = time() . '_' . basename($_FILES['project_files']['name'][$key]);
                    $file_path = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        // إدخال معلومات الملف في جدول order_files
                        $sql = "INSERT INTO order_files (order_id, file_path) VALUES (?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("is", $order_id, $file_path);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }

        $response['success'] = true;
        $response['message'] = 'تم إرسال طلبك بنجاح! سنقوم بالتواصل معك قريباً.';
        
    } catch (Exception $e) {
        $response['message'] = 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'طريقة الطلب غير صحيحة';
}

// إرجاع الرد كـ JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
