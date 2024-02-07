<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if username and password are set
    if (isset($_GET["username"]) && isset($_GET["password"])) {
        // Get username and password from the form
        $username = sanitize_input($_GET["username"]);
        $password = sanitize_input($_GET["password"]);

        // SQL query to check user credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, login successful
            echo "Login successful!";
            
            // Redirect to the home.html page
            header("Location: index.php");
            exit(); // Make sure to exit after redirection
        } else {
            // User not found or incorrect credentials
            echo "Invalid username or password!";
        }
    } else {
        // Handle the case when username or password is not set
        echo "Username and password are required!";
    }
}

// Close connection
$conn->close();
?>
