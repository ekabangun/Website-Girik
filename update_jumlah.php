<?php
session_start();

if (isset($_GET['id']) && isset($_GET['aksi'])) {

    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];

    if (isset($_SESSION['keranjang'][$id])) {

        if ($aksi == 'tambah') {
            $_SESSION['keranjang'][$id]++;

        } elseif ($aksi == 'kurang') {
            $_SESSION['keranjang'][$id]--;

            // Jika jumlah 0 → hapus item
            if ($_SESSION['keranjang'][$id] <= 0) {
                unset($_SESSION['keranjang'][$id]);
            }
        }
    }
}

header("Location: lihat_keranjang.php");
exit;
