<?php
require_once("connection.php");

// Kullanıcı sayısını stored function ile çekiyoruz
$count_query = mysqli_query($con, "SELECT GetTotalUsers() AS total_users");
$row = mysqli_fetch_assoc($count_query);
$total_users = $row['total_users'];
mysqli_next_result($con); // Sonraki prosedür sorguları için temizleme
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>

    <!-- Font Awesome CDN -->
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
            height: 75px;
            background-color: #6E7B8B;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
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

        .menu ul li a:hover {
            color: #ff7200;
        }

        .menu ul li a i {
            margin-right: 8px;
            font-size: 18px;
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

        h1.header {
            text-align: center;
            margin-bottom: 30px;
            color: #222;
            font-size: 36px;
            font-weight: bold;
        }

        .content-table {
            border-collapse: collapse;
            font-size: 16px;
            min-width: 800px;
            border-radius: 12px;
            overflow: hidden;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .content-table thead tr {
            background-color: #5d6d7e;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .content-table th,
        .content-table td {
            padding: 16px 20px;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
            transition: background-color 0.3s ease;
        }

        .content-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #ff7200;
        }

        .delete-button {
            background-color: #5d6d7e;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 18px;
            border-radius: 10px;
            height: 40px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-right: 240px;
            width: 200px;
            text-align: center;
        }

        .delete-button2 {
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 80px;
            text-align: center;
            margin-right: 40px;

        }

        .delete-button3 {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-right: 40px;
            width: 80px;
            text-align: center;
        }
    </style>

</head>

<body>

    <div class="navbar">
        <div class="logo">CaRs</div>
        <div class="menu">
            <ul>
                <li><a href="adminvehicle.php"><i class="fas fa-car"></i> Kiralık Araçlar</a></li>
                <li><a href="adminusers.php"><i class="fas fa-users"></i> Müşteriler</a></li>
                <li><a href="admindash.php"><i class="fas fa-comment-dots"></i> Geri Bildirim</a></li>
                <li><a href="adminbook.php"><i class="fas fa-calendar-check"></i> Kiralamalar</a></li>
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
        <h1 class="header">Müşteriler Tablosu</h1>

        <!-- ✅ Toplam kullanıcı sayısı gösterimi -->
        <div style="text-align:right; margin: 10px 20px 0 0; font-size: 18px; font-weight: bold;">
            Toplam Kayıtlı Kullanıcı: <?php echo $total_users; ?>
        </div>

        <?php
        // Kullanıcıları listelemek için prosedür
        $queryy = mysqli_query($con, "CALL read_users()");
        if (!$queryy) {
            die("read_users prosedürü çalıştırılamadı: " . mysqli_error($con));
        }
        mysqli_next_result($con);
        ?>

        <div style="text-align: right; margin-bottom: 15px; margin-right: 50px;">
            <a href="adduser.php" class="delete-button">Müşteri Ekle</a>
        </div>

        <table class="content-table">
            <thead>
                <tr>
                    <th>İsim</th>
                    <th>EMAIL</th>
                    <th>Telefon Numarası</th>
                    <th>Cinsiyet</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($res = mysqli_fetch_array($queryy)) { ?>
                    <tr>
                        <td><?php echo $res['FNAME'] . " " . $res['LNAME']; ?></td>
                        <td><?php echo $res['EMAIL']; ?></td>
                        <td><?php echo $res['PHONE_NUMBER']; ?></td>
                        <td><?php echo $res['GENDER']; ?></td>
                        <td>
                            <a href="edituser.php?email=<?php echo $res['EMAIL']; ?>" class="delete-button2"
                                style="background-color: #007bff;">Düzenle</a>
                            <a href="deleteuser.php?email=<?php echo urlencode($res['EMAIL']); ?>" class="delete-button3"
                                onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>