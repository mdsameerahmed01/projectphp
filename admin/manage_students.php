<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$query = "SELECT * FROM students";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Manage Students (Admin Panel)</h2>
    <a href="admin_home.php">⬅ Back to Dashboard</a> | <a href="logout.php">Logout</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Class</th>
            <th>Parent Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['parent_email']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td>
                <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_student.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
