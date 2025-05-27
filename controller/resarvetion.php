<?php
require_once 'models/Reservation.php';

class ReservationController {
    private $reservationModel;

    public function __construct($pdo) {
        $this->reservationModel = new Reservation($pdo);
    }

    // إضافة حجز جديد مع المسافرين والدفع
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservationData = [
                'reservation_date' => $_POST['reservation_date'],
                'status' => $_POST['status'],
                'class_name' => $_POST['class_name'],
                'client_id' => $_POST['client_id'],
                'flight_number' => $_POST['flight_number'],
                'total_price' => $_POST['total_price']
            ];

            // إضافة الحجز
            $reservationNumber = $this->reservationModel->addReservation($reservationData);
            if (!$reservationNumber) {
                die('حدث خطأ أثناء إضافة الحجز.');
            }

            // إضافة المسافرين
            if (isset($_POST['passengers']) && is_array($_POST['passengers'])) {
                foreach ($_POST['passengers'] as $passenger) {
                    $passenger['reservation_number'] = $reservationNumber;
                    $this->reservationModel->addPassenger($passenger);
                }
            }

            // إضافة الدفع
            $paymentData = [
                'reservation_number' => $reservationNumber,
                'card_name' => $_POST['card_name'],
                'card_number' => $_POST['card_number'],
                'expiry_date' => $_POST['expiry_date'],
                'cvv' => $_POST['cvv'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->reservationModel->addPayment($paymentData);

            header("Location: booking-confirmed.php?reservation_number=" . $reservationNumber);
            exit;
        }
        include 'views/reservation_add.php';
    }

    // عرض جميع الحجوزات
    public function list() {
        $reservations = $this->reservationModel->getAllReservations();
        include 'views/reservation_list.php';
    }

    // تعديل حالة الحجز فقط
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservationNumber = $_POST['reservation_number'];
            $status = $_POST['status'];

            $success = $this->reservationModel->updateReservationStatus($reservationNumber, $status);
            if ($success) {
                header("Location: reservations.php?message=تم تحديث الحالة بنجاح");
                exit;
            } else {
                die("فشل تحديث حالة الحجز");
            }
        }
    }

    // تعديل بيانات الحجز بشكل عام
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservationNumber = $_POST['reservation_number'];
            $data = [
                'status' => $_POST['status'],
                'class_name' => $_POST['class_name'],
                'total_price' => $_POST['total_price']
            ];

            $success = $this->reservationModel->updateReservation($reservationNumber, $data);
            if ($success) {
                header("Location: reservations.php?message=تم تحديث الحجز بنجاح");
                exit;
            } else {
                die("فشل تحديث الحجز");
            }
        }
    }

    // عرض نموذج تعديل الحجز ومعالجة التحديث
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reservation_number'])) {
            $reservationNumber = $_GET['reservation_number'];
            $reservation = $this->reservationModel->getReservationById($reservationNumber);

            if ($reservation) {
                include 'views/reservation_edit.php';
            } else {
                die("الحجز غير موجود");
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
            $reservationNumber = $_POST['reservation_number'];
            $status = $_POST['status'];
            $departureTime = $_POST['departure_time'];

            $success = $this->reservationModel->updateReservationFields($reservationNumber, $status, $departureTime);
            if ($success) {
                header("Location: reservations.php?message=تم تعديل الحجز بنجاح");
                exit;
            } else {
                die("فشل تعديل الحجز");
            }
        } else {
            die("طلب غير صالح");
        }
    }

    // حذف الحجز
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_number'])) {
            $reservationNumber = $_POST['reservation_number'];

            $success = $this->reservationModel->deleteReservation($reservationNumber);
            if ($success) {
                header("Location: reservations.php?message=تم حذف الحجز بنجاح");
                exit;
            } else {
                die("فشل حذف الحجز");
            }
        } else {
            die("رقم الحجز غير محدد");
        }
    }
}
