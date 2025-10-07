<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign Up / Sign In</title>
    <link href="signup.css" rel="stylesheet">
    <script>
        function showPopupAndRedirect(message, redirectUrl) {
            alert(message);
            window.location.href = redirectUrl;
        }
    </script>
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form action="" method="post">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="signupUsername" placeholder="User name" required autofocus>
                <input type="email" name="signupEmail" placeholder="Email address" required>
                <input type="password" name="signupPassword" placeholder="Password" required>
                <button type="submit" name="signupSubmit">Sign up</button>
                <button type="button" onclick="location.href='pa1.html'">Back</button>
            </form>
        </div>

        <div class="login">
            <form action="" method="post">
                <label for="chk" aria-hidden="true">Sign in</label>
                <input type="email" name="loginEmail" placeholder="Email address" required>
                <input type="password" name="loginPassword" placeholder="Password" required>
                <button type="submit" name="loginSubmit">Login</button>
                <button type="button" onclick="location.href='pa1.html'">Back</button>
            </form>

            <div class="forgot">
                <a href="forgot.html">Forgot password?</a>
            </div>
        </div>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "e_channelling";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['signupSubmit'])) {
            $signupUsername = $conn->real_escape_string(htmlspecialchars($_POST['signupUsername']));
            $signupEmail = $conn->real_escape_string(htmlspecialchars($_POST['signupEmail']));
            $signupPassword = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password) VALUES ('$signupUsername', '$signupEmail', '$signupPassword')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>showPopupAndRedirect('Sign-up successful! Welcome $signupUsername.', 'hospital.html');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif (isset($_POST['loginSubmit'])) {
            $loginEmail = $conn->real_escape_string(htmlspecialchars($_POST['loginEmail']));
            $loginPassword = $_POST['loginPassword'];
            
            if ($loginEmail === "jala@gmail.com" && $loginPassword === "jala") {
                $_SESSION['admin'] = true;
                header("Location: ../admin_product.php");
                exit();
            } else {
                $sql = "SELECT * FROM users WHERE email='$loginEmail'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($loginPassword, $user['password'])) {
                        echo "<script>showPopupAndRedirect('Login successful! Welcome back.', 'hospital.html');</script>";
                    } else {
                        echo "<script>alert('Invalid password.');</script>";
                    }
                } else {
                    echo "<script>alert('No user found with this email.');</script>";
                }
            }
        }
    }

    $conn->close();
    ?>
</body>
</html>
