<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_channelling";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $remember = isset($_POST['remember']) ? 'Yes' : 'No';

    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
       
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            $resetToken = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            
            $sql = "UPDATE users SET reset_token=?, token_expiry=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $resetToken, $expiry, $email);
            $stmt->execute();

            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password:\n";
            $message .= "http://localhost/reset-password.php?token=" . $resetToken;
            $headers = "From: no-reply@yourdomain.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "<p>An email has been sent to $email with instructions to reset your password.</p>";
            } else {
                echo "<p>Sorry, there was an issue sending the email. Please try again later.</p>";
            }
        } else {
            echo "<p>No account found with that email address.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Please enter a valid email address.</p>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="forgot.css"/>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <h2>Reset Password</h2>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div class="input-text">
                <input type="password" name="newPassword" placeholder="Enter new password" required>
                <label for="newPassword">New Password</label>
            </div>
            <button type="submit" name="resetSubmit">Reset Password</button>
        </form>
    </div>

    <?php
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_management";

    
    $conn = new mysqli($servername, $username, $password, $dbname);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetSubmit'])) {
        $token = $_POST['token'];
        $newPassword = $_POST['newPassword'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE reset_token=? AND token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            $sql = "UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashedPassword, $token);
            if ($stmt->execute()) {
                echo "<p>Your password has been updated successfully.</p>";
            } else {
                echo "<p>Error updating password: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>Invalid or expired token.</p>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>
</body>
</html>
