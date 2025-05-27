<?php
class Client {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // ✅ التحقق من وجود بريد إلكتروني سابقًا (مُعدل لتفادي التكرار)
    public function emailExists(string $email, ?int $excludeUserId = null): bool {
        if ($excludeUserId !== null) {
            $sql = "SELECT COUNT(*) FROM client WHERE email = :email AND client_id != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email, 'id' => $excludeUserId]);
        } else {
            $sql = "SELECT COUNT(*) FROM client WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
        }

        return $stmt->fetchColumn() > 0;
    }

    // ✅ دالة التسجيل
    public function register($firstName, $lastName, $email, $password) {
        // تحقق من كلمة المرور
        if (!$this->validatePassword($password)) {
            return false; // كلمة المرور ضعيفة
        }

        // تحقق من البريد إذا كان مستخدمًا من قبل
        if ($this->emailExists($email)) {
            return false; // البريد مستخدم
        }

        // تشفير كلمة المرور
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // تنفيذ عملية التسجيل
        $sql = "INSERT INTO client (first_name, last_name, email, password)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
    }

    // ✅ التحقق من قوة كلمة المرور
    private function validatePassword($password) {
        // 8 رموز مختلطة بين أحرف وأرقام ورموز خاصة
        $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($regex, $password);
    }

    // ✅ تسجيل الدخول
    public function loginWithEmail($email, $password) {
        $sql = "SELECT client_id, first_name, last_name, email, password, role FROM client WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // ✅ جلب جميع المستخدمين (باستثناء الأدمن)
    public function getAllClients() {
        $sql = "SELECT * FROM client WHERE role = 'client'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ حذف مستخدم
    public function deleteClientById($id) {
        $stmt = $this->db->prepare("DELETE FROM client WHERE client_id = ?");
        return $stmt->execute([$id]);
    }

    // ✅ جلب مستخدم حسب الـ ID
    public function getClientById($id) {
        $sql = "SELECT * FROM client WHERE client_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ تحديث بيانات المستخدم
    public function updateClient($id, $firstName, $lastName, $email, $phone) {
        $sql = "UPDATE client SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE client_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$firstName, $lastName, $email, $phone, $id]);
    }
}
?>




