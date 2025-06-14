<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LOGIN</title>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
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
            height: 100vh;
            background: linear-gradient(to right, #6E7B8B, #E0F7FA);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }

        .form {
            width: 350px;
            background: rgba(0, 0, 0, 0.75);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .form h2 {
            text-align: center;
            color: #ff7200;
            background: white;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form input.h {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 2px solid #ff7200;
            margin-bottom: 30px;
            padding: 10px;
            font-size: 16px;
            color: white;
            outline: none;
        }

        ::placeholder {
            color: #ccc;
            font-size: 14px;
        }

        .btnn {
            width: 100%;
            background: #ff7200;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }

        .btnn:hover {
            background: white;
            color: #ff7200;
            font-weight: bold;
        }

        .btnn a {
            text-decoration: none;
            color: inherit;
        }

        .back {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #ff7200;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .back a {
            text-decoration: none;
            color: inherit;
        }

        .helloadmin {
            position: absolute;
            top: 80px;
            text-align: center;
        }

        .helloadmin h1 {
            font-size: 48px;
            color: white;
            font-family: 'Times New Roman', serif;
        }
    </style>
</head>

<body>

    <?php
    require_once('connection.php');
    if (isset($_POST['adlog'])) {
        $id = $_POST['adid'];
        $pass = $_POST['adpass'];

        if (empty($id) || empty($pass)) {
            echo '<script>alert("Please fill the blanks")</script>';
        } else {
            $query = "SELECT * FROM admin WHERE ADMIN_ID='$id'";
            $res = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($res)) {
                $db_password = $row['ADMIN_PASSWORD'];
                if ($pass == $db_password) {
                    echo '<script>alert("Welcome ADMINISTRATOR!");</script>';
                    header("Location: admindash.php");
                } else {
                    echo '<script>alert("Enter a proper password")</script>';
                }
            } else {
                echo '<script>alert("Enter a proper email")</script>';
            }
        }
    }
    ?>

    <button class="back"><a href="index.php">Anasayfa</a></button>

    <div class="helloadmin">
        <h1>Yönetici Giriş Formu</h1>
    </div>

    <form class="form" method="POST">
        <h2>Admin Login</h2>
        <input class="h" type="text" name="adid" placeholder="Yönetici ID Giriniz" required>
        <input class="h" type="password" name="adpass" placeholder="Yönetici Şifre Giriniz" required>
        <input type="submit" class="btnn" value="Giriş Yap" name="adlog">
    </form>

</body>

</html>