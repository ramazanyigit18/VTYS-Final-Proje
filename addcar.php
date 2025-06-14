<?php
session_start();
require_once('connection.php');

// Oturum kontrolü
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Kullanıcı bilgilerini al
$value = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE EMAIL='$value'";
$name = mysqli_query($con, $sql);
$rows = mysqli_fetch_assoc($name);

// Kiralık araçları çek
$sql2 = "SELECT * FROM cars WHERE AVAILABLE='Y'";
$cars = mysqli_query($con, $sql2);

// --- Güncelleme modu kontrolü ---
$update_mode = false;
$car_data = [
    'id' => '',
    'carname' => '',
    'ftype' => '',
    'price' => '',
    'available' => '',
    'image' => ''
];

if (isset($_GET['edit'])) {
    $update_mode = true;
    $car_id = intval($_GET['edit']);
    $result = mysqli_query($con, "SELECT * FROM cars WHERE CAR_ID = $car_id");
    if (mysqli_num_rows($result) == 1) {
        $car_data_raw = mysqli_fetch_assoc($result);
        $car_data = [
            'id' => $car_id,
            'carname' => $car_data_raw['CAR_NAME'],
            'ftype' => $car_data_raw['FUEL_TYPE'],
            'price' => $car_data_raw['PRICE'],
            'available' => $car_data_raw['AVAILABLE'],
            'image' => $car_data_raw['CAR_IMG']
        ];
    }
}

// --- Araç ekleme işlemi ---
if (isset($_POST['addcar'])) {
    $carname = mysqli_real_escape_string($con, $_POST['carname']);
    $ftype = mysqli_real_escape_string($con, $_POST['ftype']);
    $price = floatval($_POST['price']);
    $available = ($_POST['available'] == 1) ? 'Y' : 'N';

    if (!empty($_FILES['image']['name'])) {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "webp", "svg");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'images/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);

            // Güvenli prosedür çağrısı
            $stmt = $con->prepare("CALL AddCar(?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $carname, $ftype, $price, $available, $new_img_name);

            if ($stmt->execute()) {
                echo '<script>alert("Araç başarıyla eklendi.")</script>';
                echo '<script>window.location.href="adminvehicle.php";</script>';
            } else {
                echo '<script>alert("Araç eklenemedi.")</script>';
            }

            $stmt->close();
        } else {
            echo '<script>alert("Geçersiz görsel formatı!")</script>';
            exit;
        }
    } else {
        echo '<script>alert("Araç görseli zorunludur!")</script>';
        exit;
    }
}


// --- Araç güncelleme işlemi ---
if (isset($_POST['updatecar'])) {
    $car_id = intval($_POST['car_id']);
    $carname = mysqli_real_escape_string($con, $_POST['carname']);
    $ftype = mysqli_real_escape_string($con, $_POST['ftype']);
    $price = floatval($_POST['price']);
    $available = ($_POST['available'] == 1) ? 'Y' : 'N';

    $new_img_name = $car_data['image']; // Varsayılan olarak eski resmi al

    if (!empty($_FILES['image']['name'])) {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "webp", "svg");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'images/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
        } else {
            echo '<script>alert("Geçersiz görsel formatı!")</script>';
            exit;
        }
    }

    // UpdateCar prosedürü çağrısı
    $stmt = $con->prepare("CALL UpdateCar(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $car_id, $carname, $ftype, $price, $available, $new_img_name);

    if ($stmt->execute()) {
        echo '<script>alert("Araç başarıyla güncellendi.")</script>';
        echo '<script>window.location.href="adminvehicle.php";</script>';
    } else {
        echo '<script>alert("Araç güncellenemedi.")</script>';
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #4b4b4b, #d6e9f9);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
        }

        .main {
            width: 400px;
            margin: 50px auto;
            padding-top: 30px;
        }

        .register {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4a4a4a;
        }

        form#register {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input#name,
        input[type="file"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f0f8ff;
            /* Buz mavisi */
        }

        input[type="submit"][name="addcar"] {
            background-color: #ff7200;
            color: white;
        }

        input[type="submit"][name="updatecar"] {
            background-color: #ff7200;
            color: white;
        }

        select#status {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f0f8ff;

            font-size: 16px;
            color: #333;
        }



        .btnn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            color: #fff;
            background-color: #ff7200;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btnn:hover {
            background: #ff7200;
        }

        #back {
            width: 120px;
            height: 40px;
            background: #ff7200;
            border: none;
            font-size: 16px;
            border-radius: 20px;
            float: right;
            margin: 20px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: -30px;
        }

        #back:hover {
            color: white;
            font-weight: bold;
        }

        #back a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }


        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 6px;
            font-size: 15px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background-color: #f0f8ff;
            transition: all 0.2s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #5d6d7e;
            outline: none;
            background-color: #ffffff;
        }
    </style>
</head>

<body>

    <button id="back"><a href="adminvehicle.php">Anasayfa</a></button>

    <div class="main">
        <div class="register">
            <h2>Kiralık Araç Detayları</h2>
            <form action="addcar.php" method="POST" enctype="multipart/form-data" id="register">
                <input type="hidden" name="car_id" value="<?= $car_data['id'] ?>">

                <div class="form-group">
                    <label for="carname">Araç Markası :</label>
                    <input type="text" id="carname" name="carname" value="<?= htmlspecialchars($car_data['carname']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="ftype">Yakıt Tipi :</label>
                    <input type="text" id="ftype" name="ftype" value="<?= htmlspecialchars($car_data['ftype']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="price">Fiyat :</label>
                    <input type="number" id="price" name="price" value="<?= htmlspecialchars($car_data['price']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="available">Araç Durumu:</label>
                    <select name="available" id="available" required>
                        <option value="1" <?= ($car_data['available'] == 'Y') ? 'selected' : '' ?>>Kiralık</option>
                        <option value="0" <?= ($car_data['available'] == 'N') ? 'selected' : '' ?>>Bakımda</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Araç Görseli:</label>
                    <input type="file" id="image" name="image">
                </div>

                <div class="form-group">
                    <?php if ($update_mode): ?>
                        <input type="submit" name="updatecar" value="Araç Güncelle" class="btnn">
                    <?php else: ?>
                        <input type="submit" name="addcar" value="Kiralık Araç Ekle" class="btnn">
                    <?php endif; ?>
                </div>
            </form>


        </div>
    </div>

</body>

</html>