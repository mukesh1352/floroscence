<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the AJAX request
$username = $_POST['username'];
$marks = $_POST['marks'];

// Insert data into the database
$sql = "INSERT INTO quiz(username, marks) VALUES ('$username', $marks)";

if ($conn->query($sql) === TRUE) {
    echo "Results saved successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
