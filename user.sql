CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255),
    token_expiry DATETIME
);
INSERT INTO user (email, password) VALUES (?, ?);

SELECT * FROM user WHERE email = ?;

UPDATE user
SET reset_token = ?, token_expiry = ?
WHERE email = ?;

SELECT * FROM user
WHERE reset_token = ? AND token_expiry > NOW();

UPDATE user
SET password = ?, reset_token = NULL, token_expiry = NULL
WHERE reset_token = ?;
