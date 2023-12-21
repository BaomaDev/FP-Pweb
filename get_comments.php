<?php
session_start(); // Start the session

include("sqlcon.php");
$conn = dbconn();

$articleId = $_POST['articleId'];

// Make sure $_SESSION['user_id'] is set before using it
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$commentText = $_POST['commentText'];

// Check if the user_id is set before trying to insert into the comments table
if ($userId !== null) {
    $sql = "INSERT INTO comments (article_id, user_id, comment_text) VALUES ('$articleId', '$userId', '$commentText')";
    $conn->query($sql);
    
    // Return the new comment HTML (you may customize this based on your display format)
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
