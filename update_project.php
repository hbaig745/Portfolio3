<?php
$dsn = "mysql:host=localhost;dbname=u_230412350_db";
$username = "u-230412350";
$password = "P6vGFORta3p5G0G";

$title = $description = $startDate = $endDate = $phase = "";
$project_id = $title_err = $description_err = $startDate_err = $endDate_err = $phase_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = trim($_POST["project_id"]);
    if (empty($project_id)) {
        $project_id_err = "Please enter project ID.";
    }

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

    if (empty($project_id_err)) {
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE projects SET title = :title, description = :description, start_date = :startDate, end_date = :endDate, phase = :phase WHERE pid = :project_id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':phase', $phase);
            $stmt->bindParam(':project_id', $project_id);

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
    <title>Update Project</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="forms">
        <h2>Update Project</h2>
        <label>Project_ID:</label><br>
        <input type="number" name="project_id" value="<?php echo $project_id; ?>"><br>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo $title; ?>"><br>
        <label>Description:</label><br>
        <textarea name="description"><?php echo $description; ?></textarea><br>
        <label>Start Date:</label><br>
        <input type="text" name="startDate" value="<?php echo $startDate; ?>"><br>
        <label>End Date:</label><br>
        <input type="text" name="endDate" value="<?php echo $endDate; ?>"><br>
        <label>Phase:</label><br>
        <select name="phase">
            <option value="design" <?php if ($phase == "design") echo "selected"; ?>>Design</option>
            <option value="development" <?php if ($phase == "development") echo "selected"; ?>>Development</option>
            <option value="testing" <?php if ($phase == "testing") echo "selected"; ?>>Testing</option>
            <option value="deployment" <?php if ($phase == "deployment") echo "selected"; ?>>Deployment</option>
            <option value="complete" <?php if ($phase == "complete") echo "selected"; ?>>Complete</option>
        </select><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
