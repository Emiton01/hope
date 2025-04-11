<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user details
$query = "SELECT gender, age, marital_status, num_children, occupation, residential_address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If form is submitted, update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $marital_status = $_POST['marital_status'];
    $children = $_POST['children'];
    $occupation = $_POST['occupation'];
    $address = $_POST['address'];

    // Update query
    $update_query = "UPDATE users SET gender=?, age=?, marital_status=?, num_children=?, occupation=?, residential_address=? WHERE user_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sissssi", $gender, $age, $marital_status, $children, $occupation, $address, $user_id);

    if ($stmt->execute()) {
        $message = "<p class='success'>Profile updated successfully.</p>";
        // Refresh the user data
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $message = "<p class='error'>Error updating profile.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Complete Profile</title>
    <link rel="stylesheet" href="css/complete_profile.css">
    <link rel="stylesheet" href="css/member_dashboard.css">
    <script defer src="js/sidebar.js"></script>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <a href="member_dashboard.php">Dashboard</a>
        <a href="view_expenses.php">View Expenses</a>
        <a href="make_contribution.php">Make Contribution</a>
        <a href="view_contributions.php" >My Contributions</a>
        <a href="complete_profile.php" >Complete Profile</a>
        <a href="logout.php">Logout</a>
    </div>

<div class="profile-container">
    <h2>Complete Your Profile</h2>
    <?php echo $message; ?>

    <form method="POST">
        <label>Gender:</label>
        <select name="gender">
            <option value="Male" <?php if ($user['gender'] == "Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if ($user['gender'] == "Female") echo "selected"; ?>>Female</option>
        </select>

        <label>Age:</label>
        <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>

        <label>Marital Status:</label>
        <select name="marital_status">
            <option value="Single" <?php if ($user['marital_status'] == "Single") echo "selected"; ?>>Single</option>
            <option value="Married" <?php if ($user['marital_status'] == "Married") echo "selected"; ?>>Married</option>
            <option value="Divorced" <?php if ($user['marital_status'] == "Divorced") echo "selected"; ?>>Divorced</option>
        </select>

        <label>Number of Children:</label>
        <input type="number" name="children" value="<?php echo htmlspecialchars($user['num_children']); ?>">

        <label>Occupation:</label>
        <input type="text" name="occupation" value="<?php echo htmlspecialchars($user['occupation'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label>Address:</label>

<input type="text" name="address" value="<?php echo htmlspecialchars($user['residential_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
