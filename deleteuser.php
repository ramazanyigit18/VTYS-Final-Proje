<?php
// Hataları göster
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bağlantılar
require_once('connection.php');
require_once('mail_helper.php');

// E-posta parametresi var mı?
if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo "<script>alert('E-posta parametresi eksik.'); window.location.href='adminusers.php';</script>";
    exit;
}

$email = $_GET['email'];

// Temsili mail gönderimi (gerçek değil, sadece simülasyon)
sendUserNotificationMail($email, 'DELETE');

// Kullanıcıyı prosedürle sil
$stmt = $con->prepare("CALL delete_user(?)");

if (!$stmt) {
    die("Prosedür hazırlanamadı: " . htmlspecialchars($con->error));
}

$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $stmt->close();
    echo "<script>alert('Kullanıcı başarıyla silindi ve temsili mail gönderildi.'); window.location.href='adminusers.php';</script>";
    exit;
} else {
    echo "<script>alert('Silme işlemi başarısız: " . htmlspecialchars($stmt->error) . "'); window.location.href='adminusers.php';</script>";
}

$stmt->close();
?>