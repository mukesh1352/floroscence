<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
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

    $stmt = $conn->prepare("SELECT * FROM student WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $plainTextPassword = $row['password'];

        if ($password == $plainTextPassword) {
            // Successful login
            $_SESSION['username'] = $username;

            // Set a session cookie (expire when the browser is closed)
            session_set_cookie_params(0);
            session_regenerate_id(true);

            header("Location: home.html");
            exit();
        } else {
            // Invalid password
            echo "Invalid username or password.";
        }
    } else {
        // Invalid username
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
