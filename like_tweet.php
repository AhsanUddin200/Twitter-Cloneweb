<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $tweet_id = $_POST['tweet_id'];

    // Check if already liked
    $check = $conn->query("SELECT * FROM likes WHERE user_id = $user_id AND tweet_id = $tweet_id");

    if ($check->num_rows > 0) {
        // Unlike tweet
        $conn->query("DELETE FROM likes WHERE user_id = $user_id AND tweet_id = $tweet_id");
        echo json_encode(['success' => true, 'action' => 'unliked', 'message' => 'Tweet unliked.']);
    } else {
        // Like tweet
        $conn->query("INSERT INTO likes (user_id, tweet_id) VALUES ($user_id, $tweet_id)");
        echo json_encode(['success' => true, 'action' => 'liked', 'message' => 'Tweet liked.']);
    }
}
?>
