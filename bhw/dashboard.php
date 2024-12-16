<?php
include '../common/auth.php';
include '../common/db.php';

// Define records per page
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;

// Get the current page number from the query string (default to 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Search functionality for Household and Family
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_family_query = isset($_GET['search_family']) ? $_GET['search_family'] : '';

// Fetch counts for dynamic updates
$household_count_query = "SELECT COUNT(*) as count FROM households WHERE name LIKE '%$search_query%'";
$family_count_query = "SELECT COUNT(*) as count FROM family_members WHERE full_name LIKE '%$search_family_query%'";

$household_count = $conn->query($household_count_query)->fetch_assoc()['count'];
$family_count = $conn->query($family_count_query)->fetch_assoc()['count'];

// Fetch household details with pagination and search
$household_details_query = "SELECT * FROM households WHERE name LIKE '%$search_query%' LIMIT $start_from, $records_per_page";
$family_details_query = "SELECT * FROM family_members WHERE full_name LIKE '%$search_family_query%' LIMIT $start_from, $records_per_page";

$household_details = $conn->query($household_details_query)->fetch_all(MYSQLI_ASSOC);
$family_details = $conn->query($family_details_query)->fetch_all(MYSQLI_ASSOC);

// Calculate total pages for pagination
$total_pages_household = ceil($household_count / $records_per_page);
$total_pages_family = ceil($family_count / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHW Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100%;
            padding-top: 30px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 15px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar .active {
            background: #1abc9c;
            border-left: 3px solid #16a085;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .stats-card {
            display: flex;
            gap: 20px;
        }

        .stats-card .card {
            flex: 1;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .stats-card .card:hover {
            transform: scale(1.05);
        }

        .search-bar input {
            border-radius: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            width: calc(100% - 40px);
        }

        .search-bar button {
            border-radius: 20px;
            padding: 10px 20px;
            border: none;
            background: #16a085;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-bar button:hover {
            background: #1abc9c;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: #1abc9c;
            border-color: #16a085;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3 class="text-center">BHW Dashboard</h3>
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="household_form.php"><i class="fas fa-users"></i> Add Household</a>
        <a href="view_households.php"><i class="fas fa-table"></i> View Households</a>
        <a href="family_form.php"><i class="fas fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php"><i class="fas fa-list"></i> View Family Members</a>
        <a href="Map.php"><i class="fas fa-map-marked-alt icon"></i> Map</a>
        <a href="../logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="stats-card">
            <div class="card" onclick="window.location='view_households.php'">
                <h5>Households</h5>
                <p><?php echo $household_count; ?> Registered</p>
            </div>
            <div class="card" onclick="window.location='view_family.php'">
                <h5>Family Members</h5>
                <p><?php echo $family_count; ?> Registered</p>
            </div>
        </div>

        <h2 class="mt-5">Household Details</h2>
        <div class="search-bar mb-3">
            <form method="GET">
                <input type="text" name="search" placeholder="Search households" value="<?php echo $search_query; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Head of Household</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($household_details as $household): ?>
                    <tr>
                        <td><?php echo $household['id']; ?></td>
                        <td><?php echo $household['name']; ?></td>
                        <td><?php echo $household['address']; ?></td>
                        <td><a href="view_households.php?id=<?php echo $household['id']; ?>" class="btn btn-info">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages_household; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>

</html>