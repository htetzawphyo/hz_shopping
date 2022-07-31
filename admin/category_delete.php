<?php 

    require "../config/config.php";

    $id = $_GET['deleteId'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=$id");
    $stmt->execute();

    header('location: category.php');