<?php
include_once __DIR__ . '/../../config/settings-config.php';
require_once __DIR__ . '/../../database/dbconnection.php';


class IMS
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function addUser($username, $email, $password)
    {

        $stmt = $this->runQuery("SELECT * FROM users WHERE username = :username");
        $stmt->execute(array(":username" => $username));

        if ($stmt->rowCount() == 1) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Username already exists'];
            header("Location: ../../index.php");
            exit;
        }

        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email already exists'];
            header("Location: ../../index.php");
            exit;
        }

        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->runQuery('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $execute = $stmt->execute(array(":username" => $username, ":email" => $email, ":password" => $hash_password));

        if ($execute) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'User Added'];
            header("Location: ../../index.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error adding user'];
            header("Location: ../../index.php");
            exit;
        }
    }

    public function login($email, $password)
    {
        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 1) {
            if (password_verify($password, $userRow['password'])) {
                $role = $userRow['role'];
                $activiy = "the $role has successfully login";
                $user_id = $userRow['id'];

                $_SESSION['session'] = $user_id;
                $this->logs($activiy, $user_id);
                $this->redirect($userRow['role']);
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Incorrect Password'];
                header("Location: ../../");
                exit;
            }
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'User Not Found'];
            header("Location: ../../");
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
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Supplier added successfully'];
            header("Location: ../admin/supplier.php");
            exit;
        }
    }

    public function addProduct($product_name, $stock, $description)
    {
        $stmt = $this->runQuery("SELECT * FROM products WHERE product_name = :product_name");
        $stmt->execute(array(":product_name" => $product_name));

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Product already exists.'];
            header("Location: ../admin/product.php");
            exit;
        }

        $stmt = $this->runQuery("INSERT INTO products (product_name, stock, description) VALUES (:product_name, :stock, :description)");
        $exec = $stmt->execute(array(":product_name" => $product_name, ":stock" => $stock, ":description" => $description));

        if ($exec) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Product added successfully'];
            header("Location: ../admin/product.php");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Error adding product'];
            header("Location: ../admin/product.php");
            exit;
        }
    }

    public function purchaseProduct($product_id, $supplier_id, $quantity)
    {
        $stmt = $this->runQuery("SELECT stock FROM products WHERE id = :product_id");
        $stmt->execute(array(":product_id" => $product_id));

        if ($stmt->rowCount() == 1) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $current_stock = $product['stock'];

            $new_stock = $current_stock + $quantity;

            $stmt = $this->runQuery("UPDATE products SET stock = :new_stock WHERE id = :product_id");
            $execute = $stmt->execute(array(":new_stock" => $new_stock, ":product_id" => $product_id));

            if ($execute) {
                $stmt = $this->runQuery("INSERT INTO product_purchase (product_id, supplier_id, quantity) VALUES (:product_id, :supplier_id, :quantity)");
                $stmt->execute(array(":product_id" => $product_id, ":supplier_id" => $supplier_id, ":quantity" => $quantity));

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

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function logs($activiy, $user_id)
    {
        $stmt = $this->runQuery("INSERT INTO  logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array("user_id" => $user_id, ":activity" => $activiy));
    }

    public function redirect($role)
    {
        if ($role === "admin") {
            header("Location: ../admin/product.php");
        } elseif ($role === "user") {
            header("Location: ../user/index.php");
        } else {
            header("Location: ../../");
        }
        exit;
    }

    public function isUserLogged()
    {
        if (isset($_SESSION['session'])) {
            return true;
        }
    }

    public function signOut()
    {
        unset($_SESSION['session']);
        header("Location: ../../");
        exit;
    }
}

if (isset($_POST['btn-signup'])) {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $addUser = new IMS();
        $addUser->addUser($username, $email, $password);
    }
}

if (isset($_POST['btn-signin'])) {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loginUser = new IMS();
        $loginUser->login($email, $password);
    }
}

if (isset($_GET['signout'])) {
    $signout = new IMS();
    $signout->signOut();
}


if (isset($_POST['btn-supplier'])) {
    if (isset($_POST['supplier_name'], $_POST['contact_number'])) {
        $supplier_name = $_POST['supplier_name'];
        $contact_number = $_POST['contact_number'];

        $supplier = new IMS();
        $supplier->addSupplier($supplier_name, $contact_number);
    }
}

if (isset($_POST['btn-product'])) {
    if (isset($_POST['product_name'], $_POST['stock'], $_POST['description'])) {
        $product_name = $_POST['product_name'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];

        $product = new IMS();
        $product->addProduct($product_name, $stock, $description);
    }
}

if (isset($_POST['btn-purchase'])) {
    if (isset($_POST['product_id'], $_POST['quantity'], $_POST['supplier_id'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $supplier_id = $_POST['supplier_id'];

        $purchase = new IMS();
        $purchase->purchaseProduct($product_id, $supplier_id, $quantity);
    }
}
