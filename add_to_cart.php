<?php
session_start();
require "config/config.php";

if($_POST){
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch();

    if($qty > $result['quantity']){
        echo "<script>alert('Not enough stock!');window.location.href='product_detail.php?product_detail_id=$id';</script>";
    }else{
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else {
            $_SESSION['cart']['id'.$id] = $qty;
        }
    
        header('location: cart.php');
    }

}