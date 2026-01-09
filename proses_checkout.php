<?php
session_start();
include 'koneksi.php';

/* ❌ CEGAH AKSES LANGSUNG */
if (!isset($_POST['checkout'])) {
    header("Location: checkout.php");
    exit;
}

/* ❌ VALIDASI INPUT */
if (
    empty($_POST['nama']) ||
    empty($_POST['hp']) ||
    empty($_POST['alamat']) ||
    empty($_POST['metode'])
) {
    header("Location: checkout.php");
    exit;
}

/* SIMPAN & AMANKAN DATA */
$nama   = mysqli_real_escape_string($conn, $_POST['nama']);
$hp     = mysqli_real_escape_string($conn, $_POST['hp']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$metode = mysqli_real_escape_string($conn, $_POST['metode']);

/* CEK KERANJANG */
if (empty($_SESSION['keranjang'])) {
    header("Location: lihat_keranjang.php");
    exit;
}

/* ================= UPLOAD BUKTI PEMBAYARAN ================= */
$bukti_pembayaran = NULL;
$status = 'Menunggu Pembayaran';

if ($metode !== 'COD') {

    if (empty($_FILES['bukti']['name'])) {
        header("Location: checkout.php");
        exit;
    }

    if (!is_dir('bukti')) {
        mkdir('bukti', 0777, true);
    }

    $nama_file = time() . '_' . basename($_FILES['bukti']['name']);
    $path = 'bukti/' . $nama_file;

    if (move_uploaded_file($_FILES['bukti']['tmp_name'], $path)) {
        $bukti_pembayaran = $nama_file;
        $status = 'Menunggu Verifikasi';
    } else {
        header("Location: checkout.php");
        exit;
    }
} else {
    $status = 'Diproses';
}

/* ================= HITUNG TOTAL ================= */
$tanggal = date('Y-m-d H:i:s');
$total_bayar = 0;

foreach ($_SESSION['keranjang'] as $id => $jumlah) {
    $id = (int)$id;
    $q = mysqli_query($conn, "SELECT harga FROM parfum WHERE id=$id");
    if ($q && mysqli_num_rows($q) > 0) {
        $p = mysqli_fetch_assoc($q);
        $total_bayar += $p['harga'] * $jumlah;
    }
}

/* ================= SIMPAN TRANSAKSI ================= */
mysqli_query($conn, "INSERT INTO transaksi 
    (tanggal, total, nama_penerima, no_hp, alamat, metode_pembayaran, bukti_pembayaran, status)
    VALUES (
        '$tanggal',
        '$total_bayar',
        '$nama',
        '$hp',
        '$alamat',
        '$metode',
        '$bukti_pembayaran',
        '$status'
    )");

$transaksi_id = mysqli_insert_id($conn);

/* ================= DETAIL TRANSAKSI & STOK ================= */
foreach ($_SESSION['keranjang'] as $id => $jumlah) {
    $id = (int)$id;

    $q = mysqli_query($conn, "SELECT harga, stok FROM parfum WHERE id=$id");
    if ($q && mysqli_num_rows($q) > 0) {
        $p = mysqli_fetch_assoc($q);

        $harga    = $p['harga'];
        $subtotal = $harga * $jumlah;

        mysqli_query($conn, "INSERT INTO detail_transaksi
            (transaksi_id, parfum_id, harga, jumlah, subtotal)
            VALUES
            ('$transaksi_id','$id','$harga','$jumlah','$subtotal')");

        mysqli_query($conn, "UPDATE parfum 
            SET stok = stok - $jumlah 
            WHERE id = $id");
    }
}

/* KOSONGKAN KERANJANG */
unset($_SESSION['keranjang']);

/* REDIRECT */
header("Location: checkout_sukses.php?id=$transaksi_id");
exit;
