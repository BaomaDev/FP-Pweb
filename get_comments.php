<?php
session_start(); 

include("sqlcon.php");
$conn = dbconn();

$articleId = $_POST['articleId'];

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$commentText = $_POST['commentText'];

if ($userId !== null) {
    $sql = "INSERT INTO comments (article_id, user_id, comment_text) VALUES ('$articleId', '$userId', '$commentText')";
    $conn->query($sql);
    
    echo '<div class="d-flex mb-4">
        <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
        <div class="ms-3">
            <div class="fw-bold">'.$username.'</div>
            '.$commentText.'
        </div>
    </div>';
} else {
    echo 'Error: User not logged in.';
}

$conn->close();
?>
