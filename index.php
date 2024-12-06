<?php
include_once 'config/settings-config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inventory Management System</title>
   <link rel="stylesheet" href="src/css/bootstrap.css">
   <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-light">
   <div class="container d-flex justify-content-center align-items-center min-vh-100">
      <div class="card shadow-lg p-4" style="width: 400px;">
         <div class="card-body">
            <form id="login-form" action="dashboard/authentication/authentication.php" method="POST">
               <h2 class="text-center mb-4">LOGIN</h2>

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

               <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
               </div>

               <div class>
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
               </div>

               <div class="text-center mt-2 d-flex justify-content-between align-items-center">
                  <div>
                     <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                     <label class="form-check-label small-text" for="remember_me">
                        Remember Me
                     </label>
                  </div>
                  <a href="forgot-password.php" class="btn btn-link small-text">Forgot Password?</a>
               </div>

               <div class="text-center">
                  <button type="submit" name="btn-signin" class="btn btn-primary w-100">Sign In</button>
               </div>

               <div class="mt-3 text-center">
                  <span>Don't have an account? <a href="#" id="show-registration">Register Here!</a></span>
               </div>
            </form>

            <form id="registration-form" action="dashboard/authentication/authentication.php" method="POST" style="display: none;">
               <h2 class="text-center mb-4">REGISTRATION</h2>

               <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username">
               </div>

               <div class="mb-3">
                  <label for="reg-email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="reg-email" placeholder="Enter Email">
               </div>

               <div class="mb-3">
                  <label for="reg-password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="reg-password" placeholder="Enter Password">
               </div>

               <div class="mb-3 text-center">
                  <button type="submit" name="btn-signup" class="btn btn-primary w-100">Sign Up</button>
               </div>

               <div class="text-center">
                  <span>Already have an account? <a href="#" id="show-login">Login here</a></span>
               </div>
            </form>
         </div>
      </div>
   </div>

   <script src="src/js/script.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>