<?php 

    require "../config/config.php";

    $id = $_GET['deleteId'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=$id");
    $stmt->execute();

    header('location: user_list.php');