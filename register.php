<!DOCTYPE html>
<html lang="en">

<head>

  <title>REGISTRATION</title>
  <link rel="stylesheet" href="css/regs.css" type="text/css">
</head>

<body>

  <?php

  require_once('connection.php');
  if (isset($_POST['regs'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $ph = mysqli_real_escape_string($con, $_POST['ph']);

    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    $cpass = mysqli_real_escape_string($con, $_POST['cpass']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $Pass = md5($pass);
    if (empty($fname) || empty($lname) || empty($email) || empty($ph) || empty($pass) || empty($gender)) {
      echo '<script>alert("please fill the place")</script>';
    } else {
      if ($pass == $cpass) {
        $sql2 = "SELECT *from users where EMAIL='$email'";
        $res = mysqli_query($con, $sql2);
        if (mysqli_num_rows($res) > 0) {
          echo '<script>alert("EMAIL ALREADY EXISTS PRESS OK FOR LOGIN!!")</script>';
          echo '<script> window.location.href = "index.php";</script>';

        } else {
          $sql = "insert into users (FNAME,LNAME,EMAIL,PHONE_NUMBER,PASSWORD,GENDER) values('$fname','$lname','$email',$ph,'$Pass','$gender')";
          $result = mysqli_query($con, $sql);


          // $to_email = $email;
          // $subject = "NO-REPLY";
          // $body = "THIS MAIL CONTAINS YOUR AUTHENTICATION DETAILS....\nYour Password is $pass and Your Registered email is $to_email"
          //          ;
          // $headers = "From: sender email";
  
          // if (mail($to_email, $subject, $body, $headers))
  
          // {
          //     echo "Email successfully sent to $to_email...";
          // }
  
          // else
  
          // {
          // echo "Email sending failed!";
          // }
          if ($result) {
            echo '<script>alert("Giriş Yapmak İçin Tamam Butonuna Basınız!")</script>';
            echo '<script> window.location.href = "index.php";</script>';
          } else {
            echo '<script>alert("please check the connection")</script>';
          }

        }

      } else {
        echo '<script>alert("PASSWORD DID NOT MATCH")</script>';
        echo '<script> window.location.href = "register.php";</script>';
      }
    }
  }


  ?>



  <style>
    body {
      background: linear-gradient(to right, #6E7B8B, #E0F7FA);
      background-size: auto;
      background-position: unset;
      /* background-repeat: ; */
    }

    input#psw {
      width: 300px;
      border: 1px solid #ddd;
      border-radius: 3px;
      outline: 0;
      padding: 7px;
      background-color: #fff;
      box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.3);
    }

    input#cpsw {
      width: 300px;
      border: 1px solid #ddd;
      border-radius: 3px;
      outline: 0;
      padding: 7px;
      background-color: #fff;
      box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.3);
    }

    #back {
      color: white;
      background-color: #ff7200;
      float: right;
      margin-right: 30px;
      margin-top: -10px;
      border-radius: 10px;
    }

    #back a {
      text-decoration: none;
      color: white;
      display: block;
    }

    #back:hover {
      background-color: #ff7200;
    }

    #message {
      display: none;
      background: #f1f1f1;
      color: #000;
      position: relative;
      padding: 20px;

      width: 400px;
      margin-left: 1000px;
      margin-top: -500px;
    }

    #message p {
      padding: 10px 35px;
      font-size: 18px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
      color: green;
    }

    .valid:before {
      position: relative;
      left: -35px;
      content: "✔";
    }

    /* Add a red text color and an "x" icon when the requirements are wrong */
    .invalid {
      color: red;
    }

    .invalid:before {
      position: relative;
      left: -35px;
      content: "✖";
    }
  </style>

  <button id="back"><a href="index.php">Anasayfa</a></button>
  <div class="main">

    <div class="register">
      <h2>Kayıt Ol</h2>

      <form id="register" action="register.php" method="POST">
        <label>İsim : </label>
        <br>
        <input type="text" name="fname" id="name" required>
        <br><br>

        <label>Soyisim : </label>
        <br>
        <input type="text" name="lname" id="name" required>
        <br><br>

        <label>Email : </label>
        <br>
        <input type="email" name="email" id="name" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
          title="ex: example@ex.com" required>
        <br><br>

        <label>Şifre : </label>
        <br>
        <input type="password" name="pass" maxlength="12" id="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
          title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
          required>
        <br><br>

        <label>Şifre Doğrulama : </label>
        <br>
        <input type="password" name="cpass" id="cpsw" required>
        <br><br>

        <label>Telefon Numarası : </label>
        <br>
        <input type="tel" name="ph" maxlength="11" onkeypress="return onlyNumberKey(event)" id="name" required>
        <br><br>


        <tr>
          <td>
            <label">Cinsiyet : </label>
          </td><br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td>
            <label for="one">Erkek</label>
            <input type="radio" id="input_enabled" name="gender" value="male" style="width:200px">
          </td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <td>
            <label for="two">Kadın</label>
            <input type="radio" id="input_disabled" name="gender" value="female" style="width:200px" />
          </td>
        </tr>
        <br><br>

        <input type="submit" class="btnn" value="Kayıt Ol" name="regs" style="background-color: #ff7200;color: white">



        </input>

      </form>
    </div>
  </div>
  <div id="message">
    <h3>Password must contain the following:</h3>
    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
    <p id="number" class="invalid">A <b>number</b></p>
    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
  </div>
  <script>
    var myInput = document.getElementById("psw");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    // When the user clicks on the password field, show the message box
    myInput.onfocus = function () {
      document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function () {
      document.getElementById("message").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function () {
      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if (myInput.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
      }

      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if (myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }

      // Validate numbers
      var numbers = /[0-9]/g;
      if (myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }

      // Validate length
      if (myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
      } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
      }
    }
  </script>
  <script>
    function onlyNumberKey(evt) {

      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
      return true;
    }
  </script>
</body>

</html>