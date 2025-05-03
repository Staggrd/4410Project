<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch current user info
$stmt = $conn->prepare("SELECT username, email, phone, password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $phone, $user_id);

    if ($stmt->execute()) {
        $message = "Details updated successfully.";
    } else {
        $message = "Error updating details: " . $stmt->error;
    }
    $stmt->close();

    // Handle password change
    if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        if (password_verify($current_password, $user['password'])) {
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_password, $user_id);
            if ($stmt->execute()) {
                $message .= " Password changed successfully.";
            } else {
                $message .= " Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message .= " Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Your Details</title>
</head>
<body>
<h2>Edit Your Account Details</h2>

<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>

    <h3>Change Password</h3>
    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password"><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password"><br>

    <input type="submit" value="Update Details">
</form>

<a href="dashboardStudent.php">Back to Dashboard</a>
</body>
</html>
