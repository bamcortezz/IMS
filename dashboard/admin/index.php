<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$dashboard = new ProductSupplierFunctions();
$isLogin = new IMS();

if (!$isLogin->isUserLogged()) {
    header("Location: ../../");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include '../../includes/link-css.php' ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content">
            <h1>Dashboard</h1>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white py-2">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <h2 class="card-text"><?php echo $dashboard->getUserCount(); ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-success text-white py-2">
                        <div class="card-body">
                            <h5 class="card-title">Products</h5>
                            <h2 class="card-text"><?php echo $dashboard->getProductCount(); ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 ">
                    <div class="card bg-warning text-white py-2">
                        <div class="card-body">
                            <h5 class="card-title">Suppliers</h5>
                            <h2 class="card-text"><?php echo $dashboard->getSupplierCount(); ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3>Recent Logs</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Activity</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dashboard->getRecentLogs() as $index => $log) {
                                echo '<tr>';
                                echo '<th scope="row">' . ($index + 1) . '</th>';
                                echo '<td>' . htmlspecialchars($log['user_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($log['activity']) . '</td>';
                                echo '<td>' . htmlspecialchars($log['created_at']) . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>

    <?php include '../../includes/link-js.php' ?>
</body>

</html>