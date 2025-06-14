<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Geçersiz ID.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $con->prepare("SELECT * FROM payment WHERE PAY_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Kayıt bulunamadı.";
    exit();
}

if (isset($_POST['update'])) {
    $cardno = mysqli_real_escape_string($con, $_POST['cardno']);
    $exp = mysqli_real_escape_string($con, $_POST['exp']);
    $cvv = intval($_POST['cvv']);
    $price = floatval($_POST['price']);

    if (empty($cardno) || empty($exp) || empty($cvv) || empty($price)) {
        echo "<script>alert('Lütfen tüm alanları doldurunuz.');</script>";
    } else {
        $stmt = $con->prepare("CALL UpdatePayment(?, ?, ?, ?, ?)");
        $stmt->bind_param("issid", $id, $cardno, $exp, $cvv, $price);

        if ($stmt->execute()) {
            echo "<script>alert('Ödeme başarıyla güncellendi.'); window.location.href='adminpayment.php';</script>";
        } else {
            echo "<script>alert('Güncelleme başarısız: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ödeme Güncelle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Roboto', sans-serif;
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
        <h2><i class="fas fa-credit-card"></i> Ödeme Güncelle</h2>
        <form method="POST">
            <label>Kart No</label>
            <input type="text" name="cardno" value="<?= htmlspecialchars($data['CARD_NO']) ?>" required>

            <label>Son Kullanma Tarihi</label>
            <input type="text" name="exp" value="<?= htmlspecialchars($data['EXP_DATE']) ?>" required>

            <label>CVV</label>
            <input type="text" name="cvv" value="<?= htmlspecialchars($data['CVV']) ?>" required>

            <label>Fiyat</label>
            <input type="text" name="price" value="<?= htmlspecialchars($data['PRICE']) ?>" required>

            <button type="submit" name="update">Güncelle</button>
        </form>
    </div>
</body>

</html>