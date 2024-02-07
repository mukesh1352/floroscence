<?php
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "new");

// Initialize variables to avoid undefined variable warnings
$uid = $username = $email = $password = "";

// Fetch data for editing
if (isset($_GET['edit'])) {
    $edit = $_GET['edit'];
    $sql = "SELECT * FROM student WHERE id = '$edit'";
    $run = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_array($run)) {
        $uid = $row['id'];
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
    }
}

// Update data on form submission
if (isset($_POST['submit'])) {
    $edit = $_GET['edit'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE student SET username = '$username', email = '$email', password = '$password' WHERE id = '$edit'";

    if (mysqli_query($connection, $sql)) {
        echo '<script>location.replace("index.php")</script>';
    } else {
        echo "Something went wrong: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Crud Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h1>Student Crud Application</h1>
                    </div>
                    <div class="card-body">
                        <form action="edit.php?edit=<?php echo $uid; ?>" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo $username; ?>">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Password" value="<?php echo $password; ?>">
                            </div>
                            <br />
                            <input type="submit" class="btn btn-primary" name="submit" value="Edit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
