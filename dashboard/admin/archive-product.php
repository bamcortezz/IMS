<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$userManagement = new ProductSupplierFunctions();

if (!$isLogin->isUserLogged()) {
   header("Location: ../../");
   exit;
}

if (isset($_GET['id'])) {
   $productId = $_GET['id'];
   $userManagement->reactivateProduct($productId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Archived Products</title>
   <?php include '../../includes/link-css.php' ?>
</head>

<body>
   <div class="wrapper">
      <?php include '../../includes/sidebar-admin.php' ?>

      <div class="main-content">
         <h1>Archived Products</h1>
         <div class="d-flex justify-content-end mb-3">
            <a href="product.php" class="btn btn-warning">Show Active Products</a>
         </div>
         <div class="table-responsive mt-4">
            <table class="table table-striped table-hover">
               <thead class="table-dark">
                  <tr>
                     <th>Name</th>
                     <th>Stock</th>
                     <th>Description</th>
                     <th>Price</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($userManagement->getArchiveProducts())): ?>
                     <?php foreach ($userManagement->getArchiveProducts() as $product): ?>
                        <tr>
                           <td><?= htmlspecialchars($product['product_name']) ?></td>
                           <td><?= htmlspecialchars($product['stock']) ?></td>
                           <td><?= htmlspecialchars($product['description']) ?></td>
                           <td><?= htmlspecialchars($product['price']) ?></td>
                           <td>
                              <button class="btn btn-sm btn-success" onclick="reactivateProduct(<?= $product['id'] ?>, '<?= $product['product_name'] ?>')">Reactivate</button>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="5" class="text-center">No archived products found</td>
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