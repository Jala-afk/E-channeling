CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    address TEXT NOT NULL,
    phoneNumber VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    clinic VARCHAR(50) NOT NULL,
    doctor VARCHAR(100),
    gender ENUM('Male', 'Female', 'Prefer not to say') NOT NULL
);
