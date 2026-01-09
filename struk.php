<?php
session_start();
include 'koneksi.php';

// generate nomor pesanan
$no_pesanan = "ORD-" . date("Ymd") . "-" . rand(1000,9999);

// simpan nomor pesanan ke session (opsional)
$_SESSION['no_pesanan'] = $no_pesanan;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f2f2f2;
        }

        .struk {
            width: 420px;
            background: white;
            margin: 30px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .center {
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        hr {
            margin: 15px 0;
        }

        table {
            width: 100%;
            font-size: 14px;
        }

        td {
            padding: 6px 0;
        }

        .total {
            font-weight: bold;
            font-size: 16px;
        }

        .btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background: #ff5e8e;
            color: white;
            padding: 10px;
            border-radius: 20px;
        }

        .btn:hover {
            background: #e94b78;
        }

        @media print {
            .btn { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="struk">
    <h2>TOKO PARFUM</h2>
    <div class="center">
        Jl. Contoh No. 123<br>
        Telp: 0812-3456-7890
    </div>

    <hr>

    <table>
        <tr>
            <td>No Pesanan</td>
            <td align="right"><?= $no_pesanan; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td align="right"><?= date("d-m-Y H:i"); ?></td>
        </tr>
    </table>

    <hr>

    <table>
        <?php
        $total_bayar = 0;

        if (!empty($_SESSION['keranjang'])) {
            foreach ($_SESSION['keranjang'] as $id => $jumlah) {

                $id = (int)$id;
                if ($id <= 0) continue;

                $q = mysqli_query($conn, "SELECT * FROM parfum WHERE id = $id");
                if ($q && mysqli_num_rows($q) > 0) {
                    $p = mysqli_fetch_assoc($q);
                    $subtotal = $p['harga'] * $jumlah;
                    $total_bayar += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($p['nama_parfum']); ?> x<?= $jumlah; ?></td>
            <td align="right">Rp <?= number_format($subtotal); ?></td>
        </tr>
        <?php } } } ?>
    </table>

    <hr>

    <table>
        <tr class="total">
            <td>Total</td>
            <td align="right">Rp <?= number_format($total_bayar); ?></td>
        </tr>
    </table>

    <hr>

    <div class="center">
        Terima kasih telah berbelanja 😊<br>
        Barang yang sudah dibeli tidak dapat dikembalikan
    </div>

    <a class="btn" href="#" onclick="window.print()">🖨️ Cetak Struk</a>
    <a href="checkout.php" class="btn">✅ Selesaikan Pembayaran</a>

</div>

</body>
</html>
