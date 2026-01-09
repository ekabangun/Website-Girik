<?php
session_start();
include 'koneksi.php';

// Pastikan ada ID produk yang dikirim
if (isset($_GET['id'])) {
    $id_parfum = $_GET['id'];
    $jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;

    // 1. Ambil detail produk dari database untuk memastikan data valid
    $ambil = mysqli_query($conn, "SELECT * FROM parfum WHERE id = '$id_parfum'");
    $pecah = mysqli_fetch_assoc($ambil);

    if ($pecah) {
        // 2. Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($_SESSION['keranjang'][$id_parfum])) {
            $_SESSION['keranjang'][$id_parfum] += $jumlah;
        } 
        // 3. Jika belum ada, masukkan sebagai item baru
        else {
            $_SESSION['keranjang'][$id_parfum] = $jumlah;
        }

        // Alihkan ke halaman lihat keranjang dengan pesan sukses
        echo "<script>alert('Produk berhasil ditambahkan kembali ke keranjang!');</script>";
        echo "<script>location='lihat_keranjang.php';</script>";
    } else {
        // Jika ID produk tidak ditemukan di database
        echo "<script>alert('Produk tidak ditemukan atau sudah dihapus.');</script>";
        echo "<script>location='riwayat.php';</script>";
    }
} else {
    // Jika diakses tanpa parameter ID
    header("Location: index.php");
    exit();
}
?>