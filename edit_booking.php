<?php
require_once('connection.php');

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $car_id = $_POST['car_id'];
    $email = $_POST['email'];
    $book_place = $_POST['book_place'];
    $book_date = $_POST['book_date'];
    $phone_number = $_POST['phone_number'];
    $destination = $_POST['destination'];
    $return_date = $_POST['return_date'];
    $book_status = $_POST['book_status'];

    $stmt = $con->prepare("CALL UpdateBooking(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssss", $book_id, $car_id, $email, $book_place, $book_date, $phone_number, $destination, $return_date, $book_status);

    if ($stmt->execute()) {
        echo "<script>alert('Rezervasyon başarıyla güncellendi.'); window.location.href='adminbook.php';</script>";
    } else {
        echo "<script>alert('Güncelleme başarısız.');</script>";
    }

    $stmt->close();
}

// Mevcut verileri çek
if (isset($_GET['bookid'])) {
    $book_id = $_GET['bookid'];
    $stmt = $con->prepare("SELECT * FROM booking WHERE BOOK_ID = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
}

// Dropdownlar için araç ve kullanıcı bilgileri
$cars = $con->query("SELECT CAR_ID, car_name FROM cars");
$users = $con->query("SELECT email FROM users");
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Rezervasyon Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        
        * {
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 50px;
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
            text-align: center;
        }
    </style>
</head>

<body>

    <button id="back"><a href="adminbook.php">Anasayfa</a></button>

    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Rezervasyonu Düzenle</h2>
        <form method="POST" action="">
            <input type="hidden" name="book_id" value="<?= $booking['BOOK_ID'] ?>">

            <label>Araç Seç</label>
            <select name="car_id" required>
                <?php while ($row = $cars->fetch_assoc()): ?>
                    <option value="<?= $row['CAR_ID'] ?>" <?= ($row['CAR_ID'] == $booking['CAR_ID']) ? 'selected' : '' ?>>
                        <?= $row['car_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Müşteri Email</label>
            <select name="email" required>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <option value="<?= $row['email'] ?>" <?= ($row['email'] == $booking['EMAIL']) ? 'selected' : '' ?>>
                        <?= $row['email'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Rezervasyon Yeri</label>
            <input type="text" name="book_place" value="<?= $booking['BOOK_PLACE'] ?>" required>

            <label>Rezervasyon Tarihi</label>
            <input type="date" name="book_date" value="<?= $booking['BOOK_DATE'] ?>" required>

            <label>Telefon Numarası</label>
            <input type="text" name="phone_number" value="<?= $booking['PHONE_NUMBER'] ?>" required>

            <label>Araç Teslim Noktası</label>
            <input type="text" name="destination" value="<?= $booking['DESTINATION'] ?>" required>

            <label>Araç Teslim Tarihi</label>
            <input type="date" name="return_date" value="<?= $booking['RETURN_DATE'] ?>" required>

            <label>Durum</label>
            <select name="book_status" required>
                <option value="Beklemede" <?= ($booking['BOOK_STATUS'] == "Beklemede") ? "selected" : "" ?>>Beklemede
                </option>
                <option value="Onaylandı" <?= ($booking['BOOK_STATUS'] == "Onaylandı") ? "selected" : "" ?>>Onaylandı
                </option>
                <option value="İptal" <?= ($booking['BOOK_STATUS'] == "İptal") ? "selected" : "" ?>>İptal</option>
            </select>

            <button type="submit">Rezervasyonu Güncelle</button>
        </form>
    </div>
</body>

</html>