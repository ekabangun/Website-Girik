<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f2f2f2;
        }
        header {
            background: linear-gradient(135deg, #ff5e8e, #ff9a9e);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #ff5e8e;
            color: white;
            padding: 12px;
        }
        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #fafafa;
        }
        .total-row td {
            font-size: 18px;
            font-weight: bold;
            color: #ff5e8e;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            background: #ff5e8e;
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s;
            margin-left: 10px;
        }
        .btn:hover {
            background: #e94b78;
        }
        .btn-secondary {
            background: #555;
        }
        .btn-secondary:hover {
            background: #333;
        }
        .hapus {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
        .qty a {
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            color: #ff5e8e;
            margin: 0 8px;
        }
        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }
        .actions {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>

<header>
    <h2>🛒 Keranjang Belanja</h2>
    <p>Periksa kembali pesanan Anda</p>
</header>

<div class="container">

<?php
$total_bayar = 0;

if (!empty($_SESSION['keranjang'])) {
?>
<table>
<tr>
    <th>Nama Parfum</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php
foreach ($_SESSION['keranjang'] as $id => $jumlah) {

    $id = (int)$id;
    if ($id <= 0) continue;

    $query = mysqli_query($conn, "SELECT * FROM parfum WHERE id = $id");

    if ($query && mysqli_num_rows($query) > 0) {
        $p = mysqli_fetch_assoc($query);

        $total = $p['harga'] * $jumlah;
        $total_bayar += $total;
?>
<tr>
    <td><?= htmlspecialchars($p['nama_parfum']); ?></td>
    <td>Rp <?= number_format($p['harga']); ?></td>

    <!-- JUMLAH + / - -->
    <td class="qty">
        <a href="update_jumlah.php?id=<?= $id; ?>&aksi=kurang">➖</a>
        <strong><?= (int)$jumlah; ?></strong>
        <a href="update_jumlah.php?id=<?= $id; ?>&aksi=tambah">➕</a>
    </td>

    <td>Rp <?= number_format($total); ?></td>

    <td>
        <a class="hapus"
           href="hapus_item.php?id=<?= $id; ?>"
           onclick="return confirm('Yakin hapus item ini?')">
           ❌ Hapus
        </a>
    </td>
</tr>
<?php } } ?>

<tr class="total-row">
    <td colspan="3">Total Bayar</td>
    <td colspan="2">Rp <?= number_format($total_bayar); ?></td>
</tr>
</table>

<div class="actions">
    <a href="index.php" class="btn btn-secondary">⬅️ Belanja Lagi</a>
    <a href="proses_checkout.php" class="btn">💳 Checkout</a>

</div>

<?php } else { ?>

<div class="empty">
    <h3>Keranjang masih kosong 😢</h3>
    <p>Silakan pilih parfum favorit Anda</p>
    <a href="index.php" class="btn">Mulai Belanja</a>
</div>

<?php } ?>

</div>

</body>
</html>
