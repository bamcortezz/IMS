<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$userManagement = new ProductSupplierFunctions();

if (!$isLogin->isUserLogged()) {
    header("Location: ../../");
    exit;
}

if (isset($_GET['delete_id'])) {
    $deleteUser = $_GET['delete_id'];
    $userManagement->deleteUser($deleteUser);
}

if (isset($_GET['reactivate_id'])) {
    $reactivateUser = $_GET['reactivate_id'];
    $userManagement->reactivateUser($reactivateUser);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include '../../includes/link-css.php' ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content">
            <h1>User Management</h1>
            <div class="d-flex justify-content-end mb-3">
                <a href="archive-user.php" class="btn btn-secondary">Show Archived Users</a>
            </div>

            <div>
                <?php
                if (isset($_SESSION['alert']) && isset($_SESSION['alert']['type']) && isset($_SESSION['alert']['message'])) {
                    $alert = $_SESSION['alert'];
                    echo "  <div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
                                    {$alert['message']}
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                         </div>";
                    unset($_SESSION['alert']);
                }
                ?>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($userManagement->getUser())): ?>
                            <?php foreach ($userManagement->getUser() as $user): ?>
                                <tr>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['role'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteUser(<?= $user['id'] ?>, '<?= $user['username'] ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../../includes/link-js.php' ?>
</body>

</html>
