<?php
require_once('connection.php');

// ID kontrolü
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script>alert("Geçersiz ID")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
    exit();
}

$bookid = (int) $_GET['id'];  // Tip güvenliği için integer'a çevrildi

$sql = "SELECT * FROM booking WHERE BOOK_ID = $bookid";
$result = mysqli_query($con, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>alert("Rezervasyon bulunamadı")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
    exit();
}

$res = mysqli_fetch_assoc($result);
$car_id = $res['CAR_ID'];

$sql2 = "SELECT * FROM cars WHERE CAR_ID = $car_id";
$carres = mysqli_query($con, $sql2);
$carresult = mysqli_fetch_assoc($carres);

$email = $res['EMAIL'];
$carname = $carresult['CAR_NAME'];

if ($carresult['AVAILABLE'] == 'Y') {
    if ($res['BOOK_STATUS'] == 'APPROVED' || $res['BOOK_STATUS'] == 'RETURNED') {
        echo '<script>alert("Zaten onaylanmış")</script>';
        echo '<script> window.location.href = "adminbook.php";</script>';
    } else {
        $query = "UPDATE booking SET BOOK_STATUS = 'APPROVED' WHERE BOOK_ID = $bookid";
        mysqli_query($con, $query);

        $sql2 = "UPDATE cars SET AVAILABLE = 'N' WHERE CAR_ID = $car_id";
        mysqli_query($con, $sql2);

        echo '<script>alert("Başarıyla Onaylandı")</script>';
        echo '<script> window.location.href = "adminbook.php";</script>';
    }
} else {
    echo '<script>alert("Araç şu anda müsait değil")</script>';
    echo '<script> window.location.href = "adminbook.php";</script>';
}
?>