<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$sales_order = new ProductSupplierFunctions();
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
    <title>Sales Order</title>
    <?php include '../../includes/link-css.php' ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../../includes/sidebar-admin.php' ?>

        <div class="main-content d-flex justify-content-center align-items-center">
            <div class="card" style="width: 800px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Sales Order</h3>
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
                                        <select class="form-select" id="productId" name="product_id" required onchange="updatePriceAndTotalSales()">
                                            <option value="" disabled selected>Select a product</option>
                                            <?php foreach ($sales_order->getProduct() as $product): ?>
                                                <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>"><?= $product['product_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="quantity" name="quantity" required oninput="updatePriceAndTotalSales()">
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
                            <label for="customerId" class="form-label">Select Customer</label>
                            <select class="form-select" id="customerId" name="customer_id" required>
                                <option value="" disabled selected>Select a customer</option>
                                <?php foreach ($sales_order->getUser() as $customer): ?>
                                    <option value="<?= $customer['id'] ?>"><?= $customer['username'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn btn-success" name="btn-sales-order">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/link-js.php' ?>

</body>

</html>
