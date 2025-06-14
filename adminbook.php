<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>

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
            width: 100%;
            text-align: left;
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
            background-color: #ff7200;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            color: white;
        }

        .action-btn a {
            text-decoration: none;
            color: white;
        }

        .action-btn:hover {
            background-color: darkorange;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM booking ORDER BY BOOK_ID DESC";
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

    <div class="main-content">
        <h1 class="header">Rezervasyon Talebi Tablosu</h1>

        <div style="display: flex; justify-content: flex-end; margin: 10px 20px;">
            <a href="add_booking.php" style="text-decoration: none;">
                <button class="action-btn" style="background-color: #5d6d7e; height: 50px; width: 170px; font-size: 16px;" > Rezervasyon Ekle</button>
            </a>
        </div>

        <table class="content-table">
            <thead>
                <tr>
                    <th>Kiralık Araç ID</th>
                    <th>EMAIL</th>
                    <th>Rezervasyon Yeri</th>
                    <th>Rezervasyon Tarihi</th>
                    <th>Telefon Numarası</th>
                    <th>Araç Teslim Noktası</th>
                    <th>Araç Teslim Tarihi</th>
                    <th>Kiralık Araç Durumu</th>
                    <th>Onayla</th>
                    <th>İptal</th>
                    <th>Düzenle</th> <!-- Yeni sütun -->
                </tr>
            </thead>
            <tbody>
                <?php while ($res = mysqli_fetch_array($queryy)) { ?>
                    <tr>
                        <td><?php echo $res['CAR_ID']; ?></td>
                        <td><?php echo $res['EMAIL']; ?></td>
                        <td><?php echo $res['BOOK_PLACE']; ?></td>
                        <td><?php echo $res['BOOK_DATE']; ?></td>
                        <td><?php echo $res['PHONE_NUMBER']; ?></td>
                        <td><?php echo $res['DESTINATION']; ?></td>
                        <td><?php echo $res['RETURN_DATE']; ?></td>
                        <td><?php echo $res['BOOK_STATUS']; ?></td>
                        <td>
                            <button class="action-btn">
                                <a href="approve.php?id=<?php echo $res['BOOK_ID']; ?>">Onayla</a>
                            </button>
                        </td>
                        <td>
                            <button class="action-btn" style="background-color:red;">
                                <a
                                    href="adminreturn.php?id=<?php echo $res['CAR_ID']; ?>&bookid=<?php echo $res['BOOK_ID']; ?>">İptal</a>
                            </button>
                        </td>
                        <td>
                            <button class="action-btn" style="background-color: #007bff;">
                                <a href="edit_booking.php?bookid=<?php echo $res['BOOK_ID']; ?>">Düzenle</a>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</body>

</html>