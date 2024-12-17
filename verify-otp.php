<?php
include_once 'config/settings-config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP</title>
  <link rel="icon" href="src/image/bg.jpeg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
   <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
      <div class="card p-4" style="max-width: 500px; width: 100%;">
         <h4 class="card-title text-center mb-4">OTP VERIFICATION</h4>

         <?php
         if (isset($_SESSION['alert']) && isset($_SESSION['alert']['type']) && isset($_SESSION['alert']['message'])) {
            $alert = $_SESSION['alert'];
            echo "<div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
              {$alert['message']}
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            unset($_SESSION['alert']);
         }
         ?>

         <p class="text-center mb-4">We have sent a One Time Password (OTP) to your email.</p>

         <form method="POST" action="dashboard/authentication/authentication.php">
            <div class="mb-3">
               <label for="otp" class="form-label">Enter OTP</label>
               <input type="number" name="otp" id="otp" class="form-control" placeholder="Enter OTP" required>
            </div>
            <div class="d-grid gap-2">
               <button type="submit" name="btn-verify" class="btn btn-primary">Verify</button>
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
