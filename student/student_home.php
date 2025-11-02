<?php
require_once __DIR__ . '/../session_cookie/session_init.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$res = mysqli_query($conn, "SELECT id, title, message, created_by, status FROM notices WHERE status=1 ORDER BY id DESC");
$att = mysqli_query($conn, "
    SELECT a.id, s.first_name, s.last_name, a.date, a.status
    FROM attendance a
    JOIN students s ON a.student_id = s.id
    ORDER BY a.date DESC
");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SYMGA School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/logo.png">
</head>

<body class="body-home">
    <div class="black-fill"><br>
        <div class="container">
            <nav class="navbar navbar-expand-lg" id="homeNav">
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
                            <li class="nav-item"><a class="nav-link" href="#notice">Notice</a></li>
                            <li class="nav-item"><a class="nav-link" href="#attendance">Attendance</a></li>
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
                <h3>Welcome, Student!</h3>
                <p>You are logged in as: <strong><?php echo $_SESSION['email']; ?></strong></p>
            </section>
            <br> <br> <br> <br>

            <section id="notice" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Notice</h4>
                        <p class="card-text">Track all Notice activities.</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-light">
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
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br>

            <section id="attendance" class="mt-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Attendance Report</h4>
                        <p class="card-text">View, add, edit, or delete student records.</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-light">
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
                </div>
            </section>
            <br><br><br><br><br><br><br><br><br><br><br>

            <div class="text-center text-light mt-5 mb-3">
                Copyright &copy; 2025 SYMGA School. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>