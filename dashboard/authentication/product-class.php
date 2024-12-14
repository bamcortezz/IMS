<?php

require_once __DIR__ . '/../../database/dbconnection.php';
include_once __DIR__ . '/../../config/settings-config.php';


class ProductSupplierFunctions
{
   private $conn;

   public function __construct()
   {
      $database = new Database();
      $this->conn = $database->dbConnection();
   }

   public function deleteUser($userId)
   {
      $stmt = $this->runQuery("SELECT * FROM users WHERE id = :user_id");
      $stmt->execute(array(":user_id" => $userId));

      if ($stmt->rowCount() > 0) {
         $stmt = $this->runQuery("UPDATE users SET active = 'not_active' WHERE id = :user_id");
         $execute = $stmt->execute(array(":user_id" => $userId));

         if ($execute) {

            $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
            $stmt->execute(array(":id" => $_SESSION['session']));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $role = $userRow['username'];
            $activity = "$role has successfully deleted a user";
            $user_id = $userRow['id'];

            $_SESSION['session'] = $user_id;
            $this->logs($activity, $user_id);

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'User successfully deleted.'];
            header("Location: ../admin/user-management.php");
            exit;
         } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error deleting user.'];
            header("Location: ../admin/user-management.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'User not found.'];
         header("Location: ../admin/user-management.php");
         exit;
      }
   }

   public function addSupplier($supplier_name, $contact_number)
   {
      $stmt = $this->runQuery("SELECT * FROM suppliers WHERE supplier_name = :supplier_name");
      $stmt->execute(array(":supplier_name" => $supplier_name));

      if ($stmt->rowCount() > 0) {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Supplier already exists.'];
         header("Location: ../admin/supplier.php");
         exit;
      }

      $stmt = $this->runQuery("SELECT * FROM suppliers WHERE contact_number = :contact_number");
      $stmt->execute(array(":contact_number" => $contact_number));

      if ($stmt->rowCount() > 0) {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Contact number exists.'];
         header("Location: ../admin/supplier.php");
         exit;
      }

      $stmt = $this->runQuery("INSERT INTO suppliers (supplier_name, contact_number) VALUES (:supplier_name, :contact_number)");
      $execute = $stmt->execute(array(":supplier_name" => $supplier_name, ":contact_number" => $contact_number));

      if ($execute) {

         $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
         $stmt->execute(array(":id" => $_SESSION['session']));
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

         $role = $userRow['username'];
         $activity = "$role has successfully added a supplier";
         $user_id = $userRow['id'];

         $_SESSION['session'] = $user_id;
         $this->logs($activity, $user_id);

         $_SESSION['alert'] = ['type' => 'success', 'message' => 'Supplier added successfully'];
         header("Location: ../admin/supplier.php");
         exit;
      }
   }

   public function updateSupplier($id, $supplier_name, $contact_number)
   {
      $stmt = $this->runQuery("SELECT * FROM suppliers WHERE id = :id");
      $stmt->execute(array(":id" => $id));

      if ($stmt->rowCount() > 0) {
         $stmt = $this->runQuery("UPDATE suppliers SET supplier_name = :supplier_name, contact_number = :contact_number WHERE id = :id");
         $execute = $stmt->execute(array(":supplier_name" => $supplier_name, ":contact_number" => $contact_number, ":id" => $id));

         if ($execute) {
            $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
            $stmt->execute(array(":id" => $_SESSION['session']));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $role = $userRow['username'];
            $activity = "$role has successfully updated a supplier";
            $user_id = $userRow['id'];

            $_SESSION['session'] = $user_id;
            $this->logs($activity, $user_id);

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Supplier edit successfully'];
            header("Location: ../admin/supplier.php");
            exit;
         } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error updating supplier'];
            header("Location: ../admin/supplier.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'No supplier found'];
         header("Location: ../admin/supplier.php");
         exit;
      }
   }

   public function deleteSupplier($supplierId)
   {
      $stmt = $this->runQuery("SELECT * FROM suppliers WHERE id = :suppliers_id");
      $stmt->execute(array(":suppliers_id" => $supplierId));

      if ($stmt->rowCount() > 0) {

         $stmt = $this->runQuery("UPDATE suppliers SET active = 'not_active' WHERE id = :suppliers_id");
         $execute = $stmt->execute(array(":suppliers_id" => $supplierId));

         if ($execute) {

            $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
            $stmt->execute(array(":id" => $_SESSION['session']));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $role = $userRow['username'];
            $activity = "$role has successfully deleted a supplier";
            $user_id = $userRow['id'];

            $_SESSION['session'] = $user_id;
            $this->logs($activity, $user_id);

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Supplier successfully deleted.'];
            header("Location: ../admin/supplier.php");
            exit;
         } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error deleting supplier.'];
            header("Location: ../admin/supplier.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Supplier not found.'];
         header("Location: ../admin/supplier.php");
         exit;
      }
   }

   public function addProduct($product_name, $stock, $description, $price)
   {
      $stmt = $this->runQuery("SELECT * FROM products WHERE product_name = :product_name");
      $stmt->execute(array(":product_name" => $product_name));

      if ($stmt->rowCount() > 0) {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Product already exists.'];
         header("Location: ../admin/product.php");
         exit;
      }

      $stmt = $this->runQuery("INSERT INTO products (product_name, stock, description, price) VALUES (:product_name, :stock, :description, :price)");
      $execute = $stmt->execute(array(":product_name" => $product_name, ":stock" => $stock, ":description" => $description, ":price" => $price));

      if ($execute) {

         $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
         $stmt->execute(array(":id" => $_SESSION['session']));
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

         $role = $userRow['username'];
         $activity = "$role has successfully added a product";
         $user_id = $userRow['id'];

         $_SESSION['session'] = $user_id;
         $this->logs($activity, $user_id);

         $_SESSION['alert'] = ['type' => 'success', 'message' => 'Product added successfully'];
         header("Location: ../admin/product.php");
         exit;
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error adding product'];
         header("Location: ../admin/product.php");
         exit;
      }
   }

   public function deleteProduct($productId)
   {
      $stmt = $this->runQuery("SELECT * FROM products WHERE id = :product_id");
      $stmt->execute(array(":product_id" => $productId));

      if ($stmt->rowCount() > 0) {
         $stmt = $this->runQuery("UPDATE products SET active = 'not_active' WHERE id = :product_id");
         $execute = $stmt->execute(array(":product_id" => $productId));

         if ($execute) {

            $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
            $stmt->execute(array(":id" => $_SESSION['session']));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $role = $userRow['username'];
            $activity = "$role has successfully deleted a product";
            $user_id = $userRow['id'];

            $_SESSION['session'] = $user_id;
            $this->logs($activity, $user_id);

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Product deleted.'];
            header("Location: ../admin/product.php");
            exit;
         } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error deleting product'];
            header("Location: ../admin/product.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'No product found'];
         header("Location: ../admin/product.php");
         exit;
      }
   }

   public function purchaseProduct($product_id, $supplier_id, $quantity)
   {
      $stmt = $this->runQuery("SELECT stock, price FROM products WHERE id = :product_id");
      $stmt->execute(array(":product_id" => $product_id));

      if ($stmt->rowCount() == 1) {
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
         $current_stock = $userRow['stock'];
         $product_price = $userRow['price'];

         $total_price = $product_price * $quantity;

         $new_stock = $current_stock + $quantity;

         $stmt = $this->runQuery("UPDATE products SET stock = :new_stock WHERE id = :product_id");
         $execute = $stmt->execute(array(":new_stock" => $new_stock, ":product_id" => $product_id));

         if ($execute) {


            $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
            $stmt->execute(array(":id" => $_SESSION['session']));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $role = $userRow['username'];
            $activity = "$role has successfully purchased a product";
            $user_id = $userRow['id'];

            $_SESSION['session'] = $user_id;
            $this->logs($activity, $user_id);

            $stmt = $this->runQuery("INSERT INTO product_purchase (product_id, supplier_id, quantity, total_price) VALUES (:product_id, :supplier_id, :quantity, :total_price)");
            $stmt->execute(array(":product_id" => $product_id, ":supplier_id" => $supplier_id, ":quantity" => $quantity, ":total_price" => $total_price));

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Product quantity updated'];
            header("Location: ../admin/purchase-order.php");
            exit;
         } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error updating stock'];
            header("Location: ../admin/purchase-order.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Product not found'];
         header("Location: ../admin/purchase-order.php");
         exit;
      }
   }

   public function createSalesOrder($product_id, $customer_id, $quantity)
   {
      $stmt = $this->runQuery("SELECT price, stock FROM products WHERE id = :product_id");
      $stmt->execute(array(":product_id" => $product_id));

      if ($stmt->rowCount() == 1) {
         $product = $stmt->fetch(PDO::FETCH_ASSOC);
         $current_stock = $product['stock'];
         $product_price = $product['price'];

         if ($current_stock >= $quantity) {
            $total_price = $product_price * $quantity;

            $new_stock = $current_stock - $quantity;
            $stmt = $this->runQuery("UPDATE products SET stock = :new_stock WHERE id = :product_id");
            $execute = $stmt->execute(array(":new_stock" => $new_stock, ":product_id" => $product_id));

            if ($execute) {

               $stmt = $this->runQuery("INSERT INTO sales_order (product_id, customer_id, quantity, total_price) VALUES (:product_id, :customer_id, :quantity, :total_price)");
               $stmt->execute(array(":product_id" => $product_id, ":customer_id" => $customer_id, ":quantity" => $quantity, ":total_price" => $total_price));

               $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
               $stmt->execute(array(":id" => $_SESSION['session']));
               $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
               $role = $userRow['username'];
               $activity = "$role has successfully created a sales order";
               $user_id = $userRow['id'];

               $_SESSION['session'] = $user_id;
               $this->logs($activity, $user_id);

               $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sales order created successfully'];
               header("Location: ../admin/sales-order.php");
               exit;
            } else {
               $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error updating stock'];
               header("Location: ../admin/sales-order.php");
               exit;
            }
         } else {

            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Not enough stock available'];
            header("Location: ../admin/sales-order.php");
            exit;
         }
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Product not found'];
         header("Location: ../admin/sales-order.php");
         exit;
      }
   }

   //FETCHING ACTIVE STATUS
   public function getUser()
   {
      $stmt = $this->runQuery("SELECT id, username, email, role FROM users WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getSupplierName()
   {
      $stmt = $this->runQuery("SELECT id, supplier_name, contact_number FROM suppliers WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getProduct()
   {
      $stmt = $this->runQuery("SELECT id, product_name, stock, description, price FROM products WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   //FETCHING NOT ACTIVE STATUS
   public function getArchiveUser()
   {
      $stmt = $this->runQuery("SELECT id, username, email, role FROM users WHERE active = 'not_active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getArchiveSuppliers()
   {
      $stmt = $this->runQuery("SELECT id, supplier_name, contact_number FROM suppliers WHERE active = 'not_active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getArchiveProducts()
   {
      $stmt = $this->runQuery("SELECT id, product_name, stock, description, price FROM products WHERE active = 'not_active'");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   //REACTIVATING 
   public function reactivateUser($id)
   {
      $stmt = $this->runQuery("UPDATE users SET active = 'active' WHERE id = :id AND active = 'not_active'");
      $execute = $stmt->execute(array(":id" => $id));

      if ($execute) {

         $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
         $stmt->execute(array(":id" => $_SESSION['session']));
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

         $role = $userRow['username'];
         $activity = "$role has successfully reactivated a user";
         $user_id = $userRow['id'];

         $_SESSION['session'] = $user_id;
         $this->logs($activity, $user_id);

         $_SESSION['alert'] = ['type' => 'success', 'message' => 'Reactivate Successful'];
         header("Location: ../admin/user-management.php");
         exit;
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error reactivating'];
         header("Location: ../admin/user-management.php");
         exit;
      }
   }

   public function reactivateSupplier($id)
   {
      $stmt = $this->runQuery("UPDATE suppliers SET active = 'active' WHERE id = :id AND active = 'not_active'");
      $execute = $stmt->execute(array(":id" => $id));

      if ($execute) {

         $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
         $stmt->execute(array(":id" => $_SESSION['session']));
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

         $role = $userRow['username'];
         $activity = "$role has successfully reactivated a supplier";;
         $user_id = $userRow['id'];

         $_SESSION['session'] = $user_id;
         $this->logs($activity, $user_id);

         $_SESSION['alert'] = ['type' => 'success', 'message' => 'Supplier reactivated successfully.'];
         header("Location: ../admin/supplier.php");
         exit;
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error reactivating supplier.'];
         header("Location: ../admin/supplier.php");
         exit;
      }
   }

   public function reactivateProduct($id)
   {
      $stmt = $this->runQuery("UPDATE products SET active = 'active' WHERE id = :id AND active = 'not_active'");
      $execute = $stmt->execute(array(":id" => $id));

      if ($execute) {

         $stmt = $this->runQuery("SELECT * FROM users WHERE id = :id");
         $stmt->execute(array(":id" => $_SESSION['session']));
         $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

         $role = $userRow['username'];
         $activity = "$role has successfully reactivated a product";
         $user_id = $userRow['id'];

         $_SESSION['session'] = $user_id;
         $this->logs($activity, $user_id);

         $_SESSION['alert'] = ['type' => 'success', 'message' => 'Product reactivated successfully.'];
         header("Location: ../admin/product.php");
         exit;
      } else {
         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error reactivating product.'];
         header("Location: ../admin/product.php");
         exit;
      }
   }

   //FETHCING COUNTS
   public function getUserCount()
   {
      $stmt = $this->runQuery("SELECT COUNT(*) FROM users WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchColumn();
   }

   public function getProductCount()
   {
      $stmt = $this->runQuery("SELECT COUNT(*) FROM products WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchColumn();
   }

   public function getSupplierCount()
   {
      $stmt = $this->runQuery("SELECT COUNT(*) FROM suppliers WHERE active = 'active'");
      $stmt->execute();
      return $stmt->fetchColumn();
   }

   public function getRecentLogs()
   {
      $stmt = $this->runQuery("SELECT * FROM logs ORDER BY created_at DESC LIMIT 10");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public function logs($activiy, $user_id)
   {
      $stmt = $this->runQuery("INSERT INTO  logs (user_id, activity) VALUES (:user_id, :activity)");
      $stmt->execute(array("user_id" => $user_id, ":activity" => $activiy));
   }

   public function runQuery($sql)
   {
      $stmt = $this->conn->prepare($sql);
      return $stmt;
   }
}

if (isset($_POST['btn-supplier'])) {
   if (isset($_POST['supplier_name'], $_POST['contact_number'])) {
      $supplier_name = $_POST['supplier_name'];
      $contact_number = $_POST['contact_number'];

      $supplier = new ProductSupplierFunctions();
      $supplier->addSupplier($supplier_name, $contact_number);
   }
}

if (isset($_POST['btn-edit-supplier'])) {
   if (isset($_POST['supplier_name'], $_POST['contact_number'], $_POST['supplier_id'])) {
      $supplier_name = $_POST['supplier_name'];
      $contact_number = $_POST['contact_number'];
      $supplier_id = $_POST['supplier_id'];

      $supplier = new ProductSupplierFunctions();
      $supplier->updateSupplier($supplier_id, $supplier_name, $contact_number);
   }
}

if (isset($_POST['btn-product'])) {
   if (isset($_POST['product_name'], $_POST['stock'], $_POST['description'])) {
      $product_name = $_POST['product_name'];
      $stock = $_POST['stock'];
      $description = $_POST['description'];
      $price = $_POST['price'];

      $product = new ProductSupplierFunctions();
      $product->addProduct($product_name, $stock, $description, $price);
   }
}

if (isset($_POST['btn-purchase'])) {
   if (isset($_POST['product_id'], $_POST['quantity'], $_POST['supplier_id'])) {
      $product_id = $_POST['product_id'];
      $quantity = $_POST['quantity'];
      $supplier_id = $_POST['supplier_id'];

      $purchase = new ProductSupplierFunctions();
      $purchase->purchaseProduct($product_id, $supplier_id, $quantity);
   }
}

if (isset($_POST['btn-sales-order'])) {

   $product_id = $_POST['product_id'];
   $customer_id = $_POST['customer_id'];
   $quantity = $_POST['quantity'];

   $sales = new ProductSupplierFunctions();
   $sales->createSalesOrder($product_id, $customer_id, $quantity);
}

