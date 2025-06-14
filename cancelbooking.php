<?php
session_start();
require_once('connection.php');

// Eğer kullanıcı login değilse, zorla index.php'ye gönder
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$value = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE EMAIL='$value'";
$name = mysqli_query($con, $sql);
$rows = mysqli_fetch_assoc($name);

$sql2 = "SELECT * FROM cars WHERE AVAILABLE='Y'";
$cars = mysqli_query($con, $sql2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon İptali</title>
    <style>
        body {
            background: linear-gradient(135deg, #D6EFFF, #ffffff);
            font-family: 'Roboto', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .form {
            background: #fff;
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .form h1 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .hai,
        .no {
            width: 220px;
            height: 50px;
            border: none;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            margin: 10px;
            transition: background 0.3s ease;
        }

        .hai {
            background: #E74C3C;
            /* kırmızı */
        }

        .hai:hover {
            background: #C0392B;
        }

        .no {
            background: #ff7200;
            /* turuncu */
        }

        .no:hover {
            background: #e06400;
        }

        .no a {
            text-decoration: none;
            color: white;
            display: block;
            width: 100%;
            height: 100%;
            line-height: 50px;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_POST['cancelnow'])) {
        $bid = $_SESSION['bid'];
        $del = mysqli_query($con, "DELETE FROM booking WHERE BOOK_ID = '$bid' LIMIT 1");

        echo "<script>window.location.href='cardetails.php';</script>";
    }
    ?>

    <form class="form" method="POST">
        <h1>Rezervasyonunuzu iptal etmek istediğinize emin misiniz?</h1>
        <input type="submit" class="hai" value="İPTAL ET" name="cancelnow">
        <button class="no"><a href="payment.php">ÖDEMEYE GİT</a></button>
    </form>

</body>

</html>