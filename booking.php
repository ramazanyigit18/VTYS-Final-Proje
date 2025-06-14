<?php
ob_start(); // Çıktı tamponlamasını başlat
session_start();
require_once('connection.php');

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$currentUserEmail = $_SESSION['email'];
// Kullanıcı bilgilerini al
$sql_user = "SELECT * FROM users WHERE EMAIL=?";
$stmt_user = mysqli_prepare($con, $sql_user);
mysqli_stmt_bind_param($stmt_user, "s", $currentUserEmail);
mysqli_stmt_execute($stmt_user);
$user_query_result = mysqli_stmt_get_result($stmt_user);
$user_row = mysqli_fetch_assoc($user_query_result);
mysqli_stmt_close($stmt_user);

if (!$user_row) {
    echo '<script>alert("Kullanıcı bilgileri bulunamadı."); window.location.href="index.php";</script>';
    exit();
}

$carid_get = $_GET['id'] ?? null;

if (!$carid_get) {
    echo '<script>alert("Geçersiz araç ID."); window.location.href="cardetails.php";</script>';
    exit();
}
$carid = (int) $carid_get; // ID'nin integer olduğundan emin olalım

// Araç bilgilerini al
$sql_car = "SELECT * FROM cars WHERE CAR_ID=?";
$stmt_car = mysqli_prepare($con, $sql_car);
mysqli_stmt_bind_param($stmt_car, "i", $carid);
mysqli_stmt_execute($stmt_car);
$car_query_result = mysqli_stmt_get_result($stmt_car);
$car_details_row = mysqli_fetch_assoc($car_query_result);
mysqli_stmt_close($stmt_car);

if (!$car_details_row) {
    echo '<script>alert("Araç bulunamadı."); window.location.href="cardetails.php";</script>';
    exit();
}

// Kullanıcı e-postası ve araç fiyatını değişkenlere ata
$uemail = $user_row['EMAIL']; // Bu users tablosundan gelen EMAIL, büyük harf.
$carprice = $car_details_row['PRICE'];

// Form gönderildi mi kontrol et
if (isset($_POST['book'])) {
    $bplace = trim($_POST['place']);
    $bdate_str = trim($_POST['date']);
    $dur_input = trim($_POST['dur']);
    $phno = trim($_POST['ph']);
    $des = trim($_POST['des']);
    $rdate_str = trim($_POST['rdate']);

    // Tarih formatlama
    $bdate = date('Y-m-d', strtotime($bdate_str));
    $rdate = date('Y-m-d', strtotime($rdate_str));

    // Alanların boş olup olmadığını kontrol et
    if (empty($bplace) || empty($bdate_str) || empty($phno) || empty($des) || empty($rdate_str)) {
        echo '<script>alert("Lütfen tüm alanları doldurun!")</script>';
    } else {
        // Veritabanınızda CheckDateRange fonksiyonu tanımlı olmalı
        $checkDateQuery = "SELECT CheckDateRange(?, ?) AS validDate";
        $stmt_check_date = mysqli_prepare($con, $checkDateQuery);
        mysqli_stmt_bind_param($stmt_check_date, "ss", $bdate, $rdate);
        mysqli_stmt_execute($stmt_check_date);
        $checkResult = mysqli_stmt_get_result($stmt_check_date);

        if (!$checkResult) {
            echo '<script>alert("Tarih kontrolü sırasında bir hata oluştu: ' . mysqli_error($con) . '")</script>';
        } else {
            $checkRow = mysqli_fetch_assoc($checkResult);
            mysqli_stmt_close($stmt_check_date);

            if ($checkRow && $checkRow['validDate']) {
                $dur = 0;
                // Eğer süre girilmediyse gün farkını hesapla
                // Veritabanınızda DaysBetweenDates fonksiyonu tanımlı olmalı
                if (empty($dur_input)) {
                    $durQuery = "SELECT DaysBetweenDates(?, ?) AS duration";
                    $stmt_dur = mysqli_prepare($con, $durQuery);
                    mysqli_stmt_bind_param($stmt_dur, "ss", $bdate, $rdate);
                    mysqli_stmt_execute($stmt_dur);
                    $durResult = mysqli_stmt_get_result($stmt_dur);
                    if ($durResult) {
                        $durRow = mysqli_fetch_assoc($durResult);
                        if ($durRow && isset($durRow['duration'])) { // duration var mı kontrol et
                            $dur = (int) $durRow['duration'];
                        } else {
                            echo '<script>alert("Kiralama süresi hesaplanamadı (gün farkı). Lütfen tarihleri kontrol edin.")</script>';
                            // Hata durumunda işlemi durdurabiliriz veya $dur = 0 olarak kalır.
                        }
                    } else {
                        echo '<script>alert("Süre hesaplama sorgusunda hata: ' . mysqli_error($con) . '")</script>';
                    }
                    if (isset($stmt_dur))
                        mysqli_stmt_close($stmt_dur); // stmt_dur her zaman tanımlı olmayabilir
                } else {
                    $dur = (int) $dur_input;
                }

                if ($dur <= 0 && empty($dur_input)) { // Eğer süre hesaplanamadıysa veya girilmediyse ve 0 ise
                    if (empty($dur_input))
                        echo '<script>alert("Kiralama süresi hesaplanamadı. Lütfen tarihleri veya süreyi kontrol edin.")</script>';
                    else
                        echo '<script>alert("Geçersiz kiralama süresi. Lütfen süreyi veya tarihleri kontrol edin.")</script>';
                } else if ($dur <= 0 && !empty($dur_input)) {
                    echo '<script>alert("Geçersiz kiralama süresi. Lütfen süreyi veya tarihleri kontrol edin.")</script>';
                } else { // $dur > 0 ise devam et
                    $price = $dur * $carprice;
                    $price_float = (float) $price;

                    $insert_sql = "INSERT INTO booking (CAR_ID, EMAIL, BOOK_PLACE, BOOK_DATE, DURATION, PHONE_NUMBER, DESTINATION, PRICE, RETURN_DATE)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_insert = mysqli_prepare($con, $insert_sql);

                    if ($stmt_insert) {
                        mysqli_stmt_bind_param($stmt_insert, "isssisids", $carid, $uemail, $bplace, $bdate, $dur, $phno, $des, $price_float, $rdate);

                        // **** YENİ EKLENEN VE DÜZENLENEN KISIM ****
                        if (mysqli_stmt_execute($stmt_insert)) {
                            $last_inserted_bid = mysqli_insert_id($con); // Son eklenen BOOK_ID'yi al
                            if ($last_inserted_bid) {
                                $_SESSION['bid'] = $last_inserted_bid; // BOOK_ID'yi session'a kaydet
                                // Başarı mesajını session'a kaydetmeye gerek yok, payment.php'ye gidilecek
                                header("Location: payment.php"); // payment.php'ye yönlendir
                                exit();
                            } else {
                                // Bu durum genellikle auto_increment birincil anahtar varsa pek oluşmaz ama kontrol etmek iyidir.
                                echo '<script>alert("Rezervasyon ID alınamadı. Kayıt başarılı ancak ödeme sayfasına yönlendirilemiyor. Yönetici ile iletişime geçin.")</script>';
                            }
                        } else {
                            echo '<script>alert("Rezervasyon kaydedilemedi: ' . htmlspecialchars(mysqli_stmt_error($stmt_insert)) . '\\nSQL: ' . htmlspecialchars($insert_sql) . '")</script>';
                        }
                        // **** YENİ EKLENEN VE DÜZENLENEN KISIM BİTTİ ****
                        mysqli_stmt_close($stmt_insert);
                    } else {
                        echo '<script>alert("Sorgu hazırlama hatası: ' . htmlspecialchars(mysqli_error($con)) . '")</script>';
                    }
                }
            } else {
                if (!$checkRow) {
                    echo '<script>alert("Tarih aralığı kontrol edilemedi. Veritabanı fonksiyonlarını (CheckDateRange) kontrol edin.")</script>';
                } else {
                    echo '<script>alert("Lütfen geçerli bir teslim tarihi giriniz (başlangıç tarihinden sonra olmalı).")</script>';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR BOOKING</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #a7a9ac, #c1dff0);
            font-family: 'Roboto', sans-serif;
            padding-top: 70px;
        }

        .navbar {
            width: 100%;
            height: 70px;
            background-color: #6E7B8B;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .logo h2 {
            font-family: Arial, sans-serif;
            font-size: 28px;
            color: #ff7200;
            margin-left: -30px;
        }

        .menu {
            list-style: none;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .menu li {
            margin-left: 40px;
        }

        .menu li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: flex;
            align-items: center;
            font-family: Arial, sans-serif;
            transition: 0.3s;
        }

        .menu li a i {
            margin-right: 8px;
            font-size: 20px;
        }

        .menu li a:hover {
            color: #ff7200;
        }

        div.main {
            width: 500px;
            margin: 50px auto 0px auto;
        }

        .car-info {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .car-info label {
            margin-right: 10px;
            font-style: normal;
        }

        .car-info span {
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: normal;
        }

        .btnn {
            width: 240px;
            height: 40px;
            background: #ff7200;
            border: none;
            margin-top: 30px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            color: #fff;
            transition: 0.4s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .btnn:hover {
            background-color: #e65a00;
        }

        h2.form-title {
            text-align: center;
            padding-bottom: 20px;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        div.register {
            background: rgba(240, 248, 255, 0.8);
            width: 100%;
            font-size: 18px;
            border-radius: 15px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            color: #333;
            padding: 30px;
        }

        form#register {
            margin: 0;
        }

        label {
            font-family: 'Arial', sans-serif;
            font-size: 18px;
            font-weight: bold;
            color: #444;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="tel"] {
            width: 100%;
            border: 1px solid #aaa;
            border-radius: 5px;
            outline: 0;
            padding: 10px;
            background-color: #fff;
            box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <h2>CaRs</h2>
        </div>
        <ul class="menu">
            <li><a href="cardetails.php"><i class="fas fa-home"></i> Anasayfa</a></li>
            <li><a href="aboutus2.html"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
            <li><a href="contactus2.html"><i class="fas fa-envelope"></i> İletişim</a></li>
            <li><a href="admindash.php"><i class="fas fa-comment-dots"></i> Geri Bildirim</a></li>
            <li><a href="adminlogin.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a></li>
        </ul>
    </div>
    <div class="main">
        <div class="register">
            <h2 class="form-title">Araç Kiralama Formu</h2>
            <form id="register" method="POST" action="booking.php?id=<?php echo htmlspecialchars($carid); ?>">
                <div class="car-info">
                    <label>CAR NAME:</label>
                    <span><?php echo htmlspecialchars($car_details_row['CAR_NAME']); ?></span>
                </div>
                <label for="place">Rezervasyon Yeri:</label>
                <input type="text" name="place" id="place" required><br>
                <label for="datefield">Rezervasyon Tarihi:</label>
                <input type="date" name="date" id="datefield" required><br>
                <label for="dur">Rezervasyon Süresi (gün):</label>
                <input type="number" name="dur" min="1" max="30" id="dur"
                    placeholder="Boş bırakırsanız tarihlerden hesaplanır"><br>
                <label for="ph">Telefon Numarası:</label>
                <input type="tel" name="ph" maxlength="10" id="ph" pattern="[0-9]{10}"
                    title="Lütfen 10 haneli telefon numaranızı girin (örn: 5xxxxxxxxx)" required><br>
                <label for="des">Araç Teslim Noktası:</label>
                <input type="text" name="des" id="des" required><br>
                <label for="dfield">Araç Teslim Tarihi:</label>
                <input type="date" name="rdate" id="dfield" required><br>
                <input type="submit" class="btnn" value="Kirala" name="book">
            </form>
        </div>
    </div>
    <script>
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        var minDate = yyyy + '-' + mm + '-' + dd;
        document.getElementById("datefield").setAttribute("min", minDate);
        document.getElementById("dfield").setAttribute("min", minDate);
        document.getElementById("datefield").addEventListener("change", function () {
            var startDate = this.value;
            document.getElementById("dfield").setAttribute("min", startDate);
            if (document.getElementById("dfield").value < startDate) {
                document.getElementById("dfield").value = startDate;
            }
        });
        document.getElementById("dfield").addEventListener("change", function () {
            var endDate = this.value;
            var startDate = document.getElementById("datefield").value;
            if (startDate && endDate < startDate) {
                alert("Teslim tarihi, rezervasyon tarihinden önce olamaz.");
                this.value = startDate;
            }
        });
    </script>
</body>

</html>
<?php
ob_end_flush(); // Tamponu gönder ve kapat
?>