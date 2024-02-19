<?php
// Include the database connection file
$mysqli = require __DIR__ . "/db.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Sanitize input
    $username = mysqli_real_escape_string($mysqli, $username);

    // Prepare and execute SQL statement to fetch user from database
    $stmt = $mysqli->prepare("SELECT UserID, Username, Password FROM UserCredentials WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["Password"])) {
            // Password correct, start session and redirect to dashboard
            session_start();
            $_SESSION["userID"] = $user["UserID"];
            $_SESSION["username"] = $user["Username"];
            header("Location: dashboard.php");
            exit();
        }
    }

    // Redirect back to login with error message
    header("Location: login.php?error=Incorrect%20username%20or%20password");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fuel Management System - Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        // Display error message if present
        if (isset($_GET['error'])) {
            $error_message = $_GET['error'];
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" data-validate="required"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" data-validate="required"><br><br>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
