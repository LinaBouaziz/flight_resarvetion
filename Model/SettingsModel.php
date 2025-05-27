<?php
class SettingsModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // جلب بيانات المستخدم
    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT username, name, email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // تحديث بيانات المستخدم بدون تغيير كلمة المرور
    public function updateUser($userId, $username, $name, $email) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $name, $email, $userId]);
    }

    // تحديث بيانات المستخدم مع كلمة المرور
    public function updateUserWithPassword($userId, $username, $name, $email, $hashedPassword) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, name = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$username, $name, $email, $hashedPassword, $userId]);
    }
}
?>
