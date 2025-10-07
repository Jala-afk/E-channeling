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

    /
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
       
        $sql = "SELECT * FROM user WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            $resetToken = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $sql = "UPDATE user SET reset_token=?, token_expiry=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $resetToken, $expiry, $email);
            $stmt->execute();

            
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password:\n";
            $message .= "http://localhost/reset_password.php?token=" . $resetToken;
            $senderEmail = "brokenjala2002@gmail.com"; 
            $headers = "From: " . $senderEmail;

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
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="forgot.css"/>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <h2>Request Password Reset</h2>
            <div class="input-text">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <label for="email">Email</label>
            </div>
            <input type="submit" name="submit" value="Send Reset Link">
        </form>
    </div>
</body>
</html>
