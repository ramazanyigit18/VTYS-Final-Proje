<?php
session_start();
require_once('connection.php');

// Eğer kullanıcı login değilse, index.php'ye gönder
if (!isset($_SESSION['email'])) {
  header("Location: index.php");
  exit();
}

$value = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE EMAIL='$value'"; // Bu sorguyu prepared statement yapmanız önerilir
$name = mysqli_query($con, $sql);
$rows = mysqli_fetch_assoc($name);

// Kullanıcıya ait son booking bul (BOOK_ID session'dan gelmeli)
// $bid değişkeninin booking.php'den doğru şekilde payment.php'ye aktarıldığından emin olun.
// Genellikle bir önceki sayfadan $_SESSION['bid'] olarak ayarlanır.
if (!isset($_SESSION['bid'])) {
  echo '<script>alert("Rezervasyon ID bulunamadı. Lütfen rezervasyon işlemini tekrar yapın."); window.location.href="cardetails.php";</script>';
  exit();
}
$bid = $_SESSION['bid']; // Session'dan BOOK_ID alınıyor

// Sadece fiyatı göstermek için booking verisini çekebilirsiniz, ama asıl fiyat hesaplaması fonksiyondan gelecek.
$bookingPriceDisplayQuery = "SELECT PRICE FROM booking WHERE BOOK_ID = ?";
$stmt_booking_price = mysqli_prepare($con, $bookingPriceDisplayQuery);
mysqli_stmt_bind_param($stmt_booking_price, "i", $bid);
mysqli_stmt_execute($stmt_booking_price);
$bookingPriceResult = mysqli_stmt_get_result($stmt_booking_price);
$bookingPriceData = mysqli_fetch_assoc($bookingPriceResult);
mysqli_stmt_close($stmt_booking_price);

$display_price = $bookingPriceData ? $bookingPriceData['PRICE'] : 'Hesaplanamadı';


if (isset($_POST['pay'])) {
  $cardno = mysqli_real_escape_string($con, $_POST['cardno']);
  $exp = mysqli_real_escape_string($con, $_POST['exp']);
  $cvv = mysqli_real_escape_string($con, $_POST['cvv']);

  // Boş alan kontrolü
  if (empty($cardno) || empty($exp) || empty($cvv)) {
    echo '<script>alert("Lütfen tüm alanları doldurunuz.")</script>';
  } else {
    // Toplam fiyatı fonksiyonla al
    // Fonksiyon adının ve parametresinin doğru olduğundan emin olun
    $priceQuery = mysqli_query($con, "SELECT CalculateTotalPrice($bid) AS total_calculated_price"); // $bid burada kullanılacak

    if ($priceQuery) {
      $priceRow = mysqli_fetch_assoc($priceQuery);
      // Fiyatın NULL olmadığını ve 0'dan büyük olduğunu kontrol et
      if ($priceRow && isset($priceRow['total_calculated_price']) && is_numeric($priceRow['total_calculated_price']) && $priceRow['total_calculated_price'] > 0) {
        $price = (float) $priceRow['total_calculated_price'];

        $stmt = $con->prepare("CALL AddPayment(?, ?, ?, ?, ?)");
        $bid_int = (int) $bid; // book_id integer olmalı
        $cvv_int = (int) $cvv; // cvv integer olmalı (eğer prosedürde INT ise)

        // Prosedürünüzdeki p_cvv INT, p_price FLOAT. PHP'deki bind_param'daki türler buna uygun olmalı.
        // "isssd" -> i: book_id (INT), s: card_no (VARCHAR), s: exp_date (VARCHAR), i: cvv (INT), d: price (FLOAT/DOUBLE)
        // Eğer cvv'yi PHP'de string olarak alıyorsanız ve prosedür INT bekliyorsa, $cvv_int doğru.
        // Prosedürünüzdeki p_cvv INT olduğuna göre bind_param'daki 3. 's' yerine 'i' olmalı.
        // DÜZELTME: p_card_no VARCHAR, p_exp_date VARCHAR, p_cvv INT
        // bind_param("issid", $bid_int, $cardno, $exp, $cvv_int, $price); OLMALI
        $stmt->bind_param("issid", $bid_int, $cardno, $exp, $cvv_int, $price);


        if ($stmt->execute()) {
          header("Location: psucess.php");
          exit();
        } else {
          echo "<script>alert('Ödeme işlemi başarısız. Hata: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close();
      } else {
        echo "<script>alert('Ödeme tutarı hesaplanamadı veya geçersiz (0 TL). Lütfen rezervasyon bilgilerinizi kontrol edin.');</script>";
      }
    } else {
      echo "<script>alert('Fiyat bilgisi alınırken bir sorun oluştu: " . mysqli_error($con) . "');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Form</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" />
  <script type="text/javascript">
    function preventBack() { window.history.forward(); }
    setTimeout("preventBack()", 0);
    window.onunload = function () { null };
  </script>
  <style>
    /* CSS KODUNUZ AYNI KALACAK */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Roboto', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #a8dadc, #457b9d);
      overflow: hidden;
      position: relative;
    }

    .payment {
      position: absolute;
      top: 30px;
      right: 50px;
      font-size: 24px;
      color: white;
    }

    .card {
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(10px);
      padding: 2rem 3rem;
      border-radius: 1.5rem;
      animation: cardEnter 1s ease forwards;
    }

    @keyframes cardEnter {
      from {
        transform: translateY(50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .card__title {
      font-weight: 600;
      font-size: 2.5rem;
      color: #fff;
      text-align: center;
      margin-bottom: 30px;
    }

    .card__row {
      display: flex;
      justify-content: space-between;
      padding-bottom: 2rem;
    }

    .card__col {
      padding-right: 2rem;
    }

    .card__label {
      color: #ffffff;
      margin-bottom: 5px;
      display: block;
    }

    .card__input {
      background: transparent;
      border: none;
      border-bottom: 2px dashed rgba(255, 255, 255, 0.5);
      font-size: 1.2rem;
      color: #fff;
      width: 100%;
      margin-bottom: 10px;
    }

    .card__input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .card__input:focus {
      outline: none;
      border-bottom: 2px solid #ffffff;
    }

    .card__chip img {
      width: 50px;
    }

    .card__brand {
      font-size: 2rem;
      color: #fff;
      text-align: right;
    }

    .pay,
    .btn {
      width: 220px;
      height: 50px;
      font-size: 18px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      color: white;
      transition: background 0.4s ease;
      margin: 10px 0;
    }

    .pay {
      background: #ff7200;
      margin-left: 30px;
    }

    .pay:hover {
      background: #e36400;
    }

    .btn {
      background: #e63946;
      margin-left: 40px;
    }

    .btn:hover {
      background: #c5303b;
    }

    .btn a,
    .pay a {
      text-decoration: none;
      color: white;
      font-weight: bold;
      display: block;
      width: 100%;
      height: 100%;
      line-height: 50px;
    }
  </style>
</head>

<body>

  <!-- Fiyatı göstermek için $display_price değişkenini kullanın -->
  <h2 class="payment">Toplam Fiyat : <?php echo htmlspecialchars($display_price); ?> TL</h2>

  <div class="card">
    <form method="POST">
      <h1 class="card__title">Ödeme Bilgilerini Giriniz</h1>
      <div class="card__row">
        <div class="card__col">
          <label for="cardNumber" class="card__label">Kart Numarası</label>
          <input type="text" class="card__input card__input--large" id="cardNumber" name="cardno"
            placeholder="xxxx-xxxx-xxxx-xxxx" required maxlength="19">
        </div>
        <div class="card__col card__chip">
          <img src="images/chip.svg" alt="chip" /> <!-- Bu resmin yolunun doğru olduğundan emin olun -->
        </div>
      </div>
      <div class="card__row">
        <div class="card__col">
          <label for="cardExpiry" class="card__label">Son Kullanma Tarihi</label>
          <input type="text" class="card__input" id="cardExpiry" name="exp" placeholder="MM/YY" required maxlength="5">
        </div>
        <div class="card__col">
          <label for="cardCcv" class="card__label">CVV</label>
          <input type="password" class="card__input" id="cardCcv" name="cvv" placeholder="xxx" required maxlength="3">
        </div>
        <div class="card__col card__brand">
          <i id="cardBrand"></i>
        </div>
      </div>
      <input type="submit" value="Satın Al" class="pay" name="pay">
      <button class="btn" onclick="return confirmCancel();"><a href="cancelbooking.php">İptal</a></button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
  <script>
    var cleave = new Cleave('#cardNumber', { creditCard: true });
    var cleaveExpiry = new Cleave('#cardExpiry', { date: true, datePattern: ['m', 'y'] });
    var cleaveCvv = new Cleave('#cardCcv', { numericOnly: true, blocks: [3] });

    function confirmCancel() {
      return confirm('Ödemeyi iptal etmek istediğinizden emin misiniz?');
    }
  </script>
</body>

</html>