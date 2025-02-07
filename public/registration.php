<?php
session_start();
include(__DIR__ . '/../app/config/database.php');
include(__DIR__ . '/../app/classes/Validation.php');

$validation = new Validation();
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = htmlspecialchars(trim($_POST['user_type']));
    $profile_pic = $_FILES['profile_pic'];

    $allowed_roles = ['staff', 'customer'];
    if (!in_array($user_type, $allowed_roles)) {
        $user_type = 'customer';
    }

    $validation->checkEmptyFields([
        "Full Name" => $full_name,
        "Email" => $email,
        "Password" => $password
    ]);
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
                $target_dir = __DIR__ . "/../public/uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $imageFileType = strtolower(pathinfo($profile_pic['name'], PATHINFO_EXTENSION));
                $profile_pic_name = time() . "_" . bin2hex(random_bytes(5)) . "." . $imageFileType;

                if ($profile_pic['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Profile picture must be less than 2MB.";
                } else {
                    move_uploaded_file($profile_pic['tmp_name'], $target_dir . $profile_pic_name);
                }
            }

            if (empty($errors)) {
                $activation_code = md5(uniqid(rand(), true));

                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, profile_pic, activation_code, email_verified) 
                                       VALUES (:full_name, :email, :password, :role, :profile_pic, :activation_code, 0)");
                $stmt->execute([
                    'full_name' => $full_name,
                    'email' => $email,
                    'password' => $hashed_password,
                    'role' => $user_type,
                    'profile_pic' => $profile_pic_name,
                    'activation_code' => $activation_code
                ]);

                $verification_link = "https://yourwebsite.com/verify.php?code=$activation_code";
                $message = "Click on this link to verify your email: $verification_link";
                mail($email, "Verify Your Email", $message);

                $_SESSION['success'] = "Registration successful! Please check your email to verify your account.";
                
                header("Location: ../public/login.php");
                exit();
            }
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
    <title>Register - Restaurant</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2 class="text-center">Register</h2>

        <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (!empty($_SESSION['success'])) { ?>
            <div class="alert alert-success text-center">
                <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select class="form-select" name="user_type" required>
                    <option value="customer">Customer</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Picture (Optional)</label>
                <input type="file" class="form-control" name="profile_pic">
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
