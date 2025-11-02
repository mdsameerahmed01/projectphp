<?php
require_once __DIR__ . '/../session_cookie/session_init.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$query = "SELECT * FROM teachers";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Teachers</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Manage Teachers (Admin Panel)</h2>
    <a href="admin_home.php">⬅ Back to Dashboard</a> | <a href="logout.php">Logout</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Qualification</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['subjects']; ?></td>
            <td><?php echo $row['qualification']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td>
                <a href="edit_teacher.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_teacher.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
