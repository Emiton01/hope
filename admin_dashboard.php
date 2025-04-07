<?php
session_start();
include 'db_connect.php';

// Ensure only the admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users except admin
$sql = "SELECT user_id, full_name, email, phone, role FROM users WHERE role != 'admin'";
$result = $conn->query($sql);

// Handle role updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    $update_sql = "UPDATE users SET role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_role, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('User role updated successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "Error updating role: " . $conn->error;
    }
    
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">

</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['full_name']; ?> (Admin)</p>
    <a href="logout.php">Logout</a>

    <h3>Manage Users</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                        <select name="new_role">
                            <option value="member" <?php if ($row['role'] == 'member') echo "selected"; ?>>Member</option>
                            <option value="leader" <?php if ($row['role'] == 'leader') echo "selected"; ?>>Leader</option>
                        </select>
                        <button type="submit" name="update_role">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>