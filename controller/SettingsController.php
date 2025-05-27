<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../confi/db.php';          // الاتصال بقاعدة البيانات (تأكد من المسار الصحيح)
require_once '../Model/SettingsModel.php'; // تحميل الموديل

$model = new SettingsModel($pdo);
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $model->updateUserWithPassword($userId, $username, $name, $email, $hashedPassword);
    } else {
        $model->updateUser($userId, $username, $name, $email);
    }

    header("Location: ../View/settings.php?success=1");
    exit;
}

// جلب بيانات المستخدم للعرض
$user = $model->getUserById($userId);

if (!$user) {
    die("المستخدم غير موجود.");
}

// الآن عرض الصفحة (View)
require_once '../View/settings.php';
?>
