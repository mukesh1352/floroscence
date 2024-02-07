<?php
// Assuming you have a database connection established

// Replace these values with your actual database connection details
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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $newPassword = $_POST["new_password"];

    // Check if the username exists in the database
    $checkUserQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // Update the password if the username exists
        $updatePasswordQuery = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
        if ($conn->query($updatePasswordQuery) === TRUE) {
            // Password updated successfully, redirect to another page
            header("Location: admin.html");
            exit(); // Ensure that no further code is executed after the redirection
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Username not found";
    }
}

// Close the database connection
$conn->close();
?>
