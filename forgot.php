<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $newPassword = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($newPassword)) {
        echo "Please enter email and new password.";
        exit();
    }

    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "new";

    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Email found, update the password
        $updateStmt = $conn->prepare("UPDATE student SET password=? WHERE email=?");
        $updateStmt->bind_param("ss", $newPassword, $email);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            // Password updated successfully
            echo "Password changed successfully.";

            // Redirect to the login page
            header("Location: login.html");
            exit();
        } else {
            // Failed to update password
            echo "Failed to change password.";
        }

        $updateStmt->close();
    } else {
        // Email not found
        echo "Email not found in the database.";
    }

    $stmt->close();
    $conn->close();
}
?>
