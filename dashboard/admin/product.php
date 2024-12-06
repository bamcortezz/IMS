<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$product = new ProductSupplierFunctions();

if (!$isLogin->isUserLogged()) {
    header("Location: ../../");
    exit;
}

if (isset($_GET['delete_id'])) {
    $deleteProduct = $_GET['delete_id'];
    $delete_product->deleteProduct($deleteProduct);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <?php include '../../includes/link-css.php' ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content">
            <h1>Product</h1>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
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
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product->getProduct())): ?>
                            <?php foreach ($product->getProduct() as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><?= $product['description'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteProduct(<?= $product['id'] ?>, '<?= $product['product_name'] ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../authentication/product-class.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="btn-product">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/link-js.php' ?>
</body>

</html>