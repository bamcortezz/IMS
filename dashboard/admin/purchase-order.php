<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$purchase_order = new ProductSupplierFunctions();

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
    <title>Add Product</title>
    <?php include '../../includes/link-css.php' ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content d-flex justify-content-center align-items-center">
            <div class="card" style="width: 800px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Purchase Order</h3>
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
                    <form method="POST" action="../authentication/product-class.php">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-select" id="productId" name="product_id" required onchange="updatePriceAndTotal()">
                                            <option value="" disabled selected>Select a product</option>
                                            <?php foreach ($purchase_order->getProduct() as $product): ?>
                                                <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>"><?= $product['product_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="quantity" name="quantity" required oninput="updatePriceAndTotal()">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="productPrice" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="totalPrice" disabled>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <label for="supplierId" class="form-label">Select Supplier</label>
                            <select class="form-select" id="supplierId" name="supplier_id" required>
                                <option value="" disabled selected>Select a supplier</option>
                                <?php foreach ($purchase_order->getSupplierName() as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn btn-success" name="btn-purchase">Purchase Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/link-js.php' ?>
</body>

</html>
