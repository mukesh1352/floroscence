<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "new";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Retrieve and sanitize form data
$flora_id = sanitizeInput($_POST['flora_id']);
$scientific_name = sanitizeInput($_POST['scientific_name']);
$added_by = sanitizeInput($_POST['added']);
$medicinal = sanitizeInput($_POST['medicinal']);
$region = sanitizeInput($_POST['region']);

// SQL query to insert data into the table using prepared statement
$sql = "INSERT INTO endangered_flora (flora_id, scientific_name, added_by, medicinal, region)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $flora_id, $scientific_name, $added_by, $medicinal, $region);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the endangered page after successful insertion
    header("Location: endangered.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
