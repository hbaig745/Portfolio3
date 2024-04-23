<!DOCTYPE html>
<html>
<head>
    <title>Project Details</title>
</head>
<body>

<?php
$dsn = "mysql:host=localhost;dbname=u_230412350_db";
$username = "u-230412350";
$password = "P6vGFORta3p5G0G";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM projects INNER JOIN users ON users.uid = projects.uid WHERE pid=:pid");
        $stmt->bindParam(':pid', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<h2>".$row["title"]."</h2>";
                echo "<p>Project Number: ".$row["pid"]."</p>";
                echo "<p>".$row["short_description"]."</p>";
                echo "<p>Start Date: ".$row["start_date"]."</p>";
                echo "<p>End Date: ".$row["end_date"]."</p>";
                echo "<p>Phase: ".$row["phase"]."</p>";
                echo "<p>Description: ".$row["description"]."</p>";
                echo "<p>User ID: ".$row["uid"]."</p>";
                echo "<p>User email: ".$row["email"]."</p>";
            }
        } else {
            echo "Project not found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request";
}
?>
<a href="index.php">Back</a>
</body>
</html>