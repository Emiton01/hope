<?php
session_start();
include 'db_connect.php'; // Database connection

// Check if user is logged in and is a leader
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    header("Location: login.php");
    exit();
}

$error = $success = "";

// Fetch available church groups
$groups_query = "SELECT group_id, group_name FROM church_groups";
$groups_result = $conn->query($groups_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id = $_POST['group_id']; 
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $added_by = $_SESSION['user_id']; // Get the logged-in leader's ID

    if (!empty($group_id) && !empty($description) && !empty($amount)) {
        // Insert expense into the database
        $sql = "INSERT INTO expenses (group_id, description, amount, added_by) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdi", $group_id, $description, $amount, $added_by);

        if ($stmt->execute()) {
            $success = "Expense added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $error = "All fields are required!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Church Group Expenses</title>
    <link rel="stylesheet" href="css/expense.css">
    <link rel="stylesheet" href="css/nav.css">
    <style>
        .navbar {
    width: 100%;
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


    </style>

</head>
<body>

<header class="site-header">
    <img src="images/logo.jpg" alt="Church Logo" class="logo">
</header>

        <h2>Leader Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION['full_name']; ?> (Leader)</p>
        <a href="logout.php">Logout</a>

 <!-- Navigation Bar -->
<div class="navbar">
    <a href="leader_dashboard.php">Dashboard</a>
    <a href="manage_donation.php">Donations</a>
    <a href="contribution.php">contributions</a>
    <a href="expense.php">Expenses</a>
    <a href="members_list.php">Members</a>
</div>

    <div class="container">
        <h2>Record Church Group Expense</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p class='success'>$success</p>"; } ?>

        <form method="POST">
            <label>Select Group:</label>
            <select name="group_id" required>
                <option value="">-- Select Church Group --</option>
                <?php while ($row = $groups_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['group_id']; ?>"><?php echo $row['group_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Description:</label>
            <input type="text" name="description" placeholder="Enter Expense Description" required>

            <label>Amount (KES):</label>
            <input type="number" name="amount" placeholder="Enter Amount" required>

            <button type="submit">Add Expense</button>
        </form>

    </div><br><br>

    <!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> Hope Chapel. All Rights Reserved.</p>
        <p><a href="privacy_policy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
    </div>
</footer>

</body>
</html>
