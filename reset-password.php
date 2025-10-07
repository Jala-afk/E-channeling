<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_channelling";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetSubmit'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['newPassword'];
    
    if (empty($newPassword)) {
        echo "<p>Password cannot be empty.</p>";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM user WHERE reset_token=? AND token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
         
            $sql = "UPDATE user SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?";
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
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
            <div class="input-text">
                <input type="password" name="newPassword" placeholder="Enter new password" required>
                <label for="newPassword">New Password</label>
            </div>
            <button type="submit" name="resetSubmit">Reset Password</button>
        </form>
    </div>
</body>
</html>
