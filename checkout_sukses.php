<?php
// Ambil ID transaksi dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Berhasil</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #ff5e8e, #ff9a9e);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box {
            background: white;
            width: 420px;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            text-align: center;
            animation: fadeIn 0.7s ease-in-out;
        }
        h2 {
            color: #ff5e8e;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 15px;
            margin: 8px 0;
        }
        .trx {
            background: #fff0f5;
            border: 1px dashed #ff5e8e;
            padding: 10px;
            margin: 15px 0;
            border-radius: 10px;
            font-weight: bold;
            color: #ff5e8e;
        }
        .btn {
            display: block;
            text-decoration: none;
            background: #ff5e8e;
            color: white;
            padding: 12px;
            margin-top: 12px;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
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

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="box">
    <h2>✅ Checkout Berhasil</h2>
    <p>Terima kasih telah berbelanja di</p>
    <p><strong>Toko Parfum</strong></p>

    <div class="trx">
        No. Transaksi<br>
        #<?= $id; ?>
    </div>

    <a href="cetak_struk_manual.php?id=<?= $id; ?>" 
       class="btn" target="_blank">
       🧾 Cetak Struk
    </a>

    <a href="index.php" class="btn btn-secondary">
        🛍️ Kembali ke Toko
    </a>
</div>

</body>
</html>
