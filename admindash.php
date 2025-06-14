<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
        }

        .add-button {
            background-color: #5d6d7e;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 18px;
            position: absolute;
            right: 60px;
            margin-top: -20px;
            margin-right: -30px;
        }

        .content-table {
            border-collapse: collapse;
            min-width: 800px;
            margin: 60px auto 0 auto;
            background-color: #fff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        .content-table thead tr {
            background-color: #5d6d7e;
            color: white;
        }

        .content-table th,
        .content-table td {
            padding: 16px 20px;
        }

        .content-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .content-table tbody tr:last-child {
            border-bottom: 2px solid #ff7200;
        }

        .delete-button {
            background-color: red;
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            width: 50px;
            height: 50px;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    $query = "SELECT * FROM feedback";
    $result = mysqli_query($con, $query);
    ?>

    <div class="navbar">
        <div class="logo">CaRs</div>
        <div class="menu">
            <ul>
                <li><a href="adminvehicle.php"><i class="fas fa-car"></i> Kiralık Araçlar</a></li>
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
        <h1 class="header">Geri Bildirim Tablosu</h1>
        <a href="addfeedback.php" class="add-button">Geri Bildirim Ekle</a>

        <table class="content-table">
            <thead>
                <tr>
                    <th>Geri bildirim ID</th>
                    <th>Email</th>
                    <th>Yorum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($res = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $res['FED_ID']; ?></td>
                        <td><?php echo $res['EMAIL']; ?></td>
                        <td><?php echo $res['COMMENT']; ?></td>
                        <td>
                            <a href="deletefeedback.php?delete=<?php echo $res['FED_ID']; ?>"
                                onclick="return confirm('Silmek istediğinize emin misiniz?');" class="delete-button">Sil</a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>