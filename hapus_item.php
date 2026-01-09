<?php
session_start();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);
    }
}

header("Location: lihat_keranjang.php");
exit;
