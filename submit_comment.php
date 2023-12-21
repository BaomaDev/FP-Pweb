<?php
session_start(); // Start the session


include("sqlcon.php");
$conn = dbconn();

if (!isset($_SESSION['id'])) {
    echo 'Error: User not logged in.';
    exit();
}
$articleId = $_POST['articleId'];


// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $commentText = $_POST['commentText'];

    // Insert the comment into the comments table
    $sql = "INSERT INTO comments (article_id, user_id, comment_text) VALUES ('$articleId', '$userId', '$commentText')";
    $result = $conn->query($sql);

    if ($result) {
        // Return the new comment HTML (you may customize this based on your display format)
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
