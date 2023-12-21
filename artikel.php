<?php
session_start();
include("sqlcon.php");
$conn = dbconn();

$articleId = isset($_GET['id']) ? $_GET['id'] : null;
if (empty($articleId)) {
    header("Location: artikel.php?id=" . $articleId);
    exit();
}

$sql = "SELECT * FROM artikel WHERE id = $articleId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $articleTitle = $row['judul'];
    $content = $row['content'];
    $image_url = $row['image_url'];
} else {
    $articleTitle = "Article Not Found";
    $content = "The requested article does not exist.";
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

$sql = "SELECT comment_text FROM comments";
$result = $conn->query($sql);

$conn->close();
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

    .no-border td, .no-border th {
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

    <div class="container mt-5">
        <div class="row">
        <div class="col-md-8 col-12">
        <div class="border p-3 container" style="background-color:#D6EEE4" method="post" action="?a=i" onsubmit="return validatediv()">
            <table style="width: 100%">
                <tr>
                    <td><h2 class="fw-bolder mb-1"><?php echo $articleTitle ?></h2></td>
                </tr>
                <tr>
                    <td><?php echo $content ?></td>
                </tr>
                <tr>
                    <td><?php if (!empty($row['image_url'])) {
                        echo '<img src="' . $row['image_url'] . '" class="truncate-image">';
                    } ?>
                    </td>
                </tr>
            </table>
        </div>

        <!--comment_section-->
        <section class="mt-3 container py-3" style="background-color:#D6EEE4">
            <form action="submit_comment.php?id=<?php echo $articleId; ?>" method="post">
                <input type="hidden" name="articleId" value="<?php echo $articleId; ?>">
                <textarea name="comment_text" class="div-control col-12" rows="4" placeholder="Tulis komentarmu"></textarea>
                <input type="submit" value="Submit Comment">
            </form>

            <?php
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="d-flex mt-4">';
                echo '<div class="flex-shrink-0"><img class="rounded-circle" src="default-profile-picture.jpg" class="rounded-circle img-fluid" style="width:32px;height:32px" alt="..." /></div>';
                echo '<div class="ms-3">';
                echo '<div class="fw-bold">User</div>';
                echo $row["comment_text"]; 
                echo '</div></div>';
            }} else {
                echo "No comments yet.";
            }?>
        </section>
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
</body>

</html>
