<!DOCTYPE html>
<html>

<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body style="background-image: url('img/s2.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="black-fill"><br>
        <div class="form-container">
            <h2>Admin Registration</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger text-center">
                    <?php foreach ($errors as $err) {
                        echo "<p>$err</p>";
                    } ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>First Name</label>
                <input type="text" name="fname" required>

                <label>Last Name</label>
                <input type="text" name="lname" required>

                <label>Role</label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="School Management">School Management</option>
                    <option value="Principal">Principal</option>
                    <option value="Vice Principal">Vice Principal</option>
                </select>

                <button type="submit" name="register">Register</button>
            </form>
            <a class="login-link" href="login.php">Already have an account? Login</a>
        </div>

        <div class="message">
            <?php
            if (isset($_POST['register'])) {
                include("db_connect.php");

                $email    = $_POST['email'];
                $password = $_POST['password'];
                $fname    = $_POST['fname'];
                $lname    = $_POST['lname'];
                $role     = $_POST['role'];

                $checkEmail = "SELECT * FROM admins WHERE email='$email'";
                $result = mysqli_query($conn, $checkEmail);

                if (mysqli_num_rows($result) > 0) {
                    echo "<p style='text-align:center;color:red;'>This email is already registered. Please use another one.</p>";
                } else {
                    $sql = "INSERT INTO admins (email, password, first_name, last_name) 
            VALUES ('$email', '$password', '$fname', '$lname', '$role')";

                    if (mysqli_query($conn, $sql)) {
                        echo "<p class='success'>Admin Registered Successfully!</p>";
                    } else {
                        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
                    }
                }
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>