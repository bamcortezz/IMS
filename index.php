<?php
include_once 'config/settings-config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="src/css/bootstrap.css">
  <link rel="stylesheet" href="src/js/bootstrap.js">
</head>

<body>
  <div class="wrapper">
    <div class="container main">
      <div class="col-md-5 py-5 right card">

        <form id="login-form" action="dashboard/authentication/class.php" method="POST">
          <div class="input-box">
            <h1>LOGIN</h1>
            <div class="input-field">
              <input type="email" name="email" required class="form-control" placeholder="Enter Email">
            </div>
            <div class="input-field">
              <input type="password" name="password" required class="form-control" placeholder="Enter Password">
            </div>
            <div class="input-field">
              <button type="submit" name="btn-signin" class="submit">Sign in</button>
            </div>
            <div class="signin">
              <span>Need to register? <a href="#" id="show-registration">Register here</a></span>
            </div>
          </div>
        </form>

        <form id="registration-form" action="dashboard/authentication/class.php" method="POST" style="display: none;">
          <div class="input-box">
            <h1>REGISTRATION</h1>
            <div class="input-field">
              <input type="text" name="username" placeholder="Enter Username" required class="form-control">
            </div>
            <div class="input-field">
              <input type="email" name="email" required class="form-control" placeholder="Enter Email">
            </div>
            <div class="input-field">
              <input type="password" name="password" required class="form-control" placeholder="Enter Password">
            </div>
            <div class="input-field">
              <button type="submit" name="btn-signup" class="submit">Sign Up</button>
            </div>
            <div class="signin">
              <span>Already have an account? <a href="#" id="show-login">Login here</a></span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>



  <script src="src/js/script.js"></script>
</body>

</html>