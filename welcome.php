<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Twitter Clone</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f8fa;
        }
        .welcome-container {
            display: flex;
            width: 80%;
            max-width: 1200px;
            height: 80%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .left-section {
            flex: 1;
            background-image: url('https://images.unsplash.com/photo-1611162618479-ee3d24aaef0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .right-section {
            flex: 1;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .right-section h1 {
            font-size: 36px;
            color: #1DA1F2;
            margin-bottom: 10px;
        }
        .right-section p {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .right-section .buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 300px;
        }
        .buttons a {
            text-decoration: none;
            text-align: center;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
        }
        .sign-up {
            background-color: #1DA1F2;
            color: white;
        }
        .sign-up:hover {
            background-color: #0c85d0;
        }
        .log-in {
            background-color: white;
            color: #1DA1F2;
            border: 2px solid #1DA1F2;
        }
        .log-in:hover {
            background-color: #f0f8ff;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #657786;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <!-- Left Section -->
        <div class="left-section"></div>

        <!-- Right Section -->
        <div class="right-section">
            <h1>Happening now</h1>
            <p>Join Twitter today.</p>
            <div class="buttons">
                <a href="signup.php" class="sign-up">Sign Up</a>
                <a href="login.php" class="log-in">Log In</a>
            </div>
            <div class="footer">
                <p>&copy; 2021 Twitter Clone. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
