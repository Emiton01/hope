<?php
session_start();
include 'db_connect.php'; // Database connection

// Ensure only leaders access this page
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch total members
$result = $conn->query("SELECT COUNT(user_id) AS total_members FROM users");
$row = $result->fetch_assoc();
$total_members = $row['total_members'] ?? 0;

// Fetch total donations
$result = $conn->query("SELECT SUM(amount) AS total_donations FROM donations");
$row = $result->fetch_assoc();
$total_donations = $row['total_donations'] ?? 0;

// Fetch total tithes
$result = $conn->query("SELECT SUM(amount) AS total_tithes FROM donations WHERE type = 'tithe'");
$row = $result->fetch_assoc();
$total_tithes = $row['total_tithes'] ?? 0;

// Fetch total offerings
$result = $conn->query("SELECT SUM(amount) AS total_offerings FROM donations WHERE type = 'offering'");
$row = $result->fetch_assoc();
$total_offerings = $row['total_offerings'] ?? 0;

// Fetch total expenses
$result = $conn->query("SELECT SUM(amount) AS total_expenses FROM expenses");
$row = $result->fetch_assoc();
$total_expenses = $row['total_expenses'] ?? 0;

// Calculate net balance
$net_balance = $total_donations - $total_expenses;

// Fetch ongoing projects
$projects = [];
$result = $conn->query("SELECT name, progress FROM projects WHERE status = 'ongoing'");
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leader Dashboard</title>
    <link rel="stylesheet" href="css/leader_dashboard.css">
    <link rel="stylesheet" href="css/nav.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>


.chart-container {
    width: 40%; /* Adjust width as needed */
    max-width: 500px; /* Ensures it doesn't get too big */
    margin: auto; /* Centers the chart */
}

/* Footer Styles */
.footer {
    width: 100%;
    background: #007bff;
    color: white;
    text-align: center;
    padding: 15px 0;
    position: relative; /* Change to 'fixed' if you want it always at the bottom */
    bottom: 0;
    left: 0;
}

.footer a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    font-weight: bold;
}

.footer a:hover {
    text-decoration: underline;
}
.navbar {
    width: 97vw;
}

    </style>
</head>
<body>

<header class="site-header">
    <img src="images/logo.jpg" alt="Church Logo" class="logo">
</header>



<div class="center-container">
    <h2>Leader Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['full_name']; ?> (Leader)</p>
    <a href="logout.php">Logout</a>
</div>


<!-- Navigation Bar -->
<div class="navbar">
    <a href="leader_dashboard.php">Dashboard</a>
    <a href="manage_donation.php">Donations</a>
    <a href="contribution.php">contributions</a>
    <a href="expense.php">Expenses</a>
    <a href="members_list.php">Members</a>
</div>
<!-- Summary Cards -->
<div class="summary-cards">
    <div class="card">
        <h3>Total Members</h3>
        <p><?php echo $total_members; ?></p>
    </div>
    <div class="card">
        <h3>Total Contributions</h3>
        <p>Ksh <?php echo number_format($total_donations, 2); ?></p>
    </div>
    <div class="card">
        <h3>Total Expenses</h3>
        <p>Ksh <?php echo number_format($total_expenses, 2); ?></p>
    </div>
    <div class="card">
        <h3>Net Balance</h3>
        <p>Ksh <?php echo number_format($net_balance, 2); ?></p>
    </div>
</div>

<!-- Charts -->
<div class="chart-container">
    <canvas id="financeChart"></canvas>
</div>

<br><br>

<div class="chart-container">
    <canvas id="pieChart"></canvas>
</div><br><br>

<!-- Ongoing Projects 
<h3>Ongoing Church Projects</h3>
<table border="1">
    <tr>
        <th>Project Name</th>
        <th>Progress</th>
    </tr>
    <?php if (!empty($projects)) {
        foreach ($projects as $project) { ?>
            <tr>
                <td><?php echo $project['name']; ?></td>
                <td><?php echo $project['progress']; ?>%</td>
            </tr>
    <?php } 
    } else { ?>
        <tr>
            <td colspan="2">No ongoing projects</td>
        </tr>
        -->
    <?php } ?>
</table>

<!-- JavaScript for Charts -->
<script>
    // Bar Chart for Contributions
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Donations', 'Tithes', 'Offerings', 'Expenses'],
            datasets: [{
                label: 'Church Finances (Ksh)',
                data: [<?php echo $total_donations; ?>, <?php echo $total_tithes; ?>, <?php echo $total_offerings; ?>, <?php echo $total_expenses; ?>],
                backgroundColor: ['green', 'blue', 'purple', 'red']
            }]
        }
    });

    // Pie Chart for Contribution Breakdown
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Tithes', 'Offerings', 'Other Donations'],
            datasets: [{
                data: [<?php echo $total_tithes; ?>, <?php echo $total_offerings; ?>, <?php echo ($total_donations - $total_tithes - $total_offerings); ?>],
                backgroundColor: ['blue', 'purple', 'orange']
            }]
        }
    });
</script>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> Hope Chapel. All Rights Reserved.</p>
        <p><a href="privacy_policy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
    </div>
</footer>


</body>
</html>
