<?php
include '../common/auth.php';
include '../common/db.php';

$population_data = $conn->query("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 1 ELSE 0 END) AS children,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 10 AND 19 THEN 1 ELSE 0 END) AS adolescents,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 18 THEN 1 ELSE 0 END) AS adults
    FROM households
")->fetch_assoc();

$illness_data = $conn->query("
    SELECT 
        medical_history, 
        COUNT(*) AS count 
    FROM households 
    WHERE medical_history IS NOT NULL AND medical_history != '' 
    GROUP BY medical_history
");

$illness_chart_data = [];
while ($row = $illness_data->fetch_assoc()) {
    $illness_chart_data[] = [
        'illness' => $row['medical_history'],
        'count' => $row['count']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Population Breakdown</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .chart-container {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 text-center">Population Statistics</h1>

        <!-- Population Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Total Population</div>
                    <div class="card-body text-center">
                        <h3><?php echo $population_data['total']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Children</div>
                    <div class="card-body text-center">
                        <h3><?php echo $population_data['children']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Adolescents</div>
                    <div class="card-body text-center">
                        <h3><?php echo $population_data['adolescents']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <div class="card-header">Adults</div>
                    <div class="card-body text-center">
                        <h3><?php echo $population_data['adults']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Illness Chart -->
        <div class="card">
            <div class="card-header">Illness Data</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="illnessChart"></canvas>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <script>
        // Illness Data for Chart
        const illnessData = <?php echo json_encode($illness_chart_data); ?>;
        const illnessLabels = illnessData.map(data => data.illness);
        const illnessCounts = illnessData.map(data => data.count);

        // Render Chart
        const ctx = document.getElementById('illnessChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: illnessLabels,
                datasets: [{
                    label: 'Illness Distribution',
                    data: illnessCounts,
                    backgroundColor: [
                        '#3498db',
                        '#1abc9c',
                        '#f39c12',
                        '#e74c3c',
                        '#9b59b6',
                        '#34495e'
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>

</html>