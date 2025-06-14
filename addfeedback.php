<?php
require_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    $stmt = $con->prepare("CALL AddFeedback(?, ?)");
    $stmt->bind_param("ss", $email, $comment);

    if ($stmt->execute()) {
        echo "<script>alert('Geri bildirim başarıyla eklendi.'); window.location.href='admindash.php';</script>";
    } else {
        echo "<script>alert('Ekleme işlemi başarısız.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Geri Bildirim Ekle</title>
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
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 15px;
        }

        textarea {
            height: 100px;
            resize: vertical;
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

        button:hover {
            background-color: #e55f00;
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

    <button id="back"><a href="admindash.php">Anasayfa</a></button>

    <div class="form-container">
        <h2><i class="fas fa-comment-dots"></i> Geri Bildirim Ekle</h2>
        <form action="" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="comment">Yorum</label>
            <textarea name="comment" id="comment" required></textarea>

            <button type="submit">Gönder</button>
        </form>
    </div>
</body>

</html>