<?php
require_once __DIR__ . '/../session_cookie/session_init.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "teacher") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}

include("../db_connect.php");

$students = mysqli_query($conn, "SELECT * FROM students ORDER BY id ASC");
if (!$students) {
    die("DB Error: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $attendanceData = $_POST['attendance'] ?? [];

    if (empty($attendanceData)) {
        $error = "Please mark attendance for at least one student.";
    } else {
        foreach ($attendanceData as $student_id => $status) {
            $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $student_id, $date, $status);
            $stmt->execute();
        }
        header("Location: teacher_mark_attendance.php?msg=Attendance Marked Successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="body-home">
    <div class="black-fill">
        <div class="form-container">
            <h2>Mark Attendance</h2>

            <!-- Success / Error Messages -->
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['msg'])): ?>
                <p style="color:green;"><?= htmlspecialchars($_GET['msg']); ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Select Date:</label>
                <input type="date" name="date" required><br><br>

                <table border="1" cellpadding="10" width="100%">
                    <tr>
                        <th>Student</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                    <?php while ($s = mysqli_fetch_assoc($students)): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['first_name'] . " " . $s['last_name']) ?></td>
                            <td><input type="radio" name="attendance[<?= $s['id'] ?>]" value="Present" required></td>
                            <td><input type="radio" name="attendance[<?= $s['id'] ?>]" value="Absent"></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <br>
                <button type="submit">Save Attendance</button>
            </form>

            <a class="login-link" href="teacher_home.php">⬅ Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
