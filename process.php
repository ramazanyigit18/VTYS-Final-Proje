<?php
session_start();
require_once('connection.php');

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Email session'ı üzerinden kullanıcı bilgilerini çek
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE EMAIL = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Araç bilgilerini çek (mevcut araçlar)
$sql2 = "SELECT * FROM cars WHERE AVAILABLE = 'Y'";
$cars = mysqli_query($con, $sql2);

// rdate kontrolü yap (var mı yok mu diye)
if (!isset($_SESSION['rdate'])) {
    // Eğer yoksa hata göstermeden anasayfaya gönder
    header("Location: index.php");
    exit();
}

// rdate değeri çekiliyor
$rdate = $_SESSION['rdate'];

// Ekrana yazdırmak istersen
echo "Rezervasyon tarihi: " . htmlspecialchars($rdate);

// Eğer kullanıcı kayıt olmak istiyorsa (örnek kayıt kodu aşağıda yorumlu)
if (isset($_POST['regs'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $lic = mysqli_real_escape_string($con, $_POST['lic']);
    $ph = mysqli_real_escape_string($con, $_POST['ph']);
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);

    if (empty($fname) || empty($lname) || empty($email) || empty($lic) || empty($ph) || empty($id) || empty($pass)) {
        echo 'Lütfen tüm alanları doldurun!';
    } else {
        $sql = "INSERT INTO users (FNAME, LNAME, EMAIL, LIC_NUM, PHONE_NUMBER, USER_ID, PASSWORD) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssiss", $fname, $lname, $email, $lic, $ph, $id, $pass);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo 'Kayıt başarıyla yapıldı!';
        } else {
            echo 'Bir hata oluştu, lütfen tekrar deneyin.';
        }
    }
}
?>