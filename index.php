<?php
include("sqlcon.php");

$conn = dbconn();

session_start();

$loggedIn = isset($_SESSION["login"]) && $_SESSION["login"];
$username = $loggedIn ? $_SESSION["username"] : "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM artikel";
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
</head>
<body>
<section class="pageTop" id="pageTop">
  <div class="p-5 text-center text-white" style="background-color:#2e2e2e">
      <h1>Jesse Robinson Junior Simanjuntak</h1>
      <h3>5025221024</h3>
      <h3>Pemrograman Web C</h3>
    <div class="py-2">
      <?php
      if ($loggedIn) {
          echo "<p>Hello, $username</p>";
          echo '<a href="addArtikel.php">Add Article</a>  ';
          echo '  <a href="?logout=true">Logout</a>';
      } else {
          echo '<a href="login.php">Login</a>';
      }
      ?>
    </div>
  </div>
</section>


<div class="container mt-5">
  <input class="form-control" id="myInput" type="text" placeholder="Search..">
  <br>
  <table class="table">
    <thead>
      <tr>
      </tr>
    </thead>
    <tbody id="myTable">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<tr class="row">';
          echo '<td class="col-sm-6"><img src="' . $row['image_url'] . '" class="w-100 h-100"></td>';
          echo '<td class="col-sm-6">';
          echo '<p class="h2"><a class="text-decoration-none text-dark" href="artikel.php?id=' . $row['id'] . '">' . $row['judul'] . '</a></p>';
          echo '<p id="truncate-text">' . $row['subjudul'] . '</p>';
          echo '</td>';
          echo '</tr>';
        }
      } else {
        echo '<tr class="row"><td class="col-sm-12">No articles found</td></tr>';
      }
      $conn->close();
      ?>
    </tbody>
  </table>

  <div id="pagination" class="text-center">
    <ul class="pagination"></ul>
  </div>

</div>

<footer class="text-center container-fluid border bg-dark text-white sticky">
  <p>Tugas Pemrograman Web Jurusan Teknik Informatika ITS 2023</p>
  <p>5025221024, Jesse Robinson Junior Simanjuntak, dosen: Imam Kuswardayan, S.Kom, M.T</p>
</footer>

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
