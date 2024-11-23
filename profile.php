<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch logged-in user details
$user_id = $_SESSION['user_id'];
$result_user = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $result_user->fetch_assoc();

// Fetch user tweets
$result_tweets = $conn->query("
    SELECT content, image_path, created_at
    FROM tweets
    WHERE user_id = $user_id
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user_data['username']; ?> - Profile</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f8fa;
            display: flex;
        }

        /* Sidebar navigation */
        .sidebar {
            width: 250px;
            background-color: white;
            height: 100vh;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            position: fixed;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .logo img {
            width: 50px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .sidebar nav a:hover {
            background-color: #e8f5fd;
            color: #1DA1F2;
        }

        .sidebar nav a .material-icons {
            margin-right: 15px;
            font-size: 22px;
        }

        .sidebar .tweet-btn {
            display: block;
            margin: 20px auto;
            width: 80%;
            padding: 10px;
            text-align: center;
            background-color: #1DA1F2;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
        }

        .sidebar .tweet-btn:hover {
            background-color: #0c85d0;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }

        .profile-header {
            position: relative;
            background-color: #1DA1F2;
            height: 200px;
            color: white;
        }

        .profile-picture {
            position: absolute;
            bottom: -50px;
            left: 20px;
            border: 5px solid white;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: white;
        }

        .profile-info {
            padding: 20px;
            padding-top: 60px;
        }

        .profile-info h2 {
            margin: 10px 0 0;
        }

        .profile-info p {
            color: #657786;
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background-color: white;
            border-top: 1px solid #ddd;
        }

        .profile-stats div {
            text-align: center;
        }

        .profile-tabs {
            display: flex;
            justify-content: space-around;
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .profile-tabs a {
            text-decoration: none;
            color: #657786;
            font-weight: bold;
        }

        .profile-tabs a:hover {
            color: #1DA1F2;
        }

        .tweet {
            background-color: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .tweet img {
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            height: auto;
            margin-top: 10px;
        }

        .tweet-content {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="data:image/png;base64,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" alt="Twitter Logo">
        </div>
        <nav>
            <a href="index.php"><span class="material-icons">home</span> Home</a>
            <a href="#"><span class="material-icons">explore</span> Explore</a>
            <a href="#"><span class="material-icons">notifications</span> Notifications</a>
            <a href="#"><span class="material-icons">mail</span> Messages</a>
            <a href="#"><span class="material-icons">bookmark</span> Bookmarks</a>
            <a href="#"><span class="material-icons">list</span> Lists</a>
            <a href="profile.php"><span class="material-icons">person</span> Profile</a>
            <a href="#"><span class="material-icons">more_horiz</span> More</a>
        </nav>
        <a href="tweet.php" class="tweet-btn">Tweet</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="profile-header">
            <img src="<?php echo $user_data['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
        </div>
        <div class="profile-info">
            <h2><?php echo $user_data['username']; ?></h2>
            <p><?php echo isset($user_data['bio']) && !empty($user_data['bio']) ? $user_data['bio'] : 'No bio available'; ?></p>
            <p>Joined: <?php echo date('F Y', strtotime($user_data['created_at'])); ?></p>
        </div>
        <div class="profile-stats">
            <div>
                <strong>17</strong>
                <p>Following</p>
            </div>
            <div>
                <strong>19.3M</strong>
                <p>Followers</p>
            </div>
            <div>
                <strong>Tweets</strong>
            </div>
        </div>
        <div class="profile-tabs">
            <a href="#">Tweets</a>
            <a href="#">Tweets & Replies</a>
            <a href="#">Media</a>
            <a href="#">Likes</a>
        </div>
        <div class="profile-tweets">
            <?php while ($row = $result_tweets->fetch_assoc()): ?>
                <div class="tweet">
                    <p><?php echo htmlspecialchars($row['content']); ?></p>
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="<?php echo $row['image_path']; ?>" alt="Tweet Image">
                    <?php endif; ?>
                    <p class="tweet-content"><?php echo date('M j, Y', strtotime($row['created_at'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
