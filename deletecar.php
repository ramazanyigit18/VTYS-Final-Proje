<?php
session_start();
require_once('connection.php');

// Oturum kontrolü
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Silme işlemi
if (isset($_GET['delete'])) {
    $car_id = intval($_GET['delete']);

    $stmt = $con->prepare("CALL DeleteCar(?)");
    $stmt->bind_param("i", $car_id);

    if ($stmt->execute()) {
        echo '<script>alert("Araç başarıyla silindi.")</script>';
    } else {
        echo '<script>alert("Araç silinemedi.")</script>';
    }

    $stmt->close();
    echo '<script>window.location.href="adminvehicle.php";</script>';
}
?>
