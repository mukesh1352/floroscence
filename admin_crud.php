<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "new");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['del'])) {
    $delete_id = $_GET['del'];
    
    // Use prepared statement to prevent SQL injection
    $delete_query = "DELETE FROM student WHERE id = ?";
    
    $stmt = mysqli_prepare($connection, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_crud.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
}

// Fetch data for display
$data = [];
$sql = "SELECT id, username, email, password FROM student";
$run = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_assoc($run)) {
    $data[] = $row;
}

// Export data to CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_data.csv"');
    
    $output = fopen('php://output', 'w');

    // Add CSV header
    fputcsv($output, array('ID', 'Username', 'Email', 'Password'));

    // Add data to CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student CRUD Application (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
         body
        {
            background-image: url(https://images.unsplash.com/photo-1601618613229-ec7645fad6fa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aGltYWxheWF8ZW58MHx8MHx8fDA%3D);
        }
        .card {
            margin-top: 20px;
        }

        .btn-success,
        .btn-danger,
        .btn-primary {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h1>Student CRUD Application (Admin)</h1>
                    </div>
                    <div class="card-body">

                        <!-- Add New button -->
                        <button class="btn btn-success"><a href="add.php" class="text-light">Add New</a></button>
                        <br />
                        <br />

                        <!-- Table to display student data -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $id = 1;

                                foreach ($data as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $id++; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['password']; ?></td>
                                        <td>
                                            <button class="btn btn-success"><a href='edit.php?edit=<?php echo $row['id']; ?>' class="text-light">Edit</a></button>
                                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Export to CSV button -->
                        <form method="get" action="">
                            <button type="submit" name="export" class="btn btn-primary">Export to CSV</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                window.location.href = 'admin_crud.php?del=' + id;
            }
        }
    </script>
</body>

</html>
