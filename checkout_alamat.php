<?php
session_start();

if (empty($_SESSION['keranjang'])) {
    header("Location: lihat_keranjang.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Alamat Pengiriman</title>
<style>
body{font-family:Segoe UI;background:#f2f2f2;padding-top:80px}
.box{width:420px;background:white;margin:auto;padding:30px;border-radius:15px}
input,textarea,button{width:100%;padding:12px;margin-top:10px}
button{background:#ff5e8e;color:white;border:none;border-radius:25px}
</style>
</head>
<body>

<div class="box">
<h2>Alamat Pengiriman</h2>

<form method="POST" action="proses_checkout.php">
    <input type="text" name="nama" placeholder="Nama Penerima" required>
    <input type="text" name="hp" placeholder="No HP" required>
    <textarea name="alamat" placeholder="Alamat Lengkap" required></textarea>
    <button type="submit" name="checkout">Checkout</button>
</form>
</div>

</body>
</html>
