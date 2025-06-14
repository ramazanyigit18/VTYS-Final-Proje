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
    <title>Car Details</title>

</head>

<body class="body">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #6E7B8B, #E0F7FA);
            font-family: 'Roboto', sans-serif;

            min-height: 100vh;
        }

        header {
            width: 100%;
            background-color: #6E7B8B;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header .logo {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        nav ul {
            display: flex;
            list-style: none;
            align-items: center;
        }

        nav ul li {
            margin-left: 30px;
            display: flex;
            align-items: center;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #ff7200;
            /* Hover rengi buz mavisi tonunda */
        }

        nav ul li i {
            margin-right: 8px;
        }

        /* Ana içerik */
        .main {
            padding-top: 140px;
            padding-left: 50px;
            padding-right: 50px;
        }

        .overview {
            text-align: center;
            color: #222;
            margin-bottom: 40px;
            font-size: 36px;
            font-weight: bold;
        }

        .de {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .box {
            background: #f0f0f0;
            /* Açık gri kutu arka planı */
            border: 1px solid #ccc;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 100px;
            margin-left: 100px;
            width: 400px;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
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

        .imgBx img {
            width: 90%;
            height: 250px;
            object-fit: cover;
            margin: 15px auto;
            border-radius: 15px;
            display: block;
        }

        .content {
            padding: 20px;
            background: #e6f7ff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 180px;
            position: relative;
        }

        .content h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .content h2 {
            font-size: 16px;
            color: #666;
            margin: 4px 0;
            font-weight: normal;
        }

        .book-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: #ff7200;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .book-button:hover {
            background-color: #ff7200;
        }
    </style>


    <?php



    $value = $_SESSION['email'];
    $_SESSION['email'] = $value;

    $sql = "select * from users where EMAIL='$value'";
    $name = mysqli_query($con, $sql);
    $rows = mysqli_fetch_assoc($name);
    $sql2 = "select *from cars where AVAILABLE='Y'";
    $cars = mysqli_query($con, $sql2);

    // $row=mysqli_fetch_assoc($cars);
    
    

    ?>

    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <header>
        <div class="logo">CaRs</div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Anasayfa</a></li>
                <li><a href="aboutus2.html"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                <li><a href="contactus2.html"><i class="fas fa-envelope"></i> İletişim</a></li>
                <li><a href="feedback.html"><i class="fas fa-comment-dots"></i> Geri Bildirim</a></li>
                <li>
                    <form action="index.php" method="post" style="margin:0;">
                        <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i>Çıkış</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <div>


        <ul class="de">
            <?php
            while ($result = mysqli_fetch_array($cars)) {
                $res = $result['CAR_ID'];
                ?>
                <div class="box">
                    <div class="imgBx">
                        <img src="images/<?php echo $result['CAR_IMG'] ?>" alt="<?php echo $result['CAR_NAME']; ?>">
                    </div>
                    <div class="content">
                        <h1><?php echo $result['CAR_NAME']; ?></h1>
                        <h2>Yakıt Tipi: <?php echo $result['FUEL_TYPE']; ?></h2>
                        <h2>Durum: <?php echo ($result['AVAILABLE'] == 'Y') ? 'Kiralık' : 'Bakımda'; ?></h2>
                        <h2>Günlük Kiralama Fiyatı: <?php echo $result['PRICE']; ?> TL</h2>
                        <a href="booking.php?id=<?php echo $res; ?>" class="book-button">Kirala</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </ul>
    </div>
    </div>
    </div>






</body>

</html>