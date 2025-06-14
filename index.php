<?php
require_once('connection.php');
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    if (empty($email) || empty($pass)) {
        echo '<script>alert("Lütfen tüm alanları doldurun.")</script>';
    } else {
        $query = "SELECT * FROM users WHERE EMAIL='$email'";
        $res = mysqli_query($con, $query);

        if ($row = mysqli_fetch_assoc($res)) {
            $db_password = $row['PASSWORD'];
            if (md5($pass) == $db_password) {
                $_SESSION['email'] = $email;
                header("Location: cardetails.php");
                exit(); // yönlendirmeden sonra kodu durdur
            } else {
                echo '<script>alert("Şifre yanlış.")</script>';
            }
        } else {
            echo '<script>alert("E-posta bulunamadı.")</script>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Araba Kiralama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('images/homepageimg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px 50px;
            width: 100%;
        }

        .navbar a {
            color: white !important;
            font-weight: 500;
        }

        .navbar i {
            margin-right: 5px;
        }

        .hero-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            padding: 90px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
        }

        .hero-text h1 {
            font-size: 50px;
        }

        .hero-text a {
            text-decoration: none;
        }

        .hero-text p {
            font-size: 20px;
            margin-top: 10px;
        }

        .hero-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 28px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background-color: #ff7200;
            text-decoration: none;
            border: 2px solid transparent;
            border-radius: 6px;
            transition: all 0.3s ease;

        }





        .hero-btn:hover {
            color: white;
            border: 2px solid #ff7200;
            transform: translateY(-2px);
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            color: #ff7200;
            text-align: center;

        }

        .form-box h3 {
            margin-bottom: 20px;
            color: #ff7200;
        }

        .slider,
        .cars,
        .about-faq,
        .team,
        .footer {
            padding: 60px 30px;
            background-color: #f9f9f9;
        }

        .card:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }

        .faq .card-header button {
            text-align: left;
            width: 100%;
        }

        .faq-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            overflow: hidden;
            max-height: 80px;
            position: relative;
            margin-bottom: 20px;
        }

        .faq-card.open {
            max-height: 300px;
        }

        .faq-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question {
            font-weight: bold;
            font-size: 18px;
        }

        .faq-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .faq-card.open .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            font-size: 16px;
            margin-top: 15px;
            color: #333;
            display: none;
        }

        .faq-card.open .faq-answer {
            display: block;
        }

        .brand-logo {
            width: 250px;
            height: auto;
            object-fit: contain;
        }

        .custom-footer {
            background-color: #1a1a1a;
            color: #fff;
            padding: 40px 20px;
            font-family: 'Segoe UI', sans-serif;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            max-width: 1200px;
        }

        .footer-left,
        .footer-center,
        .footer-right {
            flex: 1;
            min-width: 250px;

        }

        .footer-center ul {
            display: flex;
            flex-direction: space-between;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-left: -60px;
            margin-top: -20px;

        }

        .footer-right {
            margin-left: 80px;
        }

        .footer-right ul {
            display: flex;
            flex-direction: space-between;
            gap: 30px;
            align-items: center;
            justify-content: center;
            margin-left: -60px;
            margin-top: -20px;

        }

        .footer-left h4 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #ff7200;
        }

        .footer-center h5,
        .footer-right h5 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #ff7200;
        }

        .footer-center ul,
        .footer-right ul {
            list-style: none;
            padding: 0;
        }

        .footer-center li,
        .footer-right li {
            margin-bottom: 10px;
        }

        .footer-center a,
        .footer-right a {
            color: #ccc;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .footer-center a:hover,
        .footer-right a:hover {
            color: #fff;
            transform: translateX(6px);
        }

        .btn-warning {
            background-color: #ff7200 !important;
            border-color: #ff7200 !important;
        }



        /* Responsive */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
            }

            .footer-left,
            .footer-center,
            .footer-right {
                margin-bottom: 30px;
            }
        }
    </style>

    <script>
        function toggleAnswer(card) {
            const cardDiv = card.querySelector('.faq-card');
            cardDiv.classList.toggle('open');
        }
    </script>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#" style="scale: 1.2;"><i class="fas fa-car"></i> CaRs</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#">ANA SAYFA</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.html">HAKKIMIZDA</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.html">İLETİŞİM</a></li>
                <li class="nav-item"><a class="btn btn-warning"
                        style="margin-bottom: 15px; height: 40px; width: 160px; display: flex; flex-direction: row; padding-top: 10px;"
                        href="adminlogin.php">YÖNETİCİ GİRİŞİ</a></li>
            </ul>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section">
        <div class="hero-text">
            <h1>Hayalindeki Araba Sadece</h1>
            <h1>Bir Tık Uzağında</h1>
            <p>Güvenli, konforlu ve hızlı araba kiralama deneyimi için hemen kaydol!</p>

            <a href="register.php" class="hero-btn">Hemen Sahip Ol</a>
        </div>

        <div class="form-box">
            <h3>Giriş Yap</h3>

            <form method="POST">
                <input type="email" name="email" class="form-control mb-3" placeholder="E-posta" required>
                <input type="password" name="pass" class="form-control mb-3" placeholder="Şifre" required>
                <input type="submit" name="login" class="btn btn-warning btn-block" value="Giriş Yap">
            </form>

            <p style="font-size: 14px; margin-bottom: 15px; margin-top: 20px; display:flex; flex-direction: column;">
                Hesabın yok mu? <a href="register.php" style="color: #ff7200; text-decoration: underline;">Kayıt Ol</a>
            </p>
        </div>
    </section>

    <!-- LOGO SLIDER -->
    <section class="slider text-center">
        <h2>Markalarımız</h2>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
            <img src="images/PorscheLogo.png" alt="Logo1" class="brand-logo m-3">
            <img src="images/NissanLogo.png" alt="Logo2" class="brand-logo m-3">
            <img src="images/SuzukiLogo.png" alt="Logo3" class="brand-logo m-3">
            <img src="images/BmwLogo.png" alt="Logo4" class="brand-logo m-3">
        </div>
    </section>

    <!-- ARAÇ KARTLARI SLIDER -->
    <section class="cars text-center">
        <h2 style="margin-bottom: 20px;">Popüler Araçlar</h2>
        <div class="d-flex justify-content-around">
            <div class="card" style="width: 25rem; border-radius: 15px;">
                <img src="images/BMW-320.png" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">BMW 320i</h5>
                    <p class="card-text">Konforlu ve şık sürüş deneyimi.</p>
                </div>
            </div>
            <div class="card" style="width: 25rem; border-radius: 15px;">
                <img src="images/Audi-A3.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Audi A3</h5>
                    <p class="card-text">Güçlü performans, üst düzey güvenlik.</p>
                </div>
            </div>
            <div class="card" style="width: 25rem; border-radius: 15px;">
                <img src="images/Mercedesc.png" class="card-img-top" style="scale:1.3;">
                <div class="card-body">
                    <h5 class="card-title">Mercedes Benz</h5>
                    <p class="card-text">Güçlü, hızlı ve dinamik</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sales-policy-section py-5" style="background-color: #ffffff;">
        <div class="container">
            <!-- Satış Politikası -->
            <div class="row align-items-center">
                <div class="col-md-6" style="margin-top: -120px;">
                    <h2 class="mb-4">Satış Politikamız</h2>
                    <p style="font-size: 18px; line-height: 1.6;">
                        Şeffaflık, güven ve müşteri memnuniyeti ilkelerimizle araç kiralama sürecini kolaylaştırıyoruz.
                        Tüm işlemler yasal sözleşmelerle güvence altına alınır ve kiralama öncesi-sonrası profesyonel
                        destek sunulur.
                        Temiz, bakımlı ve güvenli araçlarımızla size sorunsuz bir deneyim vaat ediyoruz.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <div class="position-relative">
                        <img src="images/agree.jpg" class="img-fluid rounded shadow"
                            style="max-height: 400px; object-fit: cover; margin-left: 80px; border-radius: 40px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="customer-satisfaction-section py-5" style="background-color: #ffffff;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Sol: Görsel -->
                <div class="col-md-6 text-center">
                    <img src="images/pleasure.jpg" class="img-fluid rounded shadow"
                        style="max-height: 400px; object-fit: cover; border-radius: 40px; width: 600px;">
                </div>

                <!-- Sağ: Metin -->
                <div class="col-md-6">
                    <h2 class="mb-4" style="margin-left: 70px;">Müşteri Memnuniyeti</h2>
                    <p style="font-size: 18px; line-height: 1.6; margin-left: 70px;">
                        Müşteri memnuniyetini temel ilke edinen firmamız, her adımda kullanıcı odaklı çözümler sunar.
                        Kiralama sürecinde karşılaşabileceğiniz her türlü sorunda 7/24 destek hizmeti sağlıyoruz.
                        Araçlarımızın teslimatından iadesine kadar tüm işlemler, müşteri konforu ve güvenliği
                        düşünülerek planlanmıştır.
                        Sürekli gelişen hizmet anlayışımızla, kullanıcılarımızın beklentilerinin ötesine geçmeyi
                        hedefliyoruz.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="custom-footer">
        <div class="footer-container">
            <!-- Sol: Marka ve telif -->
            <div class="footer-left">
                <h4>CaRs</h4>
                <p>&copy; 2025 CaRs. Tüm hakları saklıdır.</p>
            </div>

            <!-- Orta: Menü Linkleri -->
            <div class="footer-center">
                <h5>Hızlı Erişim</h5>
                <ul>
                    <li><a href="#">Anasayfa</a></li>
                    <li><a href="cardetails.php">Araçlar</a></li>
                    <li><a href="aboutus.html">Hakkımızda</a></li>

                </ul>
            </div>

            <!-- Sağ: Ekstra bilgi veya sosyal -->
            <div class="footer-right">
                <h5>Bizi Takip Edin</h5>
                <ul class="social">
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>

</html>