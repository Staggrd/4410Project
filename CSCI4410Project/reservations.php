<?php
    session_start();
    require 'db.php';
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
// Initialize messages
$message = '';

// Handle GET request (display reservations)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT r.reservation_id, u.username, b.title, r.reservation_date, r.status
            FROM reservations r
            JOIN users u ON r.user_id = u.user_id
            JOIN books b ON r.book_id = b.book_id";

    $result = $conn->query($sql);
    $reservations = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }
}

// Handle POST request (add reservation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reservation'])) {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];

    // Change the last variable to a '?' when you want to change the
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, status) VALUES (?, ?, 'Reserved')");
    $stmt->bind_param("ii", $user_id, $book_id);

    if ($stmt->execute()) {
        $message = "Reservation added successfully!";
    } else {
        $message = "Error adding reservation: " . $stmt->error;
    }
    $stmt->close();
}

// Handle DELETE request (delete reservation)
if (isset($_GET['delete_id'])) {
    $reservation_id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        $message = "Reservation deleted successfully!";
    } else {
        $message = "Error deleting reservation: " . $stmt->error;
    }
    $stmt->close();

    // Redirect to remove the delete_id from URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Reservations</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            background-color: #f2f2f2;
        }
        form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Reservation Management</h1>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Display Reservations -->
    <h2>Current Reservations</h2>
    <table>
        <tr>
            <th>User</th>
            <th>Book Title</th>
            <th>Reservation Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?php echo htmlspecialchars($reservation['username']); ?></td>
                <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                <td>
                    <a href="?delete_id=<?php echo $reservation['reservation_id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Add New Reservation Form -->
    <h2>Add New Reservation</h2>
    <form method="post">
        <div>
            <label for="user_id">User ID:</label>
            <input type="number" name="user_id" id="user_id" required>
        </div>
        <div>
            <label for="book_id">Book ID:</label>
            <input type="number" name="book_id" id="book_id" required>
        </div>
        <div>
            <input type="submit" name="add_reservation" value="Add Reservation">
            <br>
            <a href = 'dashboardStudent.php'>Back to Student Dashboard</a>
        </div>
    </form>
</body>
</html>



