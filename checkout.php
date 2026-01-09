<?php
session_start();

// Validasi jika keranjang kosong, tidak bisa checkout
if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang Anda kosong, silakan belanja dulu!'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Girik Parfum</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 40px 20px;
        }
        .box {
            max-width: 500px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        label {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #ff5e8e;
            box-shadow: 0 0 5px rgba(255, 94, 142, 0.3);
        }
        .ongkir-info {
            background: #fff0f3;
            border: 1px dashed #ff5e8e;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
        }
        .ongkir-info span {
            display: block;
            font-size: 18px;
            color: #ff5e8e;
            font-weight: bold;
        }
        button {
            width: 100%;
            background: linear-gradient(135deg, #ff5e8e, #ff9a9e);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            margin-top: 25px;
            transition: 0.3s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 94, 142, 0.4);
        }
        .hidden {
            display: none;
        }
        small {
            color: #888;
            font-size: 12px;
        }
    </style>

    <script>
        // Data Ongkir per Wilayah
        const biayaWilayah = {
            "Jakarta": 10000,
            "Bandung": 15000,
            "Semarang": 20000,
            "Surabaya": 25000,
            "Medan": 35000,
            "Makassar": 40000,
            "Luar Pulau": 55000
        };

        function updateOngkir() {
            const kota = document.getElementById("kota").value;
            const infoBox = document.getElementById("info_ongkir");
            const teksOngkir = document.getElementById("tampil_ongkir");
            const inputOngkir = document.getElementById("input_ongkir");

            if (kota in biayaWilayah) {
                const biaya = biayaWilayah[kota];
                infoBox.classList.remove("hidden");
                teksOngkir.innerText = "Rp " + biaya.toLocaleString('id-ID');
                inputOngkir.value = biaya;
            } else {
                infoBox.classList.add("hidden");
                inputOngkir.value = 0;
            }
        }

        function cekMetode() {
            const metode = document.getElementById("metode").value;
            const buktiBox = document.getElementById("bukti_box");
            const inputBukti = document.getElementById("bukti");

            if (metode !== "COD" && metode !== "") {
                buktiBox.classList.remove("hidden");
                inputBukti.required = true;
            } else {
                buktiBox.classList.add("hidden");
                inputBukti.required = false;
            }
        }
    </script>
</head>
<body>

<div class="box">
    <h2>🚚 Checkout</h2>

    <form method="POST" action="proses_checkout.php" enctype="multipart/form-data">
        
        <label>Nama Lengkap Penerima</label>
        <input type="text" name="nama" placeholder="Masukkan nama penerima" required>

        <label>Nomor WhatsApp</label>
        <input type="text" name="hp" placeholder="Contoh: 08123456789" required>

        <label>Alamat Lengkap</label>
        <textarea name="alamat" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, Kecamatan" required></textarea>

        <label>Wilayah Pengiriman</label>
        <select name="kota" id="kota" onchange="updateOngkir()" required>
            <option value="">-- Pilih Wilayah --</option>
            <option value="Jakarta">DKI Jakarta</option>
            <option value="Bandung">Bandung</option>
            <option value="Semarang">Semarang</option>
            <option value="Surabaya">Surabaya</option>
            <option value="Medan">Medan</option>
            <option value="Makassar">Makassar</option>
            <option value="Luar Pulau">Luar Pulau Lainnya</option>
        </select>

        <label>Pilih Ekspedisi</label>
        <select name="ekspedisi" required>
            <option value="">-- Pilih Kurir --</option>
            <option value="JNT">J&T Express</option>
            <option value="JNE">JNE Reguler</option>
            <option value="Sicepat">SiCepat Halu</option>
            <option value="GoSend">GoSend (Sameday)</option>
        </select>

        <div id="info_ongkir" class="ongkir-info hidden">
            Ongkos Kirim:
            <span id="tampil_ongkir">Rp 0</span>
            <input type="hidden" name="ongkir" id="input_ongkir" value="0">
        </div>

        <label>Metode Pembayaran</label>
        <select name="metode" id="metode" onchange="cekMetode()" required>
            <option value="">-- Pilih Pembayaran --</option>
            <option value="COD">COD (Bayar di Tempat)</option>
            <option value="Transfer Bank">Transfer Bank (BCA/Mandiri)</option>
            <option value="E-Wallet">E-Wallet (Dana/OVO/GoPay)</option>
        </select>

        <div id="bukti_box" class="hidden">
            <label>Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" id="bukti" accept="image/*">
            <small>* Wajib upload foto bukti transfer jika bukan COD</small>
        </div>

        <button type="submit" name="checkout">Konfirmasi Pesanan</button>
    </form>
</div>

</body>
</html>