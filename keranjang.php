<?php
session_start();

$id = $_POST['id'];
$jumlah = $_POST['jumlah'];

$_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + $jumlah;

header("Location: index.php");
?>
