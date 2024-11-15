<?php
require_once '../authentication/class.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User || Inventory Management System</title>
    <link rel="stylesheet" href="../../src/css/bootstrap.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-space-between">
            <h1>Welcome User</h1>
            <button class="btn btn-danger"><a href="../authentication/class.php?signout" class="text-decoration-none text-black">Sign Out</a></button>
        </div>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">View Products</a>
            <a href="#" class="list-group-item list-group-item-action">Search Products</a>
            <a href="#" class="list-group-item list-group-item-action">Check Stock Levels</a>
        </div>
    </div>

    <footer class="mt-5">
        <p>&copy; 2024 Inventory Management System</p>
    </footer>


    <script src="../../src/js/bootstrap.js"></script>
</body>

</html>