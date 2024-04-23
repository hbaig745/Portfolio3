<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>
<form method="POST" action="registration.php">
    <label for="reg_username">Username:</label>
    <input type="text" name="reg_username" id="reg_username" required><br><br>
    <label for="reg_email">Email:</label>
    <input type="email" name="reg_email" id="reg_email" required><br><br>
    <label for="reg_password">Password:</label>
    <input type="password" name="reg_password" id="reg_password" required><br><br>
    <button type="submit" name="register">Register</button>
</form>

<?php
if(isset($_POST['register'])) {
    $reg_username = $_POST['reg_username'];
    $reg_email = $_POST['reg_email'];
    $reg_password = $_POST['reg_password'];

    $dsn = "mysql:host=localhost;dbname=u_230412350_db";
    $username = "u-230412350";
    $password = "P6vGFORta3p5G0G";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $hashedPassword = password_hash($reg_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $reg_username);
        $stmt->bindParam(':email', $reg_email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();

        echo "<p>User registered successfully!</p>";

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<a href="index.php">Return to homepage</a>
</body>
</html>
