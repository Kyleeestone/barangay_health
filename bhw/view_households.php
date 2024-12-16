<?php
include '../common/auth.php';
include '../common/db.php';

$households = $conn->query("SELECT * FROM households ORDER BY household_no");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Households</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            position: fixed;
            height: 100vh;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            text-align: center;
            font-size: 1.8rem;
            color: #ecf0f1;
            margin-bottom: 40px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 3px solid transparent;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #16a085;
            border-left: 3px solid #1abc9c;
        }

        .sidebar .active {
            background: #1abc9c;
            border-left: 3px solid #16a085;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
            text-align: left;
            margin-bottom: 20px;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table.dataTable {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3>BHW Dashboard</h3>
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="household_form.php"><i class="fas fa-users"></i> Add Household</a>
        <a href="view_households.php" class="active"><i class="fas fa-table"></i> View Households</a>
        <a href="family_form.php"><i class="fas fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php"><i class="fas fa-list"></i> View Family Members</a>
        <div class="separator"></div>
        <a href="../common/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h1>Household Profiles</h1>
        <div class="table-container">
            <table id="householdsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Household No</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Sex</th>
                        <th>Civil Status</th>
                        <th>Education</th>
                        <th>Religion</th>
                        <th>Ethnicity</th>
                        <th>4Ps Member</th>
                        <th>PhilHealth ID</th>
                        <th>Medical History</th>
                        <th>Water Source</th>
                        <th>Toilet Facility</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $households->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['household_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['civil_status']; ?></td>
                            <td><?php echo $row['education']; ?></td>
                            <td><?php echo $row['religion']; ?></td>
                            <td><?php echo $row['ethnicity']; ?></td>
                            <td><?php echo $row['is_4ps_member'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $row['philhealth_id']; ?></td>
                            <td><?php echo $row['medical_history']; ?></td>
                            <td><?php echo $row['water_source']; ?></td>
                            <td><?php echo $row['toilet_facility']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#householdsTable').DataTable({
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries available",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });
    </script>
</body>

</html>