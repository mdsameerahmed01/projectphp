<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "other") {
    header("Location: login.php?msg=Access Denied");
    exit();
}

include("db_connect.php");

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$res = mysqli_query($conn, "SELECT id, title, message, created_by, status FROM notices WHERE status=1 ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYMGA School</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body class="body-home">
    <div class="black-fill"><br>
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary" id="homeNav">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#notice">Notice</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav me-right mb-2 mb-lg-0">
                            <li class="nav-item">
                                <?php if (isset($_SESSION['email'])): ?>
                                    <a class="nav-link" href="logout.php">Logout</a>
                                <?php else: ?>
                                    <a class="nav-link" href="login.php">Login</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-info text-center">
                    <?= htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <section id="home" class="welcome-text d-flex justify-content-center align-items-center flex-column">
                <h4>Welcome to SYMGA School</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </section>

            <section id="about" class="d-flex justify-content-center align-items-center flex-column">
                <div class="card mb-3 card-1">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="img/logo.png" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">About Us</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in
                                    to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-body-secondary">SYMGA School</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="d-flex justify-content-center align-items-center flex-column">
                <form method="POST" action="save_contact.php">
                    <h3>Contact Us</h3>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comments</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </section>

            <section id="notice" class="d-flex justify-content-center align-items-center flex-column">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Notice</h4>
                        <p class="card-text">Track all Notice activities.</p>
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
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <div class="text-center text-light mt-5">
                Copyright &copy; 2025 SYMGA School. All rights reserved.
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>