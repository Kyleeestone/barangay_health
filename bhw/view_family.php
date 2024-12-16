<?php
include '../common/auth.php';
include '../common/db.php';

// Fetch all family members and organize them by Household No.
$family_members = $conn->query("SELECT * FROM family_members");

$households = [];
while ($row = $family_members->fetch_assoc()) {
    $households[$row['household_no']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Family Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: #343a40;
            padding-top: 20px;
            height: 100vh;
            position: fixed;
            width: 250px;
        }

        .sidebar h3 {
            color: #fff;
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .nav-link {
            color: #fff;
            font-size: 18px;
            padding: 15px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .active {
            background-color: #16a085;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        .back-btn {
            margin-top: 30px;
            display: inline-block;
            font-size: 18px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>BHW Dashboard</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="household_form.php" class="nav-link">Add Household</a>
            </li>
            <li class="nav-item">
                <a href="view_households.php" class="nav-link">View Households</a>
            </li>
            <li class="nav-item">
                <a href="family_form.php" class="nav-link">Add Family Members</a>
            </li>
            <li class="nav-item">
                <a href="view_family.php" class="nav-link active">View Family Members</a>
            </li>
            <li class="nav-item">
                <a href="../common/logout.php" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1 class="mb-4">Family Members</h1>

        <!-- Display family members for each household -->
        <?php foreach ($households as $household_no => $members): ?>
            <div class="household-section">
                <h2>Household No.: <?php echo htmlspecialchars($household_no); ?></h2>
                <div class="table-responsive">
                    <table id="family-table-<?php echo $household_no; ?>" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Full Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Birthday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                                    <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['age']); ?></td>
                                    <td><?php echo htmlspecialchars($member['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($member['birthday']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
        <?php endforeach; ?>

        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables for each household's family table
            <?php foreach ($households as $household_no => $members): ?>
                $('#family-table-<?php echo $household_no; ?>').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "pageLength": 5
                });
            <?php endforeach; ?>
        });
    </script>

</body>

</html>