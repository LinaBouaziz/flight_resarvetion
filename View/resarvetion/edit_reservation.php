<?php
require_once '../../model/Reservation.php';

// إعداد اتصال PDO - عدّله حسب بياناتك
$pdo = new PDO('mysql:host=localhost;dbname=your_db;charset=utf8', 'username', 'password');
$reservationModel = new Reservation($pdo);

// التحقق من وجود ID الحجز
if (!isset($_GET['id'])) {
    die('Reservation ID is required.');
}

$reservationId = $_GET['id'];
$reservation = $reservationModel->getReservationById($reservationId);

if (!$reservation) {
    die('Reservation not found.');
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>تعديل الحجز</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background-color: #f5f5f5;
    }
    h2 {
      color: #b22234;
    }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    label {
      font-weight: bold;
      display: block;
      margin: 15px 0 5px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #b22234;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h2>تعديل الحجز رقم #<?= htmlspecialchars($reservation['reservation_number']) ?></h2>

  <form method="POST" action="../../controller/reservation.php">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="reservation_number" value="<?= htmlspecialchars($reservation['reservation_number']) ?>">

    <label for="status">الحالة</label>
    <select name="status" id="status" required>
      <option value="Confirmed" <?= $reservation['status'] === 'Confirmed' ? 'selected' : '' ?>>مؤكد</option>
      <option value="Cancelled" <?= $reservation['status'] === 'Cancelled' ? 'selected' : '' ?>>ملغي</option>
      <option value="Pending" <?= $reservation['status'] === 'Pending' ? 'selected' : '' ?>>معلق</option>
    </select>

    <label for="departure_time">تاريخ ووقت الإقلاع</label>
    <input type="datetime-local" name="departure_time" id="departure_time" 
      value="<?= isset($reservation['departure_time']) ? date('Y-m-d\TH:i', strtotime($reservation['departure_time'])) : '' ?>" required>

    <button type="submit">حفظ التغييرات</button>
  </form>

</body>
</html>
