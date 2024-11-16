<?php
require_once '../authentication/class.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="../../src/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../../src/css/admin.css">
</head>

<body>
    <div class="wrapper">
      <?php include '../../includes/sidebar-admin.php' ?>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Welcome Admin</h1>
            <p>Select an option from the sidebar to get started.</p>
        </div>
    </div>

    <footer class="text-center py-3">
        <p>&copy; 2024 Inventory Management System</p>
    </footer>

    <script src="../../src/js/script.js"></script>
</body>

</html>
