<?php
require_once '../authentication/class.php';

$purchase_order = new IMS();

if (!$purchase_order->isUserLogged()) {
    header("Location: ../../");
    exit;
}

$stmt = $purchase_order->runQuery("SELECT id, product_name FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $purchase_order->runQuery("SELECT id, supplier_name FROM suppliers");
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="card" style="width: 600px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Purchase Order</h3>
                    <?php
                    if (isset($_SESSION['alert']) && isset($_SESSION['alert']['type']) && isset($_SESSION['alert']['message'])) {
                        $alert = $_SESSION['alert'];
                        echo "  <div class='alert alert-{$alert['type']} alert-dismissible fade show'       role='alert'>
                                    {$alert['message']}
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                        unset($_SESSION['alert']);
                    }
                    ?>
                    <form method="POST" action="../authentication/class.php">
                        <div class="mb-3">
                            <label for="productId" class="form-label">Select Product</label>
                            <select class="form-select" id="productId" name="product_id" required>
                                <option value="" disabled selected>Select a product</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['id'] ?>"><?= $product['product_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="supplierId" class="form-label">Select Supplier</label>
                            <select class="form-select" id="supplierId" name="supplier_id" required>
                                <option value="" disabled selected>Select a supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
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