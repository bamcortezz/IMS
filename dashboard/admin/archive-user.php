<?php
require_once '../authentication/authentication.php';
require_once '../authentication/product-class.php';

$isLogin = new IMS();
$userManagement = new ProductSupplierFunctions();

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
   <title>Archived Users</title>
   <?php include '../../includes/link-css.php' ?>
</head>

<body>
   <div class="wrapper">
      <?php include '../../includes/sidebar-admin.php' ?>

      <div class="main-content">
         <h1>Archived Users</h1>
         <div class="d-flex justify-content-end mb-3">
            <a href="user-management.php" class="btn btn-secondary">Show Active User</a>
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
                  <?php if (!empty($userManagement->getArchiveUser())): ?>
                     <?php foreach ($userManagement->getArchiveUser() as $user): ?>
                        <tr>
                           <td><?= htmlspecialchars($user['username']) ?></td>
                           <td><?= htmlspecialchars($user['email']) ?></td>
                           <td><?= htmlspecialchars($user['role']) ?></td>
                           <td>
                              <button class="btn btn-sm btn-success" onclick="reactivateUser(<?= $user['id'] ?>, '<?= $user['username'] ?>')">Reactivate</button>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="4" class="text-center">No archived users found</td>
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