<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle signup form submission
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all the fields.";
        exit();
    }

    // Database connection details
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "new";

    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check for duplicate email
    $checkEmailStmt = $conn->prepare("SELECT * FROM student WHERE email=?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        // Duplicate email
        echo "Email is already registered. Please use a different email.";
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $insertStmt = $conn->prepare("INSERT INTO student (username, email, password) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sss", $username, $email, $password);

    if ($insertStmt->execute()) {
        // Successful signup
        echo "<script>
                alert('Account created successfully.');
                setTimeout(function () {
                    window.location.href = 'login.html'; // Redirect to login page
                }, 1000); // 5 seconds delay
              </script>";
        exit();
    } else {
        // Signup failed
        echo "Error during signup. Please try again.";
    }

    $checkEmailStmt->close();
    $insertStmt->close();
    $conn->close();
}
?>
