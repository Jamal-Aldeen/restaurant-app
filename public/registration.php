<?php
include(__DIR__ . '/../app/config/database.php'); 
include(__DIR__ . '/../app/classes/Validation.php'); 

$validation = new Validation();
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = trim($_POST['user_type']);
    $profile_pic = $_FILES['profile_pic'];

    $validation->validateFullName($full_name); 
    $validation->validateEmail($email);
    $validation->validatePassword($password, $confirm_password);
    $validation->validateProfilePic($profile_pic);

    if ($validation->isValid()) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "Email is already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $profile_pic_name = "default.jpg"; 

            if (!empty($profile_pic['name'])) {
                $target_dir = "uploads/";
                $profile_pic_name = basename($profile_pic['name']);
                move_uploaded_file($profile_pic['tmp_name'], $target_dir . $profile_pic_name);
            }

            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, profile_pic) VALUES (:full_name, :email, :password, :role, :profile_pic)");
            $stmt->execute([
                'full_name' => $full_name,
                'email' => $email,
                'password' => $hashed_password,
                'role' => $user_type,
                'profile_pic' => $profile_pic_name
            ]);

            $success = "Registration successful! Redirecting to login...";
            header("refresh:3;url=login.php"); 
        }
    } else {
        $errors = $validation->getErrors();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/registr-style.css"> 
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="register-container p-4"> 
        <h2 class="text-center">New Registration</h2>

        <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) {
                    echo "<p>$error</p>";
                } ?>
            </div>
        <?php } ?>

        <?php if ($success) { ?>
            <div class="alert alert-success text-center">
                <p><?php echo $success; ?></p>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">User Type:</label>
                <select class="form-control" name="user_type" required>
                    <option value="User">User</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Picture (Optional):</label>
                <input type="file" class="form-control" name="profile_pic">
            </div>

            <button type="submit" class="btn btn-custom w-100">Register</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
