<?php
require_once('connection.php');

$email = $_GET['email'] ?? '';
$query = mysqli_query($con, "SELECT * FROM users WHERE EMAIL='$email'");
$user = mysqli_fetch_assoc($query);

// Get phone number via stored function
$phone_result = mysqli_query($con, "SELECT GetPhoneByEmail('$email') AS phone_number");
$phone_row = mysqli_fetch_assoc($phone_result);
$user['PHONE_NUMBER'] = $phone_row['phone_number'] ?? '';
mysqli_next_result($con); // stored procedure/function sonrası diğer sorgular için temizleme

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];

    $stmt = $con->prepare("CALL update_user(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $fname, $lname, $phone, $gender);

    if ($stmt->execute()) {
        echo "<script>alert('Müşteri bilgileri güncellendi.'); window.location.href='adminusers.php';</script>";
    } else {
        echo "<script>alert('Güncelleme başarısız.');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Müşteri Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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

    <button id="back"><a href="adminusers.php">Anasayfa</a></button>

    <div class="form-container">
        <h2><i class="fas fa-user-edit"></i> Müşteri Düzenle</h2>
        <form method="POST">
            <label>Ad</label>
            <input type="text" name="fname" value="<?= $user['FNAME'] ?>" required>

            <label>Soyad</label>
            <input type="text" name="lname" value="<?= $user['LNAME'] ?>" required>

            <label>Telefon</label>
            <input type="text" name="phone" value="<?= $user['PHONE_NUMBER'] ?>" required>

            <label>Cinsiyet</label>
            <select name="gender" required>
                <option value="Erkek" <?= $user['GENDER'] == 'Erkek' ? 'selected' : '' ?>>Erkek</option>
                <option value="Kadın" <?= $user['GENDER'] == 'Kadın' ? 'selected' : '' ?>>Kadın</option>
            </select>

            <button type="submit">Güncelle</button>
        </form>
    </div>
</body>

</html>
