<!DOCTYPE html>
<html>

<head>
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body style="background-image: url('img/s2.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="black-fill"><br>
        <div class="form-container">
            <h2>Teacher Registration</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger text-center">
                    <?php foreach ($errors as $err) {
                        echo "<p>$err</p>";
                    } ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>First Name</label>
                <input type="text" name="fname" required>

                <label>Last Name</label>
                <input type="text" name="lname" required>

                <label>Gender</label>
                <select name="gender">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>

                <label>Date of Birth</label>
                <input type="date" name="dob">

                <label>Address</label>
                <input type="text" name="address">

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Contact Number</label>
                <input type="text" name="contact">

                <label>Subject(s) of Expertise</label>
                <input type="text" name="subjects">

                <label>Educational Qualifications</label>
                <input type="text" name="qualification">

                <label>Teacher ID</label>
                <input type="text" name="teacher_id" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" name="register">Register</button>
            </form>
            <a class="login-link" href="login.php">Already have an account? Login</a>
        </div>

        <div class="message">

            <?php
            if (isset($_POST['register'])) {
                include("db_connect.php");

                $fname        = $_POST['fname'];
                $lname        = $_POST['lname'];
                $gender       = $_POST['gender'];
                $dob          = $_POST['dob'];
                $address      = $_POST['address'];
                $email        = $_POST['email'];
                $contact      = $_POST['contact'];
                $subjects     = $_POST['subjects'];
                $qualification = $_POST['qualification'];
                $teacher_id   = $_POST['teacher_id'];
                $password     = $_POST['password'];

                $check = "SELECT * FROM teachers WHERE email='$email' OR teacher_id='$teacher_id'";
                $result = mysqli_query($conn, $check);

                if (mysqli_num_rows($result) > 0) {
                    echo "<p style='text-align:center;color:red;'>This Email or Teacher ID is already registered. Please use another one.</p>";
                } else {
                    $sql = "INSERT INTO teachers 
        (first_name, last_name, gender, dob, address, email, contact, subjects, qualification, teacher_id, password)
        VALUES 
        ('$fname', '$lname', '$gender', '$dob', '$address', '$email', '$contact', '$subjects', '$qualification', '$teacher_id', '$password')";

                    if (mysqli_query($conn, $sql)) {
                        echo "<p class='success'>Teacher Registered Successfully!</p>";
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