<?php
include '../common/auth.php';
include '../common/db.php';

// Initialize the search query if a search term is submitted
$search_term = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

// Fetch family members based on search criteria
$stmt = $conn->prepare("
    SELECT * FROM family_members
    WHERE full_name LIKE ? OR role LIKE ? OR gender LIKE ?
");
$stmt->bind_param("sss", $search_term, $search_term, $search_term);
$stmt->execute();
$family_members_result = $stmt->get_result();

// Organize family members by Household No.
$households = [];
while ($row = $family_members_result->fetch_assoc()) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 30px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px;
            display: block;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .sidebar .active {
            background: #2980b9;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .household-header {
            background-color: #e9ecef;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-back {
            margin-top: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">BHW Dashboard</h3>
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="household_form.php"><i class="fas fa-users"></i> Add Household</a>
        <a href="view_households.php"><i class="fas fa-table"></i> View Households</a>
        <a href="family_form.php"><i class="fas fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php" class="active"><i class="fas fa-list"></i> View Family Members</a>
        <div class="separator"></div>
        <a href="../common/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Family Members</h1>

        <!-- Search Form -->
        <div class="search-bar">
            <form method="GET" action="view_family.php">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Role, or Gender" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <button type="submit" class="btn btn-primary mt-2">Search</button>
            </form>
        </div>

        <?php if (empty($households)): ?>
            <p>No family members found based on your search criteria.</p>
        <?php else: ?>
            <?php foreach ($households as $household_no => $members): ?>
                <div class="table-container">
                    <h2 class="household-header">Household No.: <?php echo htmlspecialchars($household_no); ?></h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Full Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Birthday</th>
                                <th>Actions</th>
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
                                    <td>
                                        <a href="edit_family_member.php?id=<?php echo $member['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-primary btn-back">Back to Dashboard</a>
    </div>

</body>

</html>