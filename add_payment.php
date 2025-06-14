<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['add'])) {
    $book_id = intval($_POST['book_id']);
    $cardno = mysqli_real_escape_string($con, $_POST['cardno']);
    $exp = mysqli_real_escape_string($con, $_POST['exp']);
    $cvv = intval($_POST['cvv']);
    $price = floatval($_POST['price']);

    $stmt = $con->prepare("CALL AddPayment(?, ?, ?, ?, ?)");
    $stmt->bind_param("issid", $book_id, $cardno, $exp, $cvv, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Ödeme başarıyla eklendi.'); window.location.href='adminpayment.php';</script>";
    } else {
        echo "<script>alert('Ekleme başarısız: " . $stmt->error . "');</script>";
    }

    if ($stmt->execute()) {
    // Mail gönderimi için
    $to = $email; 
    $subject = "Ödemeniz Başarıyla Alındı";
    $message = "Sayın müşteri,\n\nÖdemeniz başarıyla alınmıştır. Teşekkür ederiz.\n\nİyi günler dileriz.";
    $headers = "From: noreply@siteadi.com";

    mail($to, $subject, $message, $headers);

    echo "<script>alert('Ödeme kaydedildi ve mail gönderildi.'); window.location.href='adminpayment.php';</script>";
    } else {
        echo "<script>alert('Ödeme kaydı başarısız: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Ekle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 50px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 15px;
        }

        button {
            background-color: #ff7200;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        #back {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 120px;
            height: 40px;
            background: #ff7200;
            border: none;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            z-index: 999;
        }

        #back a {
            color: white;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            height: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

    <button id="back"><a href="adminpayment.php">Anasayfa</a></button>

    <div class="form-container">
        <h2><i class="fas fa-credit-card"></i> Ödeme Ekle</h2>
        <form method="POST">
            <label>Rezervasyon ID (BOOK_ID)</label>
            <input type="number" name="book_id" min="1" required>

            <label>Kart No</label>
            <input type="text" name="cardno" pattern="\d{16}" maxlength="16" minlength="16" placeholder="16 haneli kart no" required>

            <label>Son Kullanma Tarihi</label>
            <input type="text" name="exp" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/\d{2}" required>

            <label>CVV</label>
            <input type="number" name="cvv" min="100" max="999" required>

            <label>Fiyat</label>
            <input type="number" name="price" step="0.01" min="0" required>

            <button type="submit" name="add">Ekle</button>
        </form>
    </div>

</body>
</html>
