<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';


$isLogin = new IMS();
$supplier = new ProductSupplierFunctions();

if (!$isLogin->isUserLogged()) {
    header("Location: ../../");
    exit;
}

if (isset($_GET['delete_id'])) {
    $deleteSupplier = $_GET['delete_id'];
    $supplier->deleteSupplier($deleteSupplier);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier</title>
    <?php include '../../includes/link-css.php' ?>
</head>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>


        <div class="main-content">
            <h1>Supplier</h1>
            <div class="d-flex justify-content-end mb-3">
                <div class="me-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add Supplier</button>
                </div>
                <div>
                    <a href="archive-supplier.php" class="btn btn-secondary">Show Archived Suppliers</a>
                </div>
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
                            <th>Supplier Name</th>
                            <th>Contact Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($supplier->getSupplierName())): ?>
                            <?php foreach ($supplier->getSupplierName() as $supplier): ?>
                                <tr>
                                    <td><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                                    <td><?= $supplier['contact_number'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editSupplier(<?= $supplier['id'] ?>, '<?= $supplier['supplier_name'] ?>', '<?= $supplier['contact_number'] ?>')">Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteSupplier(<?= $supplier['id'] ?>, '<?= $supplier['supplier_name'] ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No suppliers found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../authentication/product-class.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="supplierName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="supplierName" name="supplier_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="supplierName" class="form-label">Contact Number</label>
                            <input type="number" class="form-control" id="supplierContact" name="contact_number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="btn-supplier">Add Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSupplierForm" method="POST" action="supplier.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="supplier_id" id="supplierId">
                        <div class="mb-3">
                            <label for="supplierName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="editSupplierName" name="supplier_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="supplierContact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editSupplierContact" name="contact_number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="btn-edit-supplier">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include '../../includes/link-js.php' ?>
</body>

</html>