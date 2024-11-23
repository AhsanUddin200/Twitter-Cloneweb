<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $follower_id = $_SESSION['user_id'];
    $following_id = $_POST['user_id'];

    // Check if already following
    $check = $conn->query("SELECT * FROM followers WHERE follower_id = $follower_id AND following_id = $following_id");

    if ($check->num_rows > 0) {
        // Unfollow user
        $conn->query("DELETE FROM followers WHERE follower_id = $follower_id AND following_id = $following_id");
        echo json_encode(['success' => true, 'action' => 'unfollowed', 'message' => 'User unfollowed.']);
    } else {
        // Follow user
        $conn->query("INSERT INTO followers (follower_id, following_id) VALUES ($follower_id, $following_id)");
        echo json_encode(['success' => true, 'action' => 'followed', 'message' => 'User followed.']);
    }
}
?>
