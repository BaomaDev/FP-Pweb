<?php
include("sqlcon.php");
$conn = dbconn();

$articleId = isset($_GET['id']) ? $_GET['id'] : null;
if ($articleId == "") header("location:index.php");

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $articleTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
    <!-- Responsive navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Isport Niuws</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <article>
                    <header class="mb-4">
                        <h1 class="fw-bolder mb-1"><?php echo $articleTitle ?></h1>
                        <div class="text-muted fst-italic mb-2">Posted on <?php echo $row['tanggal'] ?> by <?php echo $row['penulis'] ?></div>
                        <a class="badge bg-secondary text-decoration-none link-light" href="#!">Pro play</a>
                        <a class="badge bg-secondary text-decoration-none link-light" href="#!">E-Sport</a>
                    </header>

                    <figure class="mb-4"><img class="img-fluid rounded" src="<?php echo $image_url ?>" alt="..." /></figure>

                    <section class="mb-5">
                        <?php echo $content ?>
                    </section>
                </article>

                <section class="mb-5">
                    <div class="card bg-light">
                        <div class="card-body">
                            <form class="mb-4"><textarea class="form-control" rows="3" placeholder="Tulis komentarmu"></textarea></form>
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                <div class="ms-3">
                                    <div class="fw-bold">Jeremy</div>
                                    If you're going to lead a space frontier, it has to be government; it'll never be private enterprise. Because the space frontier is dangerous, and it's expensive, and it has unquantified risks.
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                        <div class="ms-3">
                                            <div class="fw-bold">James</div>
                                            And under those conditions, you cannot establish a capital-market evaluation of that enterprise. You can't get investors.
                                        </div>
                                    </div>
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Budi</div>
                                            When you put money directly to a problem, it makes a good headline.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                <div class="ms-3">
                                    <div class="fw-bold">Gaboleh</div>
                                    When I look at the universe and all the ways the universe wants to kill us, I find it hard to reconcile that with statements of beneficence.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Selamat datang di blog aku</div>
                    <div class="card-body">Boleh sekali jika kamu melihat artikel lainnya. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam at possimus cumque quasi nulla molestias amet? Rerum, quaerat, omnis facilis eveniet itaque natus cumque inventore perferendis porro labore, et maxime.</div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center container-fluid border bg-dark text-white sticky">
        <p>Tugas Pemrograman Web Jurusan Teknik Informatika ITS 2023</p>
        <p>5025221024, Jesse Robinson Junior Simanjuntak, dosen: Imam Kuswardayan, S.Kom, M.T</p>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
