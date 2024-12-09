<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$userManagement = new ProductSupplierFunctions();

if (!$isLogin->isUserLogged()) {
   header("Location: ../../");
   exit;
}

if (isset($_GET['reactivate_id'])) {
   $supplierId = $_GET['reactivate_id'];
   $userManagement->reactivateSupplier($supplierId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Archived Suppliers</title>
   <?php include '../../includes/link-css.php' ?>
</head>

<body>
   <div class="wrapper">
      <?php include '../../includes/sidebar-admin.php' ?>

      <div class="main-content">
         <h1>Archived Suppliers</h1>

         <div class="d-flex justify-content-end mb-3">
            <a href="supplier.php" class="btn btn-warning">Show Active Supplier</a>
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
                  <?php if (!empty($userManagement->getArchiveSuppliers())): ?>
                     <?php foreach ($userManagement->getArchiveSuppliers() as $supplier): ?>
                        <tr>
                           <td><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                           <td><?= htmlspecialchars($supplier['contact_number']) ?></td>
                           <td>
                              <button class="btn btn-sm btn-success" onclick="reactivateSupplier(<?= $supplier['id'] ?>, '<?= $supplier['supplier_name'] ?>')">Reactivate</button>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="3" class="text-center">No archived suppliers found</td>
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