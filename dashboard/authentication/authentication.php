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

    public function addUser($username, $email, $password)
    {
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'All fields are required'];
            header("Location: ../../index.php");
            exit;
        }

        $stmt = $this->runQuery("SELECT * FROM users WHERE username = :username");
        $stmt->execute(array(":username" => $username));

        if ($stmt->rowCount() == 1) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Username already exists'];
            header("Location: ../../index.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid email format'];
            header("Location: ../../index.php");
            exit;
        }

        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email already exists'];
            header("Location: ../../index.php");
            exit;
        }

        if (!preg_match('/^[A-Z]/', $password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must start with a capital letter'];
            header("Location: ../../index.php");
            exit;
        }

        if (!preg_match('/[\W_]/', $password)) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must contain at least one symbol'];
            header("Location: ../../index.php");
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password must be at least 8 characters long'];
            header("Location: ../../index.php");
            exit;
        }


        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->runQuery('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $execute = $stmt->execute(array(":username" => $username, ":email" => $email, ":password" => $hash_password));

        if ($execute) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'User Added'];
            header("Location: ../../index.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error adding user'];
            header("Location: ../../index.php");
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
                $role = $userRow['role'];
                $activiy = "the $role has successfully login";
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
        unset($_SESSION['session']);
        header("Location: ../../");
        exit;
    }
}

if (isset($_POST['btn-signup'])) {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $addUser = new IMS();
        $addUser->addUser($username, $email, $password);
    }
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
