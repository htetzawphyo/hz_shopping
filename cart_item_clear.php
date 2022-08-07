<?php
session_start();

unset($_SESSION['cart']['id'.$_GET['pId']]);

header('location: cart.php');