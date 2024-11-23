<?php
include 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $profile_picture = null;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }
        $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);
    }

    // Insert into database
    $sql = "INSERT INTO users (username, email, password, profile_picture) VALUES ('$username', '$email', '$password', '$profile_picture')";
    if ($conn->query($sql) === TRUE) {
        header('Location: login.php'); // Redirect to login page after successful signup
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Twitter Clone</title>
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
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUMAAACcCAMAAADS8jl7AAAAclBMVEUdofL///8Am/EAmfEAnPEWn/L0+/7v+P76/v/l8/0PnfHp9f7M5/whovIAlvC23vpVs/RMsPTd7/3Q6/yu2/peuPVArfQ5qPNvwPbD5fuk1vlpvPYqpvOOzfgxq/PM6/x+x/ee0/lzw/dCsPSDy/iaz/idkbZoAAADuUlEQVR4nO3b627qOhAFYON4cnEICc7F5LY3lM37v+JJUC8BEidQqdDj9f2qVCpFS7FnPLiMAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADA70MkO/Tsx/jFBGc6yfNtIzhifAQJkVQq9FzXC9et5nLwKxk977l+EUm7dPXFj7cf76LkRZaL5z7ds1ETza9MQbG/uhC2Tvdn5Gz+VJ5qLF/bPFNiLgKeh6sbJeNR0f7tfqr5jzzp64rCVTazFHnh3Ua4Wq2r9Bzt2vYSw3ddCrHxTZRFMBbhh0DLrun5sQd+QTzrc6gMTR/x0hSh1xUUTjbviETqnETM5NRHzm/qpDDZiGYXFz/50C+Gmvet7q+eKgyRMkRYJkkbe6udzVVFfpaLIHdGPyF2viFDFbj9VjD+p5aQg5LbRmP1WcSmpXxW2fwWdmtZD9qWVG9uP8BMS/kstnwKQeyib8n0da9HhWkp4y3sXTUuqmXORYoymYmwtfys3OH7q0y8thCDWERuTNCrHbsXck/qm7XqZTVzPmKcyVAtGFj8//GRuusHVd5I0Y+shXktBxoZ9hueO75Ky8M+KSLRjM4bPjO0+ZD3RRymAvK9MFDr8YjfqebZj/8CiMhJTSmZYT/sRIIL82zLKGXIUOZqX3C+nmukp5TWN9hdhlt3FZbVfvZANwGHlH72NfJNyR3ekCEj8Y2C0jlOjm4twg/fidBHSWH9APE7GaZWD18/CeM3TjPsHmB/EhNnvSX8GtvhmagezlDpZz/8q3j8rJehs3lH+tEQbb/vNSCj9UMRBrZftBkidnqksLSoygOSb+O7UwwLvIYDDfFN8+/OwQPmDUM8VmWZKuPU/4ZboDkcMF/tmnDAbnjBeLdrXIhv9C6J/O5B9h694RXn3gNfaff14VF03/jG1SgoNyiav2f4xd+jrxlBVC3fE7PZf2mxEzlJujDFFJe9pkhWLzrxBeiuDeRGHOvDXIQN2hojuUlmpolhgnpiQlxnM2+hKhChgXSi09z1JaWxkKeQFKQPsxdH4gjl5IokIkmSc862bTlbk90WTc216Fg0TfPnWLfxkuGh2qK1vtV11f7S00nYCmyFIwR7Wzo5zFCPp3CnNt9aPwuyZoNlPIm4rDNziulJC5RjIyLe1HE4mqPvqfbI8V38AsR5k5/i9KI4u6rM3rbE8Qou1TXZgult/favPR3adl8nuiEhLP/f5PsR69ttp8c50gMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAH/UfVtwrGBl8Qn0AAAAASUVORK5CYII='); /* Base64 image as background */
            background-size: cover;
            background-position: center;
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

        .footer {
            margin-top: 15px;
            text-align: center;
        }

        .footer p {
            font-size: 14px;
            color: #555;
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
           
        </div>
        <div class="right">
            <h2>Sign Up</h2>
            <form class="form" action="signup.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="file" name="profile_picture" accept="image/*">
                <button type="submit">Sign Up</button>
            </form>
            <div class="footer">
                <p>Already have an account? <a href="login.php">Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
