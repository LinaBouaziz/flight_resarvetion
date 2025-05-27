<?php
require_once 'config.php';
require_once 'Reservation.php';

$reservationModel = new Reservation($pdo);

// تحقق من وجود رقم الحجز في الرابط
if (empty($_GET['reservation_number'])) {
    // يمكن توجيه لصفحة الخطأ أو القائمة
    header("Location: reservations_list.php");
    exit;
}

$reservationNumber = $_GET['reservation_number'];

// جلب بيانات الحجز
$reservation = $reservationModel->getReservationById($reservationNumber);
if (!$reservation) {
    header("Location: reservations_list.php");
    exit;
}

// عند استلام POST (تأكيد أو إلغاء الحذف)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'نعم') {
        $deleted = $reservationModel->deleteReservation($reservationNumber);
        if ($deleted) {
            // يمكن هنا تعيين رسالة في الـ Session ثم إعادة التوجيه
            $_SESSION['message'] = "تم حذف الحجز بنجاح.";
            header("Location: reservations_list.php");
            exit;
        } else {
            $error = "حدث خطأ أثناء حذف الحجز.";
        }
    } else {
        // إلغاء الحذف
        header("Location: reservations_list.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تأكيد حذف الحجز</title>
</head>
<body>
    <h2>تأكيد حذف الحجز رقم: <?= htmlspecialchars($reservation['reservation_number']) ?></h2>
    <p>تاريخ الحجز: <?= htmlspecialchars($reservation['reservation_date']) ?></p>
    <p>الحالة الحالية: <?= htmlspecialchars($reservation['status']) ?></p>
    <p>هل أنت متأكد من حذف هذا الحجز؟</p>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="confirm" value="نعم">نعم، احذف</button>
        <button type="submit" name="confirm" value="لا">لا، إلغاء</button>
    </form>
</body>
</html>
