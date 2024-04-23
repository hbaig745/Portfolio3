<!DOCTYPE html>
<html>
<head>
    <title>Project List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
session_start();
?>

<div class="navbar">
    <?php
if(isset($_SESSION['user_id'])) {
    echo "<div class='flex-direction-column'>";
    echo "<a href='add_project.php'>Add Project</a>";
    echo "<a href='update_project.php'>Update Project</a></div>";
    echo "<h1>Welcome, ".$_SESSION['username']."!</h1>";
    echo "<a href='logout.php'>Log Out</a>";
} else {
    echo "<p>Don't have an account? <a href='registration.php'>Register</a></p>";
    echo "<h2>User Login</h2>";
    echo "<form method='POST' action='login.php' id='login_form'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<button type='submit' name='login'>Login</button>";
    echo "</form>";
}
?>
</div>

<div class='projects'>
    <h2>Search Projects</h2>
    <form method="GET" action="index.php">
        <input type="text" name="search_query" placeholder="Enter title or start date">
        <button type="submit">Search</button>
    </form>
    
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

if(isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE title LIKE :search_query OR start_date LIKE :search_query");
        $stmt->bindValue(':search_query', "%$search_query%");
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<h2>Search Results</h2>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='project'>";
                    echo "<a href='project_details.php?id=".$row["pid"]."'>".$row["title"]."</a><br>";
                    echo "<p>" . $row["start_date"] . "</p>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "</div>";
            }
        } else {
            echo "No results found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    try {
        $stmt = $pdo->query("SELECT * FROM projects");
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='project'>";
                    echo "<a href='project_details.php?id=".$row["pid"]."'>".$row["title"]."</a><br>";
                    echo "<p>" . $row["start_date"] . "</p>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "</div>";
            }
        } else {
            echo "0 results";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
</div>

</body>
</html>