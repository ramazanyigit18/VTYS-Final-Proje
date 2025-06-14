<?php
session_start();
require_once('connection.php'); // Veritabanı bağlantısı için

// Formdan gelen verileri alalım
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı için değişkenleri ayarlıyoruz
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']);

    // Veritabanına veri eklemek için SQL sorgusu
    $sql = "INSERT INTO feedback (email, comment) VALUES ('$email', '$comments')";

    // Sorguyu çalıştırıyoruz
    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Geri bildiriminiz başarıyla kaydedildi!")</script>';
        echo '<script>window.location.href = "feedback.html";</script>'; // Başarıyla gönderildikten sonra kullanıcıyı yönlendiriyoruz
    } else {
        echo '<script>alert("Bir hata oluştu. Lütfen tekrar deneyin.")</script>';
        echo '<script>window.location.href = "feedback_form.php";</script>';
    }
}

// Veritabanı bağlantısını kapatalım
mysqli_close($con);
?>