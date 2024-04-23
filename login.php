<?php
session_start();

if(isset($_POST['login'])) {
    $dsn = "mysql:host=localhost;dbname=u_230412350_db";
    $username = "u-230412350";
    $password = "P6vGFORta3p5G0G";

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $input_username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($input_password, $user['password'])) {
            $_SESSION['user_id'] = $user['uid'];
            $_SESSION['username'] = $user['username'];

           header("Location: index.php");
           exit();
        } else {

            echo "Invalid username or password";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
