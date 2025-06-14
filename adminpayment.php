<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM payment ORDER BY PAY_ID DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ödeme Kayıtları</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            background-color: #6E7B8B;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            height: 75px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ff7200;
        }

        .menu ul {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .menu ul li {
            margin-left: 30px;
        }

        .menu ul li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .menu ul li a:hover {
            color: #ff7200;
        }

        .menu ul li a i {
            margin-right: 8px;
        }

        .logout-btn {
            background-color: red;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: darkred;
        }

        .logout-btn i {
            margin-right: 5px;
        }

        .main-content {
            margin-top: 120px;
            padding: 20px;
        }

        h2.header {
            text-align: center;
            color: #222;
            font-size: 36px;
            font-weight: bold;
        }

        .content-table {
            border-collapse: collapse;
            font-size: 0.95em;
            min-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .content-table thead tr {
            background-color: #6E7B8B;
            color: white;
            font-weight: bold;
        }

        .content-table th,
        .content-table td {
            padding: 14px 18px;
            text-align: center;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
            transition: background-color 0.3s;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid orange;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .edit-btn {
            background-color: #007bff;
        }

        .delete-btn {
            background-color: red;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        .add-btn-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .add-btn {
            padding: 10px 20px;
            background-color: #5d6d7e;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 40px;
        }

        .add-btn:hover {
            background-color: #3e4b56;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">CaRs</div>
        <div class="menu">
            <ul>
                <li><a href="adminvehicle.php"><i class="fas fa-car"></i> Kiralık Araç Yönetimi</a></li>
                <li><a href="adminusers.php"><i class="fas fa-users"></i> Müşteriler</a></li>
                <li><a href="admindash.php"><i class="fas fa-comment-dots"></i> Geri Bildirim</a></li>
                <li><a href="adminbook.php"><i class="fas fa-calendar-check"></i> Rezervasyon Talebi</a></li>
                <li><a href="adminpayment.php"><i class="fa-regular fa-credit-card"></i> Ödemeler</a></li>
                <li>
                    <form action="index.php" method="post" style="margin:0;">
                        <button type="submit" class="logout-btn"><i
                                class="fa-solid fa-right-from-bracket"></i>Çıkış</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">

        <h2 class="header">Ödeme Listesi Tablosu</h2>

        <div class="add-btn-wrapper">
            <a class="add-btn" href="payment.php">Ödeme Ekle</a>
        </div>

        <table class="content-table">
            <thead>
                <tr>
                    <th>Ödeme ID</th>
                    <th>Rezervasyon ID</th>
                    <th>Kart No</th>
                    <th>Son Kullanma</th>
                    <th>CVV</th>
                    <th>Fiyat</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['PAY_ID']; ?></td>
                        <td><?= $row['BOOK_ID']; ?></td>
                        <td><?= $row['CARD_NO']; ?></td>
                        <td><?= $row['EXP_DATE']; ?></td>
                        <td><?= $row['CVV']; ?></td>
                        <td><?= $row['PRICE']; ?> TL</td>
                        <td>
                            <a href="edit_payment.php?id=<?= $row['PAY_ID']; ?>" class="action-btn edit-btn">Düzenle</a>
                            <a href="delete_payment.php?id=<?= $row['PAY_ID']; ?>" class="action-btn delete-btn"
                                onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>