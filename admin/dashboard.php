<?php
include '../common/auth.php';
include '../common/db.php';

// Fetch total population
$totalPopulation = $conn->query("SELECT COUNT(*) as total_population FROM family_members")->fetch_assoc()['total_population'];

// Fetch classifications: Adults, Adolescents, Children
$classifications = $conn->query("
    SELECT 
        SUM(CASE WHEN age >= 18 THEN 1 ELSE 0 END) AS adults,
        SUM(CASE WHEN age BETWEEN 13 AND 17 THEN 1 ELSE 0 END) AS adolescents,
        SUM(CASE WHEN age < 13 THEN 1 ELSE 0 END) AS children
    FROM family_members
")->fetch_assoc();

// Fetch illness data
$illness_data = $conn->query("
    SELECT 
        medical_history, 
        COUNT(*) AS count 
    FROM households 
    WHERE medical_history IS NOT NULL AND medical_history != '' 
    GROUP BY medical_history
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.1rem;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        .sidebar .active {
            background: #2980b9;
        }

        .sidebar .icon {
            font-size: 1.3rem;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            overflow-y: auto;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container input {
            margin-bottom: 10px;
        }

        .card-body {
            padding: 25px;
            text-align: center;
        }

        .card-title {
            font-weight: bold;
        }

        .display-6 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #2c3e50;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">Admin Dashboard</h3>
        <a href="dashboard.php" class="active"><i class="fas fa-home icon"></i> Home</a>
        <a href="view_family.php"><i class="fas fa-users icon"></i> View Family Members</a>
        <a href="view_households.php"><i class="fas fa-table icon"></i> View Households</a>
        <a href="logbook.php"><i class="fas fa-book icon"></i> Logbook</a>
        <a href="Map.php"><i class="fas fa-map-marked-alt icon"></i> Map</a>
        <a href="../logout.php" class="text-danger"><i class="fas fa-sign-out-alt icon"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome, Admin</h1>

        <!-- Population Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Population</h5>
                        <p class="card-text display-6" id="totalPopulation"><?php echo $totalPopulation; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Adults</h5>
                        <p class="card-text display-6"><?php echo $classifications['adults']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">Adolescents</h5>
                        <p class="card-text display-6"><?php echo $classifications['adolescents']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Population Chart -->
        <div class="chart-container">
            <canvas id="populationChart"></canvas>
        </div>

        <!-- Illness Data -->
        <h2>Illness Data</h2>
        <div class="table-container">
            <input type="text" id="searchIllness" class="form-control" placeholder="Search illness...">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Illness</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody id="illnessTableBody">
                    <?php while ($row = $illness_data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['medical_history']); ?></td>
                            <td><?php echo htmlspecialchars($row['count']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Population Chart
        const ctx = document.getElementById('populationChart').getContext('2d');
        const populationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Adults', 'Adolescents', 'Children'],
                datasets: [{
                    label: 'Population',
                    data: [
                        <?php echo $classifications['adults']; ?>,
                        <?php echo $classifications['adolescents']; ?>,
                        <?php echo $classifications['children']; ?>
                    ],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Search Functionality
        document.getElementById('searchIllness').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#illnessTableBody tr');
            rows.forEach(row => {
                const illness = row.cells[0].textContent.toLowerCase();
                if (illness.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>