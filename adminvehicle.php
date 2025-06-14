<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #6E7B8B, #E0F7FA);
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            width: 100%;
            height: 75px;
            background-color: #6E7B8B;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-size: 30px;
            color: #ff7200;
            font-weight: bold;
        }

        .menu ul {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .menu ul li {
            margin-left: 35px;
            display: flex;
            align-items: center;
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

        .menu ul li a i {
            margin-right: 8px;
            font-size: 18px;
        }

        .menu ul li a:hover {
            color: #ff7200;
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

        .header {
            text-align: center;
            margin: 30px 0;
            font-size: 36px;
            color: #333;
        }

        .add {
            display: block;
            margin: 0 auto 20px;
            background-color: #5d6d7e;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            width: 200px;
            float: right;
            margin-right: 80px;
        }

        .content-table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .content-table thead tr {
            background-color: #6E7B8B;
            color: #ffffff;
            text-align: center;
        }

        .content-table th,
        .content-table td {
            padding: 15px;
            text-align: center;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #6E7B8B;
        }

        .but {
            width: 90px;
            height: 35px;
            border-radius: 8px;
            background-color: red;
            transition: background 0.3s;
            font-weight: bold;
            border: none;
        }

        .but-edit {
            background-color: #007bff;
            width: 90px;
            height: 35px;
            border-radius: 8px;
            background-color: red;
            transition: background 0.3s;
            font-weight: bold;
            border: none;
        }

        .but-edit a {
            text-decoration: none;
            color: white;
            border: none;
        }

        .but a {
            text-decoration: none;
            color: white;
            border: none;
        }

        .but:hover {
            color: white;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM cars";
    $queryy = mysqli_query($con, $query);
    ?>

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

    <h1 class="header">Kiralık Araçlar Tablosu</h1>
    <a class="add" href="addcar.php">Kiralık Araç Ekle</a>

    <table class="content-table">
        <thead>
            <tr>
                <th>Araç ID</th>
                <th>Araç Markası</th>
                <th>Yakıt Tipi</th>
                <th>Fiyat</th>
                <th>Müsaitlik</th>
                <th>Sil</th>
                <th>Düzenle</th> <!-- Yeni sütun -->
            </tr>
        </thead>
        <tbody>
            <?php while ($res = mysqli_fetch_array($queryy)) { ?>
                <tr>
                    <td><?php echo $res['CAR_ID']; ?></td>
                    <td><?php echo $res['CAR_NAME']; ?></td>
                    <td><?php echo $res['FUEL_TYPE']; ?></td>
                    <td><?php echo $res['PRICE']; ?></td>
                    <td><?php echo ($res['AVAILABLE'] == 'Y') ? 'Kiralık' : 'Bakımda'; ?></td>
                    <td> <!-- Yeni: Düzenle butonu -->
                        <button class="but-edit" style="background-color: #007bff;">
                            <a href="addcar.php?edit=<?php echo $res['CAR_ID']; ?>">Düzenle</a>
                        </button>
                    </td>
                    <td>
                        <button class="but">
                            <a href="deletecar.php?delete=<?= $res['CAR_ID'] ?>"
                                onclick="return confirm('Bu aracı silmek istediğinize emin misiniz?');">Sil</a>
                        </button>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    // En ucuz araç
    $cheapest = mysqli_query($con, "SELECT GetCheapestCar() AS cheapest");
    $cheap = mysqli_fetch_assoc($cheapest)['cheapest'];

    // En pahalı araç
    $expensive = mysqli_query($con, "SELECT GetMostExpensiveCar() AS expensive");
    $exp = mysqli_fetch_assoc($expensive)['expensive'];

    // Yakıt türlerine göre sayılar
    $diesel_count = mysqli_query($con, "SELECT FuelTypeCount('Dizel') AS count");
    $petrol_count = mysqli_query($con, "SELECT FuelTypeCount('Benzin') AS count");
    $electric_count = mysqli_query($con, "SELECT FuelTypeCount('Elektrik') AS count");

    $dizel = mysqli_fetch_assoc($diesel_count)['count'];
    $benzin = mysqli_fetch_assoc($petrol_count)['count'];
    $elektrik = mysqli_fetch_assoc($electric_count)['count'];
    ?>

    <div
        style="width:90%; margin:40px auto; background-color:#fefefe; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
        <h2 style="margin-bottom:30px; color:#2c3e50; text-align:center;">
            <i class="fas fa-chart-bar"></i> Araç Fiyat ve Yakıt Analizi
        </h2>

        <table style="width:100%; border-collapse:collapse; font-size:16px;">
            <thead>
                <tr style="background-color:#6E7B8B; color:white;">
                    <th style="padding:14px; border-radius:8px 0 0 8px;">Bilgi Türü</th>
                    <th style="padding:14px; border-radius:0 8px 8px 0;">Değer</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color:#ffffff;">
                    <td style="padding:12px;"><i class="fas fa-car-side" style="color:#27ae60;"></i> En Ucuz Araç</td>
                    <td style="padding:12px;"><?= $cheap ?></td>
                </tr>
                <tr style="background-color:#f8f9fa;">
                    <td style="padding:12px;"><i class="fas fa-car" style="color:#e74c3c;"></i> En Pahalı Araç</td>
                    <td style="padding:12px;"><?= $exp ?></td>
                </tr>
                <tr style="background-color:#ffffff;">
                    <td style="padding:12px;"><i class="fas fa-gas-pump" style="color:#2980b9;"></i> Dizel Araç Sayısı
                    </td>
                    <td style="padding:12px;"><?= $dizel ?></td>
                </tr>
                <tr style="background-color:#f8f9fa;">
                    <td style="padding:12px;"><i class="fas fa-gas-pump" style="color:#d35400;"></i> Benzinli Araç
                        Sayısı</td>
                    <td style="padding:12px;"><?= $benzin ?></td>
                </tr>
                <tr style="background-color:#ffffff;">
                    <td style="padding:12px;"><i class="fas fa-bolt" style="color:#f1c40f;"></i> Elektrikli Araç Sayısı
                    </td>
                    <td style="padding:12px;"><?= $elektrik ?></td>
                </tr>
            </tbody>
        </table>
    </div>




</body>

</html>