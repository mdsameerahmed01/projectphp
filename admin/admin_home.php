<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}

include("../db_connect.php");

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$teachers = mysqli_query($conn, "SELECT * FROM teachers ORDER BY id DESC");
$students = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
$admins   = mysqli_query($conn, "SELECT * FROM admins ORDER BY id DESC");
$login_logs = mysqli_query($conn, "SELECT * FROM login_logs ORDER BY login_time DESC");
$contacts = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY created_at DESC");
$res = mysqli_query($conn, "SELECT id, title, message, created_by, status FROM notices WHERE status=1 ORDER BY id DESC");
$att = mysqli_query($conn, "SELECT a.id, s.first_name, s.last_name, a.date, a.status FROM attendance a JOIN students s ON a.student_id = s.id ORDER BY a.date DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SYMGA School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/logo.png">
</head>

<body class="body-home">
    <div class="black-fill"><br>
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary" id="homeNav">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="../img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                        SYMGA School
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link active" href="#dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="#teachers">Teachers</a></li>
                            <li class="nav-item"><a class="nav-link" href="#students">Students</a></li>
                            <li class="nav-item"><a class="nav-link" href="#admins">Admins</a></li>
                            <li class="nav-item"><a class="nav-link" href="#notice">Notice</a></li>
                            <li class="nav-item"><a class="nav-link" href="#login_logs">Login Logs</a></li>
                            <li class="nav-item"><a class="nav-link" href="#attendance">Attendance</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contact_messages">Contact Messages</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings">Settings</a></li>
                        </ul>
                        <ul class="navbar-nav me-right mb-2 mb-lg-0">
                            <li class="nav-item">
                                <?php if (isset($_SESSION['email'])): ?>
                                    <a class="nav-link" href="../logout.php">Logout</a>
                                <?php else: ?>
                                    <a class="nav-link" href="../login.php">Login</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <section id="dashboard" class="welcome-text d-flex justify-content-center align-items-center flex-column mt-4">
                <h3>Welcome, Admin!</h3>
                <p>You are logged in as: <strong><?php echo $_SESSION['email']; ?></strong></p>
            </section>
            <br> <br> <br> <br>

            <section id="teachers" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Teachers Management</h4>
                        <p class="card-text">View, add, edit, or delete teacher records.</p>
                        <a href="../teacher_register.php" class="btn btn-success mb-2">Add New Teacher</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Qualification</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($teachers)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['subjects'] ?></td>
                                        <td><?= $row['qualification'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                        <td>
                                            <a href="edit_teacher.php?id=<?= $row['id'] ?>">Edit</a> |
                                            <a href="delete_teacher.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <br><br><br><br><br><br><br><br><br>

            <section id="students" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Students Management</h4>
                        <p class="card-text">View, add, edit, or delete student records.</p>
                        <a href="../student_register.php" class="btn btn-success mb-2">Add New Student</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Parent Email</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($students)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?= $row['class'] ?></td>
                                        <td><?= $row['parent_email'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                        <td>
                                            <a href="edit_student.php?id=<?= $row['id'] ?>">Edit</a> |
                                            <a href="delete_student.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br><br>

            <section id="admins" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Admins List</h4>
                        <p class="card-text">Manage system admins.</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($admins)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['role'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br>

            <section id="notice" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Notice</h4>
                        <p class="card-text">Track all Notice activities.</p>
                        <a href="create_notice.php" class="btn btn-success mb-2">Create New Notice</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Created_by</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <h2 class="text-light">Notices</h2>
                                <?php while ($row = mysqli_fetch_assoc($res)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['title'] ?></td>
                                        <td><?= $row['message'] ?></td>
                                        <td><?= $row['created_by'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                            <a href="edit_notice.php?id=<?= $row['id'] ?>">Edit</a> |
                                            <a href="delete_notice.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br>

            <section id="login_logs" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">User Login Logs</h4>
                        <p class="card-text">Track all login activities.</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Login Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($login_logs)): ?>
                                    <tr>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['role'] ?></td>
                                        <td><?= $row['login_time'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br>

            <section id="attendance" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Attendance Report</h4>
                        <p class="card-text">View, add, edit, or delete student records.</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($att)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                                        <td><?= $row['date'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br><br>

            <section id="contact_messages" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Contact Messages</h4>
                        <p class="card-text">View all messages submitted by users via the contact form.</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($contacts)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['comments']) ?></td>
                                        <td><?= $row['created_at'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br>

            <section id="settings" class="mt-5 mb-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">System Settings</h4>
                        <p class="card-text">Configure system-wide settings and preferences.</p>
                        <a href="settings.php" class="btn btn-warning">Go to Settings</a>
                    </div>
                </div>
            </section>

            <div class="text-center text-light mt-5 mb-3">
                Copyright &copy; 2025 SYMGA School. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>