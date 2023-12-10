<?php
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

                $query = "INSERT INTO tb_user (name, username, email, password) VALUES ('$name', '$username', '$email', '$hashed_password')";
                mysqli_query($conn, $query);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
    <h2>Registration</h2>
    <div class="container-fluid">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" class="col-2">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
    
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
    
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
    
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
