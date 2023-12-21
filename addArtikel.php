<?php
include("sqlcon.php");

$conn = dbconn();

session_start();

if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$act = isset($_GET['a']) ? $_GET['a'] : "";

$is_admin = isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'admin';

if ($act == "i") {
    if (isset($_POST['judul']) && isset($_POST['content'])) {
        $judul = $_POST['judul'];
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : null;

        if ($image_url !== null && !preg_match("/\.(jpeg|jpg|png|gif)$/i", $image_url)) {
            $image_url = null;
        }

        $username = $_SESSION['username'];
        $user_id_query = "SELECT id FROM tb_user WHERE username = '$username'";
        $user_id_result = mysqli_query($conn, $user_id_query);
        $user_id_row = mysqli_fetch_assoc($user_id_result);
        $user_id = $user_id_row['id'];


        $sql = "INSERT INTO artikel (judul, content, image_url, user_id) VALUES ('$judul', '$content', '$image_url', '$user_id')";

        $result = mysqli_query($conn, $sql);
    } else {
        echo "One or more fields are not set in the POST request.";
    }
}

if ($act == "d" && $is_admin) {
    if (isset($_GET['id'])) {
        $id_to_delete = $_GET['id'];
        $sql = "DELETE FROM artikel WHERE id = '$id_to_delete'";
        $result = mysqli_query($conn, $sql);
    } else {
        echo "id is not set in the URL.";
    }
}

if ($act == "e" && $is_admin) {
    if (isset($_GET['id'])) {
        $id_to_edit = $_GET['id'];
        $sql = "SELECT * FROM artikel WHERE id = '$id_to_edit'";
        $result = mysqli_query($conn, $sql);
        $student = mysqli_fetch_assoc($result);

        if ($student) {
            if (isset($_POST['judul']) && isset($_POST['content']) && isset($_POST['image_url'])) {
                $new_judul = $_POST['judul'];
                $new_content = mysqli_real_escape_string($conn, $_POST['content']);
                $new_image_url = $_POST['image_url'];

                if ($new_image_url !== null && !preg_match("/\.(jpeg|jpg|png|gif)$/i", $new_image_url)) {
                    $new_image_url = null;
                }

                $update_sql = "UPDATE artikel SET judul = '$new_judul', content = '$new_content', image_url = '$new_image_url' WHERE id = '$id_to_edit'";
                $update_result = mysqli_query($conn, $update_sql);

                if ($update_result) {
                    echo "Data updated successfully.";
                } else {
                    echo "Error updating data: " . mysqli_error($conn);
                }
            }
            ?>
            <form method="post" action="?a=e&id=<?php echo $id_to_edit; ?>">
                Judul: <input name="judul" type="text" value="<?php echo $student['judul']; ?>"><br>
                Content: <input name="content" type="text" value="<?php echo htmlspecialchars($student['content']); ?>"><br>
                Image URL: <input name="image_url" type="text" value="<?php echo $student['image_url']; ?>"><br>
                <button type="submit">Simpan Perubahan</button>
            </form>
            <?php
        } else {
            echo "Data not found for id: $id_to_edit";
        }
    } else {
        echo "id is not set in the URL for editing.";
    }
}

$sql = "SELECT * FROM artikel";
$result = mysqli_query($conn, $sql);

$loggedIn = isset($_SESSION["login"]) && $_SESSION["login"];
$username = $loggedIn ? $_SESSION["username"] : "";

$sql = "SELECT artikel.*, tb_user.username as creator_username 
        FROM artikel
        INNER JOIN tb_user ON artikel.user_id = tb_user.id";
$result = $conn->query($sql);

if (isset($_GET["logout"])) {
  $_SESSION = array();

  session_destroy();

  header("Location: index.php");
  exit();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .no-border td,
    .no-border th {
        border: none !important;
    }

    .truncate-image {
        height: 150px;
        object-fit: cover;
    }
</style>

<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color:#80B9A6">
    <div class="container">
        <a class="navbar-brand col-md-2 fw-bold" href="index.php">ForumIn</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <form class="d-flex ms-auto col-md-7">
              <input class="form-control rounded-pill" id="myInput" type="text" placeholder="Search.." style="background-color:#C7EAE5">
          </form>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 col-md-3">
                <?php
                if ($loggedIn) {
                    echo "<li class='nav-item'> <a class='nav-link'>Hello, $username</a> </li>";
                    echo '<li class="nav-item d-block d-sm-none mb-2"> <a class="nav-link" href="addArtikel.php">Add Article</a> </li>';
                    echo '<li class="nav-item border rounded-pill text-center" style="background-color:#EADCC7; width: 100px; height: 40px;"> <a class="nav-link" href="?logout=true">Logout</a> </li>';
                } else {
                    echo '<li class="nav-item border rounded-pill text-center" style="background-color:#EADCC7; width: 100px; height: 40px"><a class="nav-link" href="login.php">Login</a></li>';
                    echo '<li class="nav-item border rounded-pill text-center ms-md-1" style="background-color:#EADCC7; width: 100px; height: 40px;"><a class="nav-link" href="regis.php">Sign Up</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

    <?php
    if ($is_admin) {
    ?>
        <table style="width: 100%">
            <tr>
                <td>Judul</td>
                <td>Content</td>
                <td>Image URL</td>
                <td>Action</td>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                    <td><?php echo $row['image_url']; ?></td>
                    <td>
                        <a href="?a=e&id=<?php echo $row['id']; ?>">Edit Data</a>
                        <a href="?a=d&id=<?php echo $row['id']; ?>">Hapus Data</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    <?php
    }
    ?>

    <div class="container mt-5">
        <div class="row">
        <div class="col-md-8 col-12">
        <h3 class="mb-4">New Post<hr></h3>
        <form class="border p-3 container shadow" style="background-color:#D6EEE4" method="post" action="?a=i" onsubmit="return validateForm()">
            <table style="width: 100%">
                <tr>
                    <td><input name="judul" class="rounded-pill col-12 mb-3" type="text" placeholder="  Judul..."></td>
                </tr>
                <tr>
                    <td><textarea name="content" class="col-12 mb-3 border-black rounded" placeholder="  Tuliskan di sini..." rows="5"></textarea></td>
                <tr>
                    <td><input name="image_url" class="rounded-pill col-12 mb-3" type="text" placeholder="  Image URL (Opsional)..."></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><button type="submit" style="background-color:#80B9A6; text-color:#6A8990">Tambah</button></td>
                </tr>
            </table>
        </form>
        </div>

        <div class='col-3 border ms-5 d-none d-md-block shadow' style="height:280px; background-color:#D6EEE4">
            <h5 class="mt-3 text-center fw-bold">Rules</h5>
            <hr>
            <div class="border border-dark rounded-3 mb-3" style="background-color:#F8F8F8; opacity:0.7;">&nbsp1. No SARA</div>
            <div class="border border-dark rounded-3 mb-3" style="background-color:#F8F8F8; opacity:0.7;">&nbsp2. Hargai Sesama</div>
            <div class="border border-dark rounded-3 mb-3" style="background-color:#F8F8F8; opacity:0.7;">&nbsp3. No Toxic</div>
            <div class="border border-dark rounded-3 mb-3" style="background-color:#F8F8F8; opacity:0.7;">&nbsp4. No NSFW</div>
        </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var judul = document.getElementsByName("judul")[0].value;
            var content = document.getElementsByName("content")[0].value;
            var image_url = document.getElementsByName("image_url")[0].value;

            if (judul === "" || content === "") {
                alert("Please fill in all fields");
                return false;
            }

            if (image_url !== "" && !/\.(jpeg|jpg|png|gif)$/i.test(image_url)) {
                alert("Invalid image URL format");
                return false;
            }

            return true;
        }
    </script>

    <?php
    mysqli_close($conn);
    ?>
</body>

</html>
