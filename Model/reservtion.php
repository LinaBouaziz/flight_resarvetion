<?php

class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // إضافة حجز جديد، ترجع رقم الحجز الجديد
    public function addReservation($data) {
        $sql = "INSERT INTO reservation (reservation_date, status, class_name, client_id, flight_number, total_price)
                VALUES (:reservation_date, :status, :class_name, :client_id, :flight_number, :total_price)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':reservation_date' => $data['reservation_date'],
            ':status' => $data['status'],
            ':class_name' => $data['class_name'],
            ':client_id' => $data['client_id'],
            ':flight_number' => $data['flight_number'],
            ':total_price' => $data['total_price']
        ]);
        return $this->pdo->lastInsertId();
    }

    // إضافة مسافر مرتبط بحجز معين
    public function addPassenger($data) {
        $sql = "INSERT INTO passenger (name, passport_number, nationality, reservation_number)
                VALUES (:name, :passport_number, :nationality, :reservation_number)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':passport_number' => $data['passport_number'],
            ':nationality' => $data['nationality'],
            ':reservation_number' => $data['reservation_number']
        ]);
    }

    // إضافة معلومات دفع مرتبطة بالحجز
    public function addPayment($data) {
        $sql = "INSERT INTO payment (reservation_number, card_name, card_number, expiry_date, cvv, created_at)
                VALUES (:reservation_number, :card_name, :card_number, :expiry_date, :cvv, :created_at)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':reservation_number' => $data['reservation_number'],
            ':card_name' => $data['card_name'],
            ':card_number' => $data['card_number'],
            ':expiry_date' => $data['expiry_date'],
            ':cvv' => $data['cvv'],
            ':created_at' => $data['created_at']
        ]);
    }

    // جلب كل الحجوزات بترتيب تنازلي حسب تاريخ الحجز
    public function getAllReservations() {
        $sql = "SELECT * FROM reservation ORDER BY reservation_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // تحديث حالة الحجز فقط
    public function updateReservationStatus($reservationNumber, $status) {
        $sql = "UPDATE reservation SET status = :status WHERE reservation_number = :reservation_number";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':reservation_number' => $reservationNumber
        ]);
    }

    // تحديث معلومات عامة للحجز (حالة، درجة، السعر الإجمالي)
    public function updateReservation($reservationNumber, $data) {
        $sql = "UPDATE reservation SET status = :status, class_name = :class_name, total_price = :total_price
                WHERE reservation_number = :reservation_number";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $data['status'],
            ':class_name' => $data['class_name'],
            ':total_price' => $data['total_price'],
            ':reservation_number' => $reservationNumber
        ]);
    }

    // جلب حجز معين بواسطة رقم الحجز
    public function getReservationById($reservationNumber) {
        $sql = "SELECT * FROM reservation WHERE reservation_number = :reservation_number";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':reservation_number' => $reservationNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // تحديث بعض الحقول في الحجز، مع تحديث وقت الانطلاق في الرحلة المرتبطة (إن وجد)
    public function updateReservationFields($reservationNumber, $status, $departureTime) {
        $sql1 = "UPDATE reservation SET status = :status WHERE reservation_number = :reservation_number";
        $stmt1 = $this->pdo->prepare($sql1);
        $result1 = $stmt1->execute([
            ':status' => $status,
            ':reservation_number' => $reservationNumber
        ]);

        // تحديث وقت الانطلاق في جدول الرحلات المرتبطة بالحجز
        $sql2 = "UPDATE flight SET departure_time = :departure_time WHERE flight_number = (
                    SELECT flight_number FROM reservation WHERE reservation_number = :reservation_number
                )";
        $stmt2 = $this->pdo->prepare($sql2);
        $result2 = $stmt2->execute([
            ':departure_time' => $departureTime,
            ':reservation_number' => $reservationNumber
        ]);

        return $result1 && $result2;
    }

    // حذف حجز معين بواسطة رقم الحجز
    public function deleteReservation($reservationNumber) {
        $sql = "DELETE FROM reservation WHERE reservation_number = :reservation_number";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':reservation_number' => $reservationNumber]);
    }
}







