<?php
include_once 'config/settings-config.php';
require_once 'dashboard/authentication/authentication.php';

if (isset($_GET['token']) && isset($_GET['id'])) {
    $token = $_GET['token'];
    $userId = $_GET['id'];

    $resetPass = new IMS();

    if ($resetPass->validateResetToken($userId, $token)) {
        if (isset($_POST['btn-reset-password'])) {
            $newPassword = trim($_POST['password']);
            $resetPass->resetPassword($userId, $newPassword);
        }
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid token.'];
        header("Location: forgot-password.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" href="src/image/bg.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-4" style="max-width: 500px; width: 100%;">
            <h4 class="card-title text-center mb-4">Reset Your Password</h4>
            <?php if (isset($_SESSION['alert'])) {
                $alert = $_SESSION['alert'];
                echo '<div class="alert alert-' . $alert['type'] . '" role="alert">';
                echo $alert['message'];
                echo '</div>';
                unset($_SESSION['alert']);
            } ?>
            <p class="text-center mb-4">Enter your new password below.</p>

            <form method="POST" action="reset-password.php?token=<?php echo $token; ?>&id=<?php echo $userId; ?>">
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="btn-reset-password" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="index.php">Back to Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>