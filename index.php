        <?php
        // Start sessiontweets
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
        $username = $_SESSION['username'];
        $profile_picture = ''; // User profile picture (to be fetched below)

        $result_user = $conn->query("SELECT profile_picture FROM users WHERE id = $user_id");
        if ($result_user && $result_user->num_rows > 0) {
            $user_data = $result_user->fetch_assoc();
            $profile_picture = $user_data['profile_picture'];
        }

        // Fetch tweets
        $result_tweets = $conn->query("
            SELECT tweets.id AS tweet_id, tweets.content, tweets.image_path, tweets.created_at, users.username, users.profile_picture
            FROM tweets
            JOIN users ON tweets.user_id = users.id
            ORDER BY tweets.created_at DESC
        ");

        // Fetch "Who to Follow" suggestions
        $result_users = $conn->query("SELECT id, username, profile_picture, email FROM users WHERE id != $user_id");

        // Fetch trends
        $trends = [
            "#BreakingNews" => "Lunar photography improves the discovery of the moon",
            "#WorldNews" => "125K Tweets",
            "#InternationalCatDay" => "These cats are ready for International Cat Day",
            "#GreatestOfAllTime" => "100K Tweets"
        ];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Home - Twitter Clone</title>
            <!-- Google Material Icons -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <style>
                /* General Body Styles */
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    margin: 0;
                    background-color: #f5f8fa;
                }

                /* Left Sidebar */
                .sidebar {
                    width: 20%;
                    padding: 20px;
                    background-color: white;
                    border-right: 1px solid #ddd;
                    height: 100vh;
                }
                .sidebar a {
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    color: black;
                    padding: 10px 0;
                    margin-bottom: 10px;
                    font-size: 18px;
                }
                .sidebar a i {
                    margin-right: 10px;
                    font-size: 24px;
                    color: #1DA1F2;
                }
                .sidebar a:hover {
                    color: #1DA1F2;
                }
                .tweet-button {
                    display: block;
                    background-color: #1DA1F2;
                    color: white;
                    text-align: center;
                    padding: 10px;
                    margin-top: 20px;
                    border-radius: 20px;
                    text-decoration: none;
                }
                .tweet-button:hover {
                    background-color: #0c85d0;
                }

                /* Main Content */
                .main-content {
                    width: 60%;
                    padding: 20px;
                    overflow-y: auto;
                }
                .tweet-box {
                    background-color: white;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    margin-bottom: 20px;
                }
                .tweet-box img {
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    margin-right: 10px;
                    vertical-align: middle;
                }
                .tweet-box textarea {
                    width: 100%;
                    border: none;
                    resize: none;
                    font-size: 16px;
                    margin-top: 10px;
                }
                .tweet-box textarea:focus {
                    outline: none;
                }
                .tweet-box input[type="file"] {
                    margin-top: 10px;
                }
                .tweet-box button {
                    background-color: #1DA1F2;
                    color: white;
                    border: none;
                    padding: 8px 15px;
                    border-radius: 20px;
                    cursor: pointer;
                }
                .tweet-box button:hover {
                    background-color: #0c85d0;
                }
                .tweet {
                    background-color: white;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    margin-bottom: 20px;
                }
                .tweet img {
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    margin-right: 10px;
                    vertical-align: middle;
                }
                .tweet-content {
                    margin-top: 10px;
                }
                .tweet-image img {
            width: 100%; /* Makes the image take the full width of its container */
            max-width: 1100px; /* Sets a maximum width for larger screens */
            height: auto; /* Ensures the aspect ratio is preserved */
            border-radius: 10px; /* Gives the image rounded corners */
            margin-top: 10px;
            display: block;
        }


        .tweet-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
            font-size: 14px;
            color: #657786;
        }

        .tweet-actions button {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #657786;
        }

        .tweet-actions button:hover {
            color: #1DA1F2;
        }

        .tweet-actions i.material-icons {
            font-size: 20px;
            margin-right: 5px;
        }

        .tweet-actions span {
            font-size: 14px;
        }
        .like-icon {
            color: #657786; /* Default color */
            font-size: 20px;
            cursor: pointer;
        }

        .like-icon.liked {
            color: #E0245E; /* Red color for liked state */
        }

        .like-button {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .like-button:hover .like-icon {
            color: #E0245E; /* Red hover color for the heart */
        }


                /* Right Sidebar */
                .right-sidebar {
                    width: 20%;
                    padding: 20px;
                    background-color: white;
                    border-left: 1px solid #ddd;
                    height: 100vh;
                }
                .search-bar {
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 20px;
                    margin-bottom: 20px;
                    width: 100%;
                }
                .trend {
                    margin-bottom: 20px;
                }
                .who-to-follow {
            margin-top: 20px;
        }

        .follow-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .follow-info {
            display: flex;
            align-items: center;
        }

        .follow-info img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .follow-details {
            display: flex;
            flex-direction: column;
        }

        .follow-details strong {
            font-size: 14px;
            color: #000;
        }

        .follow-details span {
            font-size: 12px;
            color: #657786;
        }

        .follow-button {
            background-color: #1DA1F2;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .follow-button:hover {
            background-color: #0c85d0;
        },
        .show-more {
            background: none;
            border: none;
            color: #1DA1F2;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
            display: block;
        }

        .show-more:hover {
            text-decoration: underline;
        }
        .tweet-button {
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center text horizontally */
            align-items: center; /* Center text vertically */
            background-color: #1DA1F2;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 20px; /* Keep rounded corners */
            text-decoration: none;
            font-size: 16px;
            width: 100%; /* Make it span the full container width */
            height: 50px; /* Adjust height for proper alignment */
            line-height: normal; /* Ensure the line height doesn't misalign the text */
        }



            </style>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    // Submit a new tweet
                    $("#tweetForm").on("submit", function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        $.ajax({
                            url: "post_tweet.php",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                const res = JSON.parse(response);
                                alert(res.message);
                                if (res.success) {
                                    location.reload(); // Reload the page to show new tweets
                                }
                            },
                        });
                    });

                    // Follow/Unfollow a user
                    $(document).on("click", ".follow-button", function () {
                        const button = $(this);
                        const userId = button.data("user-id");

                        $.post("follow_action.php", { user_id: userId }, function (response) {
                            const res = JSON.parse(response);
                            button.text(res.action === "followed" ? "Unfollow" : "Follow");
                            alert(res.message);
                        });
                    });
                    

                    // Like/Unlike a tweet
                    $(document).on("click", ".like-button", function () {
                        const tweetId = $(this).data("tweet-id");

                        $.post("like_tweet.php", { tweet_id: tweetId }, function (response) {
                            const res = JSON.parse(response);
                            alert(res.message);
                        });
                    });
                });
                
                
            </script>
        </head>
        <body>
            <div class="sidebar">
            <img 
            src="data:image/png;base64,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" 
            alt="Twitter Clone Logo"
            style="width: 100px; height: auto; border-radius: 10px;">

                <a href="#"><i class="material-icons">home</i>Home</a>
                <a href="#"><i class="material-icons">explore</i>Explore</a>
                <a href="#"><i class="material-icons">notifications</i>Notifications</a>
                <a href="#"><i class="material-icons">mail</i>Messages</a>
                <a href="#"><i class="material-icons">bookmark</i>Bookmarks</a>
                <a href="#"><i class="material-icons">list</i>Lists</a>
                <a href="profile.php"><i class="material-icons">person</i>Profile</a>

                <a href="#"><i class="material-icons">more_horiz</i>More</a>
                <div class="tweet-button-container">
            <a href="#" class="tweet-button">Tweet</a>
        </div>

            </div>
            <div class="main-content">
            <div class="main-heading">
                <h2>Home</h2>
            </div>
            <div class="tweet-box" style="background-color: white; border: 1px solid #ddd; border-radius: 15px; padding: 15px; margin-bottom: 20px;">
    <form id="tweetForm" enctype="multipart/form-data">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <img src="<?php echo $profile_picture; ?>" alt="User Profile" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
            <textarea id="tweetContent" name="tweet_content" placeholder="What's happening?" rows="2" required style="flex: 1; border: none; resize: none; font-size: 16px; outline: none;"></textarea>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px; position: relative;">
                <!-- Image Icon -->
                <label for="tweet_image" style="cursor: pointer;">
                    <i class="material-icons" style="color: #1DA1F2; font-size: 24px;">image</i>
                </label>
                <input id="tweet_image" type="file" name="tweet_image" style="display: none;">
                
                <!-- GIF Icon -->
                <i class="material-icons" style="color: #1DA1F2; font-size: 24px;">gif_box</i>
                
                <!-- Emoji Icon -->
                <i id="emojiButton" class="material-icons" style="color: #1DA1F2; font-size: 24px; cursor: pointer;">mood</i>

                <!-- Emoji Popup -->
                <div id="emojiPopup" style="display: none; position: absolute; top: 30px; left: 0; background-color: white; border: 1px solid #ddd; border-radius: 10px; padding: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <span class="emoji" style="font-size: 24px; cursor: pointer; margin-right: 10px;">😀</span>
                    <span class="emoji" style="font-size: 24px; cursor: pointer; margin-right: 10px;">❤️</span>
                    <span class="emoji" style="font-size: 24px; cursor: pointer;">🔥</span>
                </div>
            </div>
            <button type="submit" style="background-color: #1DA1F2; color: white; border: none; padding: 8px 20px; border-radius: 30px; font-size: 14px; cursor: pointer;">Tweet</button>
        </div>
    </form>
</div>


                <div id="tweets">
            <?php while ($row = $result_tweets->fetch_assoc()): ?>
                <div class="tweet">
                    <img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture" style="border-radius: 50%; width: 50px; height: 50px;">
                    <strong>@<?php echo $row['username']; ?>df</strong>
                    <div class="tweet-content"><?php echo htmlspecialchars($row['content']); ?></div>
                    <?php if (!empty($row['image_path'])): ?>
                        <div class="tweet-image">
                            <img src="<?php echo $row['image_path']; ?>" alt="Tweet Image">
                        </div>
                    <?php endif; ?>
                
                    <div class="tweet-actions">
            <button class="like-button" data-tweet-id="<?php echo $row['tweet_id']; ?>">
                <i class="material-icons like-icon">favorite_border</i>
                <span class="like-count">7</span>
            </button>
            <button class="retweet-button" data-tweet-id="<?php echo $row['tweet_id']; ?>">
                <i class="material-icons">repeat</i>
                <span class="retweet-count">4</span>
            </button>
            <button class="comment-button" data-tweet-id="<?php echo $row['tweet_id']; ?>">
                <i class="material-icons">chat_bubble_outline</i>
                <span class="comment-count">88</span>
            </button>
            <button class="share-button" data-tweet-id="<?php echo $row['tweet_id']; ?>">
                <i class="material-icons">share</i>
            </button>
        </div>

                </div>
            <?php endwhile; ?>
        </div>

            </div>
            <div class="right-sidebar">
                <input type="text" class="search-bar" placeholder="Search Twitter">
                <h3>Trends for You</h3>
                <?php foreach ($trends as $trend => $details): ?>
                    <div class="trend">
                        <strong><?php echo $trend; ?></strong>
                        <p><?php echo $details; ?></p>
                    </div>
                <?php endforeach; ?>
                
        <div class="who-to-follow">
        <h3>Who To Follow You</h3>
            <?php while ($user = $result_users->fetch_assoc()): ?>
                
                <div class="follow-item">
                    <div class="follow-info">
                        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                        <div class="follow-details">
                            <strong><?php echo $user['username']; ?></strong>
                            <span>@<?php echo $user['username']; ?></span>
                        </div>
                    </div>
                    <button class="follow-button" data-user-id="<?php echo $user['id']; ?>">Follow</button>
                </div>
            <?php endwhile; ?>
        </div>

            </div>
        </body>
        </html>
