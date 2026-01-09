<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Girik Parfum</title>
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
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
        }

        .status-menunggu { color: orange; font-weight: bold; }
        .status-proses   { color: blue; font-weight: bold; }
        .status-selesai  { color: green; font-weight: bold; }

        .btn {
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-toko {
            background: #ff5e8e;
            color: white;
        }

        .btn-hapus {
            background: #f44336;
            color: white;
        }

        .btn-hapus:hover { background: #d32f2f; }
        .btn-toko:hover  { background: #e94b78; }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }
    </style>
</head>
<body>

<header>
    <h2>📜 Riwayat Pesanan</h2>
    <p>Riwayat transaksi belanja Anda di Girik Parfum</p>
</header>

<div class="container">

<?php
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id DESC");

if ($transaksi && mysqli_num_rows($transaksi) > 0) {
?>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Metode Pembayaran</th>
        <th>Status</th>
        <th style="text-align:center;">Aksi</th>
    </tr>

<?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>
<tr>
    <td><?= $t['tanggal']; ?></td>

    <td style="color:#ff5e8e;font-weight:bold;">
        Rp <?= number_format($t['total']); ?>
    </td>

    <td><?= $t['metode_pembayaran']; ?></td>

    <td>
        <?php
        if ($t['status'] == 'Menunggu Pembayaran') {
            echo "<span class='status-menunggu'>⏳ {$t['status']}</span>";
        } elseif ($t['status'] == 'Diproses') {
            echo "<span class='status-proses'>🚚 {$t['status']}</span>";
        } elseif ($t['status'] == 'Selesai') {
            echo "<span class='status-selesai'>✔ {$t['status']}</span>";
        } else {
            echo $t['status'];
        }
        ?>
    </td>

    <td style="text-align:center;">
        <a href="hapus_riwayat.php?id=<?= $t['id']; ?>"
           class="btn btn-hapus"
           onclick="return confirm('Yakin ingin menghapus riwayat ini?')">
           🗑️ Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>

<?php } else { ?>
    <div class="empty">
        <h3>Belum ada riwayat pesanan</h3>
    </div>
<?php } ?>

<div style="margin-top:20px;text-align:center;">
    <a href="index.php" class="btn btn-toko">⬅️ Kembali ke Toko</a>
</div>

</div>
</body>
</html>
