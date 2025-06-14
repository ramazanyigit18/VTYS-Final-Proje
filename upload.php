<?php
if (isset($_POST['addcar'])) {
    require_once('connection.php');

    $img_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

    if ($error === 0) {
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "webp", "svg");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'images/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);

            // Form verilerini güvenli hale getir
            $carname = mysqli_real_escape_string($con, $_POST['carname']);
            $ftype = mysqli_real_escape_string($con, $_POST['ftype']);
            $price = floatval($_POST['price']);
            $available = ($_POST['available'] == 1) ? 'Y' : 'N';

            // Saklı yordamı (Stored Procedure) kullanarak veri ekle
            $stmt = $con->prepare("CALL AddCar(?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $carname, $ftype, $price, $available, $new_img_name);
            $stmt->execute();

            if ($stmt) {
                echo '<script>alert("Araç Başarılı Bir Şekilde Kayıt Edildi!")</script>';
                echo '<script> window.location.href = "adminvehicle.php";</script>';
            } else {
                echo '<script>alert("Veritabanı hatası")</script>';
            }

            $stmt->close();
        } else {
            echo '<script>alert("Görsel formatı geçersiz!")</script>';
            echo '<script> window.location.href = "addcar.php";</script>';
        }
    } else {
        $em = "Bilinmeyen bir hata oluştu";
        header("Location: addcar.php?error=$em");
    }
} else {
    echo "Formdan gelen veri yok.";
}
?>