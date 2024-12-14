<?php
include_once __DIR__ . '/../../config/settings-config.php';
require_once __DIR__ . '/../../database/dbconnection.php';
require_once __DIR__ . '/../../src/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class IMS
{
    private $conn;
    private $smtp_email;
    private $smtp_password;
    private $settings;

    public function __construct()
    {
        $this->settings = new SystemConfig();
        $this->smtp_email = $this->settings->getSmtpEmail();
        $this->smtp_password = $this->settings->getSmtpPassword();

        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function sendOtp($otp, $email)
    {

        if ($email == NULL) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'No email found'];
            header("Location: ../../");
            exit;
        } else {

            $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $stmt->fetch(pdo::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email taken'];
                header("Location: ../../");
                exit;
            } else {
                $_SESSION['OTP'] = $otp;

                $subject = "OTP Verification";
                $message = "
                            <h1>OTP Verification</h1>
							<p>Hello, $email</p>
							<p>Your OTP is: $otp</p>
							<p>If you didn't request an OTP, please ignore this email.</p>
							<p>Thank you</p>
                ";

                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'OTP Sent'];
                header("Location: ../../verify-otp.php");
                exit;
            }
        }
    }

    public function verifyOtp($username, $email, $password, $otp)
    {
        if ($otp == $_SESSION['OTP']) {
            unset($_SESSION['OTP']);

            $this->addUser($username, $email, $password);

            $subject = "Verification Success";
            $message = "
                        <h1>Welcome</h1>
						<p>Hello, <strong>$email</strong></p>
						<p>Welcome to our System</p>
						<p>If you did not sign up for an account, you can safely ignore this email.</p>
						<p>Thank you!</p>
            ";

            $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Thank you'];
            header("Location: ../../");
            exit;

            unset($_SESSION['not_verify_username']);
            unset($_SESSION['not_verify_email']);
            unset($_SESSION['not_verify_password']);
        } else if ($otp == NULL) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'No otp found'];
            header("Location: ../../verify-otp.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid'];
            header("Location: ../../verify-otp.php");
            exit;
        }
    }

    public function addUser($username, $email, $password)
    {
        $stmt = $this->runQuery("SELECT * FROM users WHERE username = :username");
        $stmt->execute(array(":username" => $username));

        if ($stmt->rowCount() == 1) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Username already exists'];
            header("Location: ../../");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid email format'];
            header("Location: ../../");
            exit;
        }

        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email already exists'];
            header("Location: ../../");
            exit;
        }

        if (!preg_match('/^[A-Z]/', $password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must start with a capital letter'];
            header("Location: ../../");
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must be at least 8 characters long'];
            header("Location: ../../");
            exit;
        }

        if (!preg_match('/[\W_]/', $password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must contain at least one symbol'];
            header("Location: ../../");
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->runQuery('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $execute = $stmt->execute(array(":username" => $username, ":email" => $email, ":password" => $hashed_password));

        if ($execute) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'User Added'];
            header("Location: ../../");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error adding user'];
            header("Location: ../../");
            exit;
        }
    }

    public function login($email, $password)
    {
        if (empty($email)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Enter Email'];
            header("Location: ../../");
            exit;
        }

        if (empty($password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Enter Password'];
            header("Location: ../../");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid email format'];
            header("Location: ../../");
            exit;
        }

        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email AND active = 'active'");
        $stmt->execute(array(":email" => $email));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 1) {
            if (password_verify($password, $userRow['password'])) {
                $role = $userRow['username'];
                $activiy = "$role has successfully logged in";
                $user_id = $userRow['id'];

                $_SESSION['session'] = $user_id;
                $this->logs($activiy, $user_id);
                $this->redirect($userRow['role']);
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Incorrect Password'];
                header("Location: ../../");
                exit;
            }
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'User Not Found'];
            header("Location: ../../");
            exit;
        }
    }

    public function forgotPassword($email)
    {
        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() == 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email not found.'];
            header("Location: ../../forgot-password.php");
            exit;
        }

        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $userRow['id'];

        $resetToken = bin2hex(random_bytes(32));

        $stmt = $this->runQuery("UPDATE users SET reset_token = :reset_token WHERE id = :user_id");
        $stmt->execute(array(":reset_token" => $resetToken, ":user_id" => $userId));

        $resetLink = "http://localhost/ims/reset-password.php?token=$resetToken&id=$userId";
        $message = "Please click on the following link to reset your password: <a href='$resetLink'>Click Here!</a>";
        $subject = "Password Reset Request";

        $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);

        $_SESSION['alert'] = ['type' => 'success', 'message' => 'A password reset link has been sent to your email.'];
        header("Location: ../../forgot-password.php");
        exit;
    }

    public function validateResetToken($userId, $token)
    {
        $stmt = $this->runQuery("SELECT * FROM users WHERE id = :user_id AND reset_token = :reset_token");
        $stmt->execute(array(":user_id" => $userId, ":reset_token" => $token));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return true;
        }

        return false;
    }

    public function resetPassword($userId, $newPassword)
    {
<<<<<<< HEAD
=======

>>>>>>> 7dea1daa3ddb8541838b659b04061f3c20218798
        $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->runQuery("UPDATE users SET password = :password, reset_token = NULL WHERE id = :user_id");
        $stmt->execute(array(":password" => $hash_password, ":user_id" => $userId));

        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Your password has been reset successfully.'];
        header("Location: index.php");
        exit;
    }


    public function send_email($email, $message, $subject, $smtp_email, $smtp_password)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->addAddress($email);
        $mail->Username = $smtp_email;
        $mail->Password = $smtp_password;
        $mail->setFrom($smtp_email, "no-reply");
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->Send();
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function logs($activiy, $user_id)
    {
        $stmt = $this->runQuery("INSERT INTO  logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array("user_id" => $user_id, ":activity" => $activiy));
    }

    public function redirect($role)
    {
        if ($role === "admin") {
            header("Location: ../admin/");
        } elseif ($role === "user") {
            header("Location: ../user/");
        } else {
            header("Location: ../../");
        }
        exit;
    }

    public function isUserLogged()
    {
        if (isset($_SESSION['session'])) {
            return true;
        }
    }

    public function signOut()
    {
        $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
        $stmt->execute(array(":id" => $_SESSION['session']));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $role = $userRow['username'];
        $activity = "$role has successfully signed out";
        $user_id = $userRow['id'];

        $_SESSION['session'] = $user_id;
        $this->logs($activity, $user_id);

        unset($_SESSION['session']);
        header("Location: ../../");
        exit;
    }
}

if (isset($_POST['btn-signup'])) {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {

        $_SESSION['not_verify_username'] = trim($_POST['username']);
        $_SESSION['not_verify_email'] = trim($_POST['email']);
        $_SESSION['not_verify_password'] = trim($_POST['password']);

        $email = trim($_POST['email']);;
        $otp = rand(100000, 999999);

        $addAdmin = new IMS();
        $addAdmin->sendOtp($otp, $email);
    }
}

if (isset($_POST['btn-verify'])) {
    $username = $_SESSION['not_verify_username'];
    $email = $_SESSION['not_verify_email'];
    $password = $_SESSION['not_verify_password'];

    $otp = trim($_POST['otp']);

    $adminVerify = new IMS();
    $adminVerify->verifyOtp($username, $email, $password, $otp);
}

if (isset($_POST['btn-signin'])) {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loginUser = new IMS();
        $loginUser->login($email, $password);
    }
}

if (isset($_GET['signout'])) {
    $signout = new IMS();
    $signout->signOut();
}

if (isset($_POST['btn-forgot-password'])) {
    $email = trim($_POST['email']);

    $forgotPassword = new IMS();
    $forgotPassword->forgotPassword($email);
}
