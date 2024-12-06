<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$sales_order = new IMS();

if (!$sales_order->isUserLogged()) {
    header("Location: ../../");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Order</title>
    <?php include '../../includes/link-css.php' ?>
</head>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content">
            <h1>Sales Order</h1>
        </div>

    </div>


    <?php include '../../includes/link-js.php' ?>
</body>

</html>