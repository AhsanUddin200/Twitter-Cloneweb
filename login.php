<?php
include 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check credentials
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Start session and set session variables
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header("Location: index.php"); // Redirect to the index page
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Twitter Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }

        .left {
            background-color: #1DA1F2;
            color: white;
            padding: 50px;
            text-align: center;
            flex: 1;
        }

        .left h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .left p {
            font-size: 18px;
        }

        .right {
            flex: 1;
            padding: 50px;
        }

        .right h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .form {
            display: flex;
            flex-direction: column;
        }

        .form input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form button {
            padding: 10px;
            background-color: #1DA1F2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .form button:hover {
            background-color: #0d8bf2;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #1DA1F2;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Welcome Back!</h1>
            <p>Login to your account and stay connected.</p>
        </div>
        <div class="right">
            <h2>Login</h2>
            <form class="form" action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="footer">
                <p>New to Twitter? <a href="signup.php">Sign up now</a></p>
                <p>Forgot your password? <a href="forgot_password.php">Reset your password</a></p>
            </div>
        </div>
    </div>
</body>
</html>
