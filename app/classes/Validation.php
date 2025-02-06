<?php

class Validation {
    
    private $errors = [];

    public function validateFullName($full_name) {
        if (empty($full_name)) {
            $this->errors[] = "Full name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
            $this->errors[] = "Full name can only contain letters and spaces.";
        }
    }
    

    public function validateEmail($email) {
        if (empty($email)) {
            $this->errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format.";
        }
    }

    public function validatePassword($password, $confirm_password) {
        if (empty($password)) {
            $this->errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $this->errors[] = "Password must be at least 6 characters long.";
        }

        if ($password !== $confirm_password) {
            $this->errors[] = "Passwords do not match.";
        }
    }

    public function validateProfilePic($profile_pic) {
        if (!empty($profile_pic['name'])) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $imageFileType = strtolower(pathinfo($profile_pic['name'], PATHINFO_EXTENSION));

            if (!in_array($imageFileType, $allowed_types)) {
                $this->errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function isValid() {
        return empty($this->errors);
    }
}
?>
