<?php
session_start();
include 'db_connect.php'; // Database connection

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch members from the database
$membersQuery = "SELECT user_id, full_name, phone FROM users WHERE role = 'member'";
$membersResult = $conn->query($membersQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = ($_POST['user_id'] !== "other") ? $_POST['user_id'] : NULL;
    $full_name = (!empty($_POST['full_name']) && $_POST['user_id'] == "other") ? $_POST['full_name'] : NULL;
    $phone_number = $_POST['phone_number'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $payment_method = $_POST['payment_method'];
    $transaction_code = (!empty($_POST['transaction_code']) && $payment_method === "Mpesa") ? $_POST['transaction_code'] : NULL;
    $group_id = 1; // Assuming a single church group

    // Allow NULL values for user_id if "Other" is selected
    $stmt = $conn->prepare("INSERT INTO donations (user_id, group_id, full_name, phone_number, amount, type, payment_method, transaction_code, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissdsss", $user_id, $group_id, $full_name, $phone_number, $amount, $type, $payment_method, $transaction_code);

    if ($stmt->execute()) {
        $message = "Contribution added successfully!";
    } else {
        $message = "Error adding contribution: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Contribution</title>
    <link rel="stylesheet" href="css/nav.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .form-container {
            width: 50%;
            background: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0px 0px 10px gray;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: blue;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkblue;
        }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<div class="form-container">
    <h2>Add Contribution</h2>

    <!-- Display Success or Error Message -->
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="contribution.php" method="POST">

        <!-- Select Member -->
        <label for="member">Select Member:</label>
        <select name="user_id" id="member" required onchange="toggleOtherMember()">
            <option value="">-- Select Member --</option>
            <?php while ($member = $membersResult->fetch_assoc()): ?>
                <option value="<?php echo $member['user_id']; ?>" data-phone="<?php echo $member['phone']; ?>">
                    <?php echo $member['full_name']; ?>
                </option>
            <?php endwhile; ?>
            <option value="other">Other</option>
        </select>

        <!-- Enter Member Name (Only if "Other" is Selected) -->
        <div id="other_member_div" style="display: none;">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name">
        </div>

        <!-- Auto-Filled Phone Number -->
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone_number">

        <!-- Amount -->
        <label for="amount">Amount (KES):</label>
        <input type="number" name="amount" id="amount" required>

        <!-- Type -->
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="donation">Donation</option>
            <option value="offering">Offering</option>
            <option value="tithe">Tithe</option>
        </select>

        <!-- Payment Method -->
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required onchange="toggleTransactionCode()">
            <option value="cash">Cash</option>
            <option value="Mpesa">Mpesa</option>
        </select>

        <!-- Transaction Code (Only for Mpesa) -->
        <div id="transaction_code_div" style="display: none;">
            <label for="transaction_code">Transaction Code:</label>
            <input type="text" name="transaction_code" id="transaction_code">
        </div>

        <br><br><button type="submit">Submit Contribution</button>
    </form>
</div><br><br>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> Hope Chapel. All Rights Reserved.</p>
        <p><a href="privacy_policy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
    </div>
</footer>

<script>
    function toggleOtherMember() {
        let memberSelect = document.getElementById("member");
        let otherMemberDiv = document.getElementById("other_member_div");
        let fullNameInput = document.getElementById("full_name");
        let phoneInput = document.getElementById("phone");

        if (memberSelect.value === "other") {
            otherMemberDiv.style.display = "block";
            fullNameInput.required = true;
            phoneInput.value = ""; // Allow manual phone entry
        } else {
            otherMemberDiv.style.display = "none";
            fullNameInput.required = false;
            let selectedOption = memberSelect.options[memberSelect.selectedIndex];
            phoneInput.value = selectedOption.getAttribute("data-phone") || "";
        }
    }

    function toggleTransactionCode() {
        let paymentMethod = document.getElementById("payment_method").value;
        let transactionCodeDiv = document.getElementById("transaction_code_div");
        let transactionCodeInput = document.getElementById("transaction_code");

        if (paymentMethod === "Mpesa") {
            transactionCodeDiv.style.display = "block";
            transactionCodeInput.required = true;
        } else {
            transactionCodeDiv.style.display = "none";
            transactionCodeInput.required = false;
        }
    }
</script>

</body>
</html>
