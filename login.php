<?php
session_start();
include 'db_connect.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, full_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $full_name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store user session data
            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role;

            // Redirect based on user role
            switch ($role) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'leader':
                    header("Location: leader_dashboard.php");
                    break;
                case 'member':
                    header("Location: member_dashboard.php");
                    break;
                default:
                    $error = "Invalid user role!";
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that email!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
img {
    width: 100%;
    height: 100%;
}


</style>
</head>
<body>
    <div class="left-side">

    <img src="images/logo.jpg" alt="Paris" style="">
        

    </div>

    <div class="right-side">

        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <a href="signup.php">Don't have an account? Sign up</a>
        </div>
    </div>
</body>
</html>