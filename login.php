<?php
include("sqlcon.php");
session_start();

$conn = dbconn();

if (!empty($_SESSION["id"])) {
    header("Location: index.php");
}

if (isset($_POST["submit"])) {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$usernameemail' OR email = '$usernameemail'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if (password_verify(trim($password), $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            header("Location: index.php");
            exit();
        } else {
            echo "<script> alert('Wrong Password'); </script>";
        }
    } else {
        echo "<script> alert('User Not Registered'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
    }

    #example {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

</style>

<body>
  <div class="container border shadow-lg rounded" id="example">
    <div class="row">
      <div class="col-md-6 px-3 py-5" style="background-color:#D6EEE4">
        <h3 class="fw-bold">Login</h3> <hr>
        <form method="post" action="" class="mt-4">
          <label class="fw-bold">Username/E-mail</label>
          <input class="col-12 rounded mb-4 border-1" style="height:40px" type="text" name="usernameemail" placeholder="Masukkan E-mail Anda" required><br>

          <label class="fw-bold">Password</label>
          <input class="col-12 rounded mb-4 border-1" style="height:40px" type="password" name="password" placeholder="Masukkan Password Anda" required><br>

          <input class="col-12 rounded-pill mb-4 border-1 fw-bold" style="height:40px; background-color:#C9DED9; color:#3F6675" type="submit" name="submit" value="Login">
          <p>Don't have an account? <a href="regis.php" class="fw-bold">Create now</a></p>
        </form>
      </div>
        <div class="col-md-6 d-none d-lg-block" style="padding-left:100px">
            <img src="login.jpg" class="img-fluid" alt="Login Image">
        </div>
    </div>
  </div>
</body>
</html>

