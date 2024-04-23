<?php
session_start();


$dsn = "mysql:host=localhost;dbname=u_230412350_db";
$username = "u-230412350";
$password = "P6vGFORta3p5G0G";

$title = $description = $startDate = $endDate = $phase = "";
$startDateErr = $endDateErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $startDate = trim($_POST["startDate"]);
    if (!preg_match("/^\d{2}-\d{2}-\d{2}$/", $startDate)) {
        $startDateErr = "Invalid date format (xx-xx-xx)";
    }
    $endDate = trim($_POST["endDate"]);
    if (!preg_match("/^\d{2}-\d{2}-\d{2}$/", $endDate)) {
        $endDateErr = "Invalid date format (xx-xx-xx)";
    }
    $phase = trim($_POST["phase"]);

    if (!empty($title) && !empty($description) && !empty($startDate) && !empty($endDate) && !empty($phase) && empty($startDateErr) && empty($endDateErr)) {
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO projects (title, description, start_date, end_date, phase, uid) VALUES (:title, :description, :startDate, :endDate, :phase, :uid)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':phase', $phase);
            $stmt->bindParam(':uid', $_SESSION['user_id']);

            $stmt->execute();

            header("location: success.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Project</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="forms">
        <h2>Add Project</h2>
        <label>Title:</label><br>
        <input type="text" name="title"><br>
        <label>Description:</label><br>
        <textarea name="description"></textarea><br>
        <label>Start Date:</label><br>
        <input type="text" name="startDate"><span class="error"><?php echo $startDateErr; ?></span><br>
        <label>End Date:</label><br>
        <input type="text" name="endDate"><span class="error"><?php echo $endDateErr; ?></span><br>
        <label>Phase:</label><br>
        <select name="phase">
            <option value="design">Design</option>
            <option value="development">Development</option>
            <option value="testing">Testing</option>
            <option value="deployment">Deployment</option>
            <option value="complete">Complete</option>
        </select><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
