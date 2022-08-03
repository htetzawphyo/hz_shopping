<?php 

    require "../config/config.php";

    $id = $_GET['deleteId'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=$id");
    $stmt->execute();

    header('location: index.php');