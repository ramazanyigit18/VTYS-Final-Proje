<?php
require_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $email = $_POST['email'];
    $book_place = $_POST['book_place'];
    $book_date = $_POST['book_date'];
    $phone_number = $_POST['phone_number'];
    $destination = $_POST['destination'];
    $return_date = $_POST['return_date'];
    $book_status = $_POST['book_status'];

    $stmt = $con->prepare("CALL AddBooking(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $car_id, $email, $book_place, $book_date, $phone_number, $destination, $return_date, $book_status);

    if ($stmt->execute()) {
        // Mail gönderimi
        $user_email = $_POST['email'];
        $subject = "Rezervasyon Başarılı";
        $message = "Sayın kullanıcı,\n\nRezervasyonunuz başarıyla alınmıştır. En kısa sürede sizinle iletişime geçilecektir.\n\nİyi günler dileriz.";
        $headers = "From: noreply@siteadi.com";

        mail($user_email, $subject, $message, $headers);

        echo "<script>alert('Rezervasyon başarıyla eklendi. Kullanıcıya mail gönderildi.'); window.location.href='adminbook.php';</script>";
    } else {
        echo "<script>alert('Ekleme işlemi başarısız.');</script>";
    }

    $stmt->close();
}

$cars = $con->query("SELECT CAR_ID, car_name FROM cars");
$users = $con->query("SELECT email FROM users");
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Rezervasyon Ekle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 50px;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 15px;
        }

        button {
            background-color: #ff7200;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        #back {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 120px;
            height: 40px;
            background: #ff7200;
            border: none;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            z-index: 999;
        }

        #back a {
            color: white;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>

    <button id="back"><a href="adminbook.php">Anasayfa</a></button>

    <div class="form-container">
        <h2><i class="fas fa-car"></i> Yeni Rezervasyon Ekle</h2>
        <form action="" method="POST">

            <label>Araç Seç</label>
            <select name="car_id" required>
                <option value="">Seçiniz</option>
                <?php while ($row = $cars->fetch_assoc()): ?>
                    <option value="<?= $row['CAR_ID'] ?>"><?= basename($row['car_name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Müşteri Email</label>
            <select name="email" required>
                <option value="">Seçiniz</option>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <option value="<?= $row['email'] ?>"><?= $row['email'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Rezervasyon Yeri</label>
            <input type="text" name="book_place" required>

            <label>Rezervasyon Tarihi</label>
            <input type="date" name="book_date" required>

            <label>Telefon Numarası</label>
            <input type="text" name="phone_number" required>

            <label>Araç Teslim Noktası</label>
            <input type="text" name="destination" required>

            <label>Araç Teslim Tarihi</label>
            <input type="date" name="return_date" required>

            <label>Durum</label>
            <select name="book_status" required>
                <option value="Beklemede">Beklemede</option>
                <option value="Onaylandı">Onaylandı</option>
                <option value="İptal">İptal</option>
            </select>

            <button type="submit">Rezervasyon Ekle</button>
        </form>
    </div>
</body>

</html>