<?php
session_start();
include("sqlcon.php");
$conn = dbconn();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$comment = $_POST['comment_text'];
$articleId = isset($_GET['id']) ? $_GET['id'] : null;

if (!empty($comment) && !empty($articleId)) {
    $sql = "INSERT INTO comments (comment_text, article_id) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $comment, $articleId);
        $stmt->execute();
        $stmt->close();

        header("Location: artikel.php?id=" . $articleId);
        exit();
    }
}

$conn->close();
?>
