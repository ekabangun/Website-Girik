<?php
include 'koneksi.php';

$id = (int)$_GET['id'];

$qTransaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = $id");
$transaksi = mysqli_fetch_assoc($qTransaksi);

$qDetail = mysqli_query($conn, "
    SELECT d.*, p.nama_parfum
    FROM detail_transaksi d
    JOIN parfum p ON d.parfum_id = p.id
    WHERE d.transaksi_id = $id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }
        .struk {
            width: 350px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border: 1px dashed #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .center {
            text-align: center;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        td {
            padding: 5px 0;
        }
        .total {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
        }
        .btn {
            margin-top: 15px;
            text-align: center;
        }
        button {
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
            }
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk">
    <h2>TOKO PARFUM</h2>
    <div class="center">
        Jl.Jamin Ginting Parang 2<br>
        Telp: 0812-7911-3163
    </div>

    <hr>

    <table>
        <tr>
            <td>No Transaksi</td>
            <td>: <?= $transaksi['id']; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= $transaksi['tanggal']; ?></td>
        </tr>
    </table>

    <hr>

    <table>
        <?php while ($row = mysqli_fetch_assoc($qDetail)) { ?>
        <tr>
            <td colspan="2"><?= htmlspecialchars($row['nama_parfum']); ?></td>
        </tr>
        <tr>
            <td><?= $row['jumlah']; ?> x Rp <?= number_format($row['harga']); ?></td>
            <td align="right">Rp <?= number_format($row['subtotal']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <div class="total">
        TOTAL : Rp <?= number_format($transaksi['total']); ?>
    </div>

    <div class="center" style="margin-top:10px;">
        Terima kasih 🙏<br>
        Barang yang sudah dibeli tidak dapat dikembalikan
    </div>

    <div class="btn">
        <button onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</div>

</body>
</html>
