<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Hapus detail transaksi terlebih dahulu (Foreign Key Check)
    mysqli_query($conn, "DELETE FROM transaksi_detail WHERE transaksi_id = $id");

    // 2. Hapus transaksi utama
    $hapus = mysqli_query($conn, "DELETE FROM transaksi WHERE id = $id");

    if ($hapus) {
        echo "<script>alert('Riwayat berhasil dihapus!'); window.location='riwayat.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus riwayat.'); window.location='riwayat.php';</script>";
    }
} else {
    header("Location: riwayat.php");
}
?>