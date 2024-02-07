<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flora Database</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 30px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-bottom: 20px;
      background-color: #fff;
    }

    table, th, td {
      border: 1px solid #dee2e6;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    img {
      max-width: 100px;
      max-height: 100px;
    }

    /* Additional style for the search input */
    #search {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Flora Database</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="home.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="location.html">Locations</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="floratype.html">Flora</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="medicine.html">Medicinal</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="quiz.html ">Quiz</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <h2>Flora Database</h2>

    <!-- Search and Filter Controls -->
    <div class="form-group">
      <label for="search">Search:</label>
      <input type="text" class="form-control" id="search" oninput="renderTable()" placeholder="Enter search term">
    </div>

    <div class="form-group">
      <label for="endangeredFilter">Filter by Endangered:</label>
      <select id="endangeredFilter" class="form-control" onchange="renderTable()">
        <option value="all">All</option>
        <option value="1">Endangered</option>
      </select>
    </div>

    <div class="form-group">
      <label for="regionFilter">Filter by Region:</label>
      <select id="regionFilter" class="form-control" onchange="renderTable()">
        <option value="all">All</option>
        <option value="eastern">Eastern</option>
        <option value="western">Western</option>
        <option value="central">Central</option>
      </select>
    </div>

    <!-- Flora Table -->
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>Name</th>
          <th>Scientific Name</th>
          <th>Endangered</th>
          <th>Medicinal</th>
          <th>Region</th>
          <th>Image</th>
        </tr>
      </thead>
      <tbody id="floraTableBody"></tbody>
    </table>
  </div>

  <!-- JavaScript and Bootstrap Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- PHP Script to Fetch Flora Data -->
  <?php
  function fetchFloraData() {
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

      // SQL query to retrieve Flora data
      $sql = "SELECT * FROM flora";
      $result = $conn->query($sql);

      // Fetch all rows as an associative array
      $rows = [];
      while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
      }

      // Close the database connection
      $conn->close();

      // Return the JSON-encoded array
      return json_encode($rows);
  }
  ?>

  <!-- JavaScript to Render Flora Table -->
  <script>
    function renderTable() {
      const searchFilter = document.getElementById('search').value.toLowerCase();
      const endangeredFilter = document.getElementById('endangeredFilter').value;
      const regionFilter = document.getElementById('regionFilter').value;
      const tbody = document.getElementById('floraTableBody');
      const rows = <?php echo fetchFloraData(); ?>;

      tbody.innerHTML = '';

      rows.forEach(item => {
        const nameMatch = item.name.toLowerCase().includes(searchFilter);
        const endangeredMatch = (endangeredFilter === 'all') || (endangeredFilter === '1' && item.endangered === '1');
        const regionMatch = (regionFilter === 'all') || (item.region.toLowerCase() === regionFilter);

        if (nameMatch && endangeredMatch && regionMatch) {
          const row = tbody.insertRow();
          row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.scientific_name}</td>
            <td>${item.endangered === '1' ? 'Yes' : 'No'}</td>
            <td>${item.medicinal === '1' ? 'Yes' : 'No'}</td>
            <td>${item.region}</td>
            <td><img src="${item.image}" alt="${item.name}" class="img-fluid"></td>
          `;
        }
      });
    }

    // Initial table rendering
    renderTable();
  </script>

</body>
</html>
