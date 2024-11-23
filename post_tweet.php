<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $tweet_content = $_POST['tweet_content'];

    $image_path = null;
    if (isset($_FILES['tweet_image']) && $_FILES['tweet_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = time() . "_" . $_FILES['tweet_image']['name'];
        $image_path = 'uploads/' . $image_name;
        move_uploaded_file($_FILES['tweet_image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO tweets (user_id, content, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $tweet_content, $image_path);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Tweet posted successfully!']);
}
