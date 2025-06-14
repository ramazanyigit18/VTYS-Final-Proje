<?php
require_once('connection.php');
require_once('mail_helper.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Saklı yordamı çağır
    $stmt = $con->prepare("CALL create_user(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fname, $lname, $email, $phone, $gender, $password);

    if ($stmt->execute()) {
        // Mail gönder
        sendUserNotificationMail($email, 'INSERT');
        echo "<script>alert('Kullanıcı başarıyla eklendi ve mail gönderildi.'); window.location.href='adminusers.php';</script>";
    } else {
        echo "<script>alert('Kullanıcı eklenirken hata oluştu.');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Müşteri Ekle</title>
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

        button:[type="submit"] {
            background-color: #ff7200;
            transition: background-color 0.3s ease;
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
        <h2><i class="fas fa-user-plus"></i> Yeni Müşteri Ekle</h2>
        <form action="" method="POST">
            <label>Ad</label>
            <input type="text" name="fname" required>

            <label>Soyad</label>
            <input type="text" name="lname" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Telefon</label>
            <input type="text" name="phone" required>

            <label>Cinsiyet</label>
            <select name="gender" required>
                <option value="">Seçiniz</option>
                <option value="Erkek">Erkek</option>
                <option value="Kadın">Kadın</option>
                <option value="Diğer">Diğer</option>
            </select>

            <label>Şifre</label>
            <input type="password" name="password" required>

            <button type="submit">Müşteri Ekle</button>
        </form>
    </div>
</body>

</html>