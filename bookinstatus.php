<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKING STATUS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #E0F7FA, #6E7B8B);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 120px;
            color: #333;
        }

        .top-bar {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .top-bar .home-btn {
            padding: 10px 20px;
            background-color: #ff7200;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            margin-top: 50px;
        }

        .top-bar .home-btn:hover {
            background-color: #ff7200;
        }

        .welcome {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.25);
            padding: 10px 20px;
            border-radius: 10px;
        }

        .box {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
        }

        .box h1 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        .highlight {
            font-weight: bold;
            color: #6E7B8B;
        }
    </style>
</head>
<body>

<?php
require_once('connection.php');
session_start();
$email = $_SESSION['email'];

$sql = "SELECT * FROM booking WHERE EMAIL='$email' ORDER BY BOOK_ID DESC LIMIT 1";
$name = mysqli_query($con, $sql);
$rows = mysqli_fetch_assoc($name);

if ($rows == null) {
    echo '<script>alert("THERE ARE NO BOOKING DETAILS")</script>';
    echo '<script> window.location.href = "cardetails.php";</script>';
} else {
    $sql2 = "SELECT * FROM users WHERE EMAIL='$email'";
    $name2 = mysqli_query($con, $sql2);
    $rows2 = mysqli_fetch_assoc($name2);
    $car_id = $rows['CAR_ID'];
    $sql3 = "SELECT * FROM cars WHERE CAR_ID='$car_id'";
    $name3 = mysqli_query($con, $sql3);
    $rows3 = mysqli_fetch_assoc($name3);
?>

    <div class="top-bar">
        <a href="cardetails.php" class="home-btn">Anasayfa</a>
    </div>

    <div class="welcome">
        Hoşgeldin <?php echo $rows2['FNAME'] . " " . $rows2['LNAME']; ?>
    </div>

    <div class="box">
        <h1>Araç Markası: <span class="highlight"><?php echo $rows3['CAR_NAME']; ?></span></h1>
        <h1>Kiralama Gün Sayısı: <span class="highlight"><?php echo $rows['DURATION']; ?></span></h1>
        <h1>Araç Durumu: <span class="highlight"><?php echo $rows['BOOK_STATUS']; ?></span></h1>
    </div>

<?php } ?>

</body>
</html>
