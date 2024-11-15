<?php

include_once __DIR__ . '/../../config/settings-config.php';
require_once __DIR__ . '/../../database/dbconnection.php';


class IMS {

    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function addUser($username, $email, $password) {
        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));
    
        if ($stmt->rowCount() > 0) {
            echo "<script> alert('Email already exists'); window.location.href = '../../index.php'; </script>";
            exit; 
        }
    
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->runQuery('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $execute = $stmt->execute(array(":username" => $username, ":email" => $email, ":password" => $hash_password));
    
        if ($execute) {
            echo "<script> alert('User Added'); window.location.href = '../../index.php'; </script>";
            exit;  
        } else {
            echo "<script> alert('Error'); window.location.href = '../../index.php'; </script>";
            exit;  
        }
    }
    

    public function login($email, $password) {

        $stmt = $this->runQuery("SELECT * FROM users WHERE email = :email");
        $stmt->execute(array(":email" => $email));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($stmt->rowCount() == 1) {
            if (password_verify($password, $userRow['password'])) {
                $role = $userRow['role'];
                $activiy = "the $role has successfully login";
                $user_id = $userRow['id'];

                $_SESSION['session'] = $user_id;


                $this->logs($activiy,$user_id);
                $this->redirect($userRow['role']);
            } else {
                echo "<script> alert('Incorrect Password'); window.location.href = '../../'; </script>";
            }
        } else {
            echo "<script> alert('User Not Found'); window.location.href = '../../'; </script>";
        }
    }
    

    public function runQuery($sql) {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function logs($activiy, $user_id) {
        $stmt = $this->runQuery("INSERT INTO  logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array("user_id"=>$user_id, ":activity"=>$activiy));
    }

    public function redirect($role) {
        if ($role === "admin") {
            echo "<script> alert ('Welcome Admin!'); window.location.href = '../admin/index.php'; </script>";
        } elseif ($role === "user") {
            echo "<script> alert ('Welcome User!'); window.location.href = '../user/index.php'; </script>";
        } else {
            echo "<script> alert('Role not recognized'); window.location.href = '../../'; </script>";
        }
    }

    public function isUserLogged() {
        if (isset($_SESSION['session'])){
            return true;
        }
    }

    public function signOut() {
        unset($_SESSION['session']);
        echo "<script> alert('Signout Successfully'); window.location.href = '../../'; </script>";
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

if(isset($_GET['signout'])) {
    $signout = new IMS();
    $signout->signOut();
}



?>