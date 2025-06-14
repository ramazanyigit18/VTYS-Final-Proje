<?php
require_once('connection.php');

// GET parametrelerini kontrol et
if (!isset($_GET['id']) || !isset($_GET['bookid']) || !is_numeric($_GET['id']) || !is_numeric($_GET['bookid'])) {
    echo '<script>alert("Geçersiz veya eksik parametreler")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
    exit();
}

// Parametreleri integer'a çevir (SQL injection'dan korur)
$carid = (int) $_GET['id'];
$book_id = (int) $_GET['bookid'];

// Booking bilgisi alınıyor
$sql2 = "SELECT * FROM booking WHERE BOOK_ID = $book_id";
$result2 = mysqli_query($con, $sql2);
if (!$result2 || mysqli_num_rows($result2) === 0) {
    echo '<script>alert("Rezervasyon bulunamadı")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
    exit();
}
$res2 = mysqli_fetch_assoc($result2);

// Araç bilgisi alınıyor
$sql = "SELECT * FROM cars WHERE CAR_ID = $carid";
$result = mysqli_query($con, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>alert("Araç bulunamadı")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
    exit();
}
$res = mysqli_fetch_assoc($result);

// Duruma göre işlem yapılır
if ($res['AVAILABLE'] == 'Y') {
    echo '<script>alert("ARAÇ ZATEN TESLİM ALINMIŞ")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
} else {
    // Araç müsait olarak güncelleniyor
    $sql4 = "UPDATE cars SET AVAILABLE = 'Y' WHERE CAR_ID = $carid";
    mysqli_query($con, $sql4);

    // Rezervasyon durumu RETURNED olarak işaretleniyor
    $sql5 = "UPDATE booking SET BOOK_STATUS = 'RETURNED' WHERE BOOK_ID = $book_id";
    mysqli_query($con, $sql5);

    echo '<script>alert("ARAÇ BAŞARIYLA TESLİM ALINDI")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
}
?>