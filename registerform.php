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
    
    $fullName = $conn->real_escape_string(htmlspecialchars($_POST['fullName']));
    $age = $conn->real_escape_string(htmlspecialchars($_POST['age']));
    $address = $conn->real_escape_string(htmlspecialchars($_POST['address']));
    $phoneNumber = $conn->real_escape_string(htmlspecialchars($_POST['phoneNumber']));
    $email = $conn->real_escape_string(htmlspecialchars($_POST['email']));
    $date = $conn->real_escape_string(htmlspecialchars($_POST['date']));
    $clinic = $conn->real_escape_string(htmlspecialchars($_POST['clinic']));
    $doctor = $conn->real_escape_string(htmlspecialchars($_POST['doctor']));
    $gender = $conn->real_escape_string(htmlspecialchars($_POST['gender']));
    
    
    $sql = "INSERT INTO registrations (fullName, age, address, phoneNumber, email, date, clinic, doctor, gender)
            VALUES ('$fullName', '$age', '$address', '$phoneNumber', '$email', '$date', '$clinic', '$doctor', '$gender')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thank you for registering, $fullName. Your information has been received.'); window.location.href = 'hospital.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Channelling</title>
    <link rel="stylesheet" href="reg.css"/>
</head>
<body>
    <div class="container">
        <div class="title">Registration</div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            include 'process_registration.php'; 
        }
        ?>

        <form action="" method="post">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Full name</span>
                    <input type="text" name="fullName" placeholder="Enter your name" required>
                </div>
                <div class="input-box">
                    <span class="details">Age</span>
                    <input type="text" name="age" placeholder="Enter your age" required>
                </div>
                <div class="input-box">
                    <span class="details">Address</span>
                    <input type="text" name="address" placeholder="Enter your Address" required>
                </div>
                <div class="input-box">
                    <span class="details">Phone number</span>
                    <input type="text" name="phoneNumber" placeholder="+94123456789" required>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" name="email" placeholder="abc@gmail.com" required>
                </div>
                <div class="input-box">
                    <span class="details">Date</span>
                    <input type="date" name="date" required>
                </div>
                <br>
                <div class="clinic">
                    <label for="clinic">Clinic:</label>
                    <select id="clinic" name="clinic" onchange="updateOptions()" required>
                        <option value="">Select a clinic</option>
                        <option value="Primary-Care">Primary Care clinic</option>
                        <option value="Specialty">Specialty Clinic</option>
                        <option value="Surgical">Surgical clinic</option>
                        <option value="Rehabilitation">Rehabilitation Clinic</option>
                        <option value="Mental-Health">Mental Health clinic</option>
                        <option value="Oncology">Oncology clinic</option>
                        <option value="Women's-Health">Women's Health clinic</option>
                    </select>
                    
                    <label for="doctor">Sort:</label>
                    <select id="doctor" name="doctor">
                        <option value="">Select a category</option>
                    </select>
                </div>
            </div>
            
            <div class="gender-details">
                <input type="radio" name="gender" id="dot-1" value="Male">
                <input type="radio" name="gender" id="dot-2" value="Female">
                <input type="radio" name="gender" id="dot-3" value="Prefer not to say">
                
                <span class="gender-title">Gender</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Male</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="gender">Female</span>
                    </label>
                    <label for="dot-3">
                        <span class="dot three"></span>
                        <span class="gender">Prefer not to say</span>
                    </label>
                </div>
            </div>
            
            <div class="button">
                <input type="submit" value="Register">
                <button type="button" onclick="location.href='hospital.html'">Back</button>
            </div>
        </form>
    </div>

    <script>
        function updateOptions() {
            var category = document.getElementById("clinic").value;
            var options = {
                "Primary-Care": ["family medicine", "internal medicine", "pediatrics"],
                "Specialty": ["Cardiology", "Neurology", "Orthopedics", "Gastroenterology", "Dermatology", "Endocrinology"],
                "Surgical": ["general surgery", "orthopedic surgery", "neurosurgery"],
                "Rehabilitation": ["physical therapy", "occupational therapy", "speech therapy"],
                "Mental-Health": ["psychiatry", "psychology", "counseling services"],
                "Oncology": ["chemotherapy", "radiation therapy", "other cancer-related treatments"],
                "Women's-Health": ["gynecology", "obstetrics", "reproductive health"]
            };
            
            var doctorSelect = document.getElementById("doctor");
            doctorSelect.innerHTML = ""; 
            
            if (options[category]) {
                options[category].forEach(function(item) {
                    var option = document.createElement("option");
                    option.value = item;
                    option.text = item;
                    doctorSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>
