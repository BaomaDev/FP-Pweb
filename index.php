<?php
session_start();

include("sqlcon.php");

$conn = dbconn();

$loggedIn = isset($_SESSION["login"]) && $_SESSION["login"];
$username = $loggedIn ? $_SESSION["username"] : "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                    echo '<li class="nav-item border rounded-pill text-center" style="background-color:#EADCC7; width: 100px; height: 40px; margin-left: 10px;"><a class="nav-link" href="regis.php">Sign Up</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
  <div class="row">
  <div class="col-md-8">
    <?php if ($loggedIn) : ?>
    <div class="border container py-3 shadow-sm d-none d-sm-block" style="background-color:#D6EEE4">
        <div class="row">
            <div class="col-1">
                <?php
                if (!empty($_SESSION['profile_picture_path'])) {
                    echo '<img src="' . $_SESSION['profile_picture_path'] . '" class="img-fluid rounded-circle" alt="Profile Picture">';
                } else {
                    echo '<img src="default-profile-picture.jpg" class="rounded-circle img-fluid" style="width:32px;height:32px" alt="Default Profile Picture">';
                }
                ?>
            </div>
            <form class="d-flex ms-auto col-sm-11 col-xs-12">
              <input class="form-control rounded-pill" id="makeQuestion" type="text" placeholder="Apa yang kamu ingin tanyakan atau ceritakan?" onclick="window.location.href='addArtikel.php'">
            </form>
        </div>
        <p class="mt-2">Jangan lupa untuk melihat rules ya!</p>
    </div>
    <?php endif; ?>

    <div class="container-fluid mt-3 border" style="background-color:#D6EEE4">
    <table class="table no-border">
        <thead>
          <tr>
          </tr>
        </thead>
        <tbody id="myTable">
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo '<tr class="row mt-2">';
              if (!empty($_SESSION['profile_picture_path'])) {
                 echo '<td class="col-md-1 col-2"><img src="' . $_SESSION['profile_picture_path'] . '" class="img-fluid rounded-circle" alt="Profile Picture"></td>';
              } else {
                 echo '<td class="col-md-1 col-2"><img src="default-profile-picture.jpg" class="rounded-circle" style="width:32px;height:32px" alt="Default Profile Picture"></td>';
              }
              echo '<td class="col-10">';
              echo "<p>" . $row['creator_username'] . "</p>";
              echo '</td>';
              echo '<td class="col-12">';
              echo '<div class="row">';
              echo '<p class="fw-bold"><a class="text-decoration-none text-dark" href="artikel.php?id=' . $row['id'] . '">' . $row['judul'] . '</a></p>';
              echo '<div id="truncate-text">' . $row['content'] . '</div>';
              if (!empty($row['image_url'])) {
                 echo '<img src="' . $row['image_url'] . '" class="truncate-image">';
              }
              echo '</div>';
              echo '</td>';
              echo '</tr>';
             
            }
          }
           else {
            echo '<tr class="row"><td class="col-sm-12">No forums found</td></tr>';
          }
          $conn->close();
          ?>

        </tbody>
      </table>
      <div id="pagination" class="text-center">
        <ul class="pagination"></ul>
      </div>
    </div>

  </div>
        
    <div class='col-3 border ms-5 d-none d-md-block' style="height:480px; background-color:#ECE9E2">
      <p class="mt-3">Selamat Datang pada Forum Ini!!!</p>
      <hr>
      <p>Mohon untuk menjaga kesopanan dan keramahan dalam berinteraksi dengan sesama pengguna forum :D</p>
      <p class="opacity-50">Dibuat oleh dan didedikasikan untuk :</p>
      <li>Jesse Robinson Junior Simanjuntak - 5025221024</li>
      <li>Koresy Samuel P. Nainggolan - 5025221141</li>
      Kuliah Pemrograman Web Jurusan Teknik Informatika ITS (2023). 
      Dosen: Imam Kuswardayan,
      S.Kom, M.T.
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  var itemsPerPage = 5;
  var tableRows = $("#myTable tr");
  var totalPages = Math.ceil(tableRows.length / itemsPerPage);

  // Pagination
  var pagination = $("#pagination ul");
  for (var i = 1; i <= totalPages; i++) {
    pagination.append('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
  }

  tableRows.hide();
  tableRows.slice(0, itemsPerPage).show();
  pagination.find("li:first").addClass("active");

  function handlePaginationClick(currentPage) {
    var start = (currentPage - 1) * itemsPerPage;
    var end = start + itemsPerPage;

    tableRows.hide();
    tableRows.slice(start, end).show();

    pagination.find("li").removeClass("active");
    pagination.find("li").eq(currentPage - 1).addClass("active");
  }

  pagination.find("a").click(function(e) {
    e.preventDefault();
    handlePaginationClick(parseInt($(this).text()));
  });

  // Truncate text
  $("#truncate-text").each(function() {
    var maxLength = 200;
    var text = $(this).text();
    if (text.length > maxLength) {
      var truncatedText = text.substring(0, maxLength) + " ...";
      $(this).text(truncatedText);
    }
  });

  // Search filter
  function resetPaginationAndFilter() {
    $("#myInput").val("");
    tableRows.show();
    handlePaginationClick(1);
  }

  $("#myInput").on("input", function() {
    var value = $(this).val().toLowerCase();
    tableRows.each(function() {
      var rowText = $(this).text().toLowerCase();
      $(this).toggle(rowText.indexOf(value) > -1);
    });

    if (value === "") {
      resetPaginationAndFilter();
    }
  });
});
</script>

</body>
</html>
