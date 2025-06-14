<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <title>Başarılı Kiralama</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Roboto", sans-serif;
      background-color: #E0F7FA;
      /* Açık buz mavisi */
      background-image: url("images/creditcards.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background-color: #ECEFF1;
      /* Metalik gri */
      padding: 60px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
      text-align: center;
      max-width: 500px;
      width: 100%;
    }

    .icon-circle {
      background-color: #E0F7FA;
      /* Açık buz mavisi */
      border-radius: 50%;
      width: 150px;
      height: 150px;
      margin: 0 auto 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .icon-circle i {
      color: #ff7200;
      font-size: 100px;
    }

    h1 {
      color: #263238;
      /* Koyu lacivert */
      font-size: 36px;
      margin-bottom: 20px;
    }

    p {
      color: #263238;
      font-size: 18px;
      margin-bottom: 40px;
    }

    .btn-back {
      background-color: #ff7200;
      /* Canlı mavi */
      color: white;
      padding: 12px 28px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .btn-back:hover {
      background-color: #ff7200;
      /* Koyu mavi hover */
    }
  </style>
</head>

<body>

  <div class="card">
    <div class="icon-circle">
      <i class="fa fa-check-circle"></i>
    </div>
    <h1>Başarılı</h1>
    <p>Kiralama talebiniz başarıyla alındı.<br> En kısa zamanda sizinle iletişime geçilecektir.</p>
    <a href="cardetails.php" class="btn-back">Araçlara Geri Dön</a>
  </div>

  <!-- Font Awesome CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

</body>

</html>