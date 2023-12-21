<?php
session_start();


include("sqlcon.php");
$conn = dbconn();

if (!isset($_SESSION['id'])) {
    echo 'Error: User not logged in.';
    exit();
}
$articleId = $_POST['articleId'];


if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $commentText = $_POST['commentText'];

    $sql = "INSERT INTO comments (article_id, user_id, comment_text) VALUES ('$articleId', '$userId', '$commentText')";
    $result = $conn->query($sql);

    if ($result) {
        echo '<div class="d-flex mb-4">
            <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
            <div class="ms-3">
                <div class="fw-bold">'.$username.'</div>
                '.$commentText.'
            </div>
        </div>';
    } else {
        echo 'Error: Unable to insert comment.';
    }
} else {
    echo 'Error: User not logged in.';
}

$conn->close();
?>
