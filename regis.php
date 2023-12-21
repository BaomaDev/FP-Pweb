<?php
session_start(); // Start the session

include("sqlcon.php");
$conn = dbconn();

if (!empty($_SESSION["id"])) {
    header("Location: index.php");
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirm_password"];

    $profilePicturePath = "uploads/default-profile-picture.jpg"; 
    
    if (!empty($_FILES["profile_picture"]["name"])) {
        $targetDirectory = "uploads/";
        $profilePictureFileName = $username . "_" . time() . "." . pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
        $targetFile = $targetDirectory . $profilePictureFileName;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profilePicturePath = $targetFile;
        } else {
            echo "<script> alert('Sorry, there was an error uploading your profile picture.'); </script>";
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script> alert('Invalid Email Format'); </script>";
    } else {
        $duplicate = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' OR email = '$email'");

        if (mysqli_num_rows($duplicate) > 0) {
            $existingUser = mysqli_fetch_assoc($duplicate);
            if ($existingUser['username'] == $username) {
                echo "<script> alert('Username has already been taken'); </script>";
            } elseif ($existingUser['email'] == $email) {
                echo "<script> alert('A user with this email already exists'); </script>";
            }
        } else {
            if ($password == $confirmpassword) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO tb_user (name, username, email, password, profile_picture_path) VALUES ('$name', '$username', '$email', '$hashed_password', '$profilePicturePath')";
                mysqli_query($conn, $query);

                $_SESSION['profile_picture_path'] = $profilePicturePath;

                echo "<script> alert('Registration Successful'); </script>";
            } else {
                echo "<script> alert('Password Do Not Match'); </script>";
            }
        }
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
        <div class="col-md-6 px-3 py-4" style="background-color:#D6EEE4">
            <h3 class="fw-bold">Create A New Account</h3> <hr>
            <form class="mt-2" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" autocomplete="off" class="col-2">
            <label class="fw-bold">Name</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" type="text" id="name" name="name" placeholder="Nama Lengkap Anda" required><br>

            <label class="fw-bold">Password</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" type="text" id="username" name="username" placeholder="Username" required><br>

            <label class="fw-bold">E-Mail</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" type="email" id="email" name="email" placeholder="E-Mail" required><br>

            <label class="fw-bold">Password</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" type="password" id="password" name="password" placeholder="Password" required><br>

            <label class="fw-bold">Confirm Password</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br>

            <label class="fw-bold">Profile Picture</label>
            <input class="col-12 rounded mb-1 border-1" style="height:40px" ttype="file" id="profile_picture" name="profile_picture" accept="image/*" placeholder="Nama Lengkap Anda..." required><br>

            <input class="col-12 rounded-pill mb-1 border-1 fw-bold" style="height:40px; background-color:#C9DED9; color:#3F6675" type="submit" name="submit">
            <p>Sudah Punya Akun? <a href="login.php" class="fw-bold">Log In Sekarang</a></p>
            </form>
        </div>
            <div class="col-md-6 d-none d-lg-block" style="padding-left:30px">
                <img src="regis.png" class="img-fluid" alt="Login Image">
            </div>
        </div>
    </div>
</body>
</html>
