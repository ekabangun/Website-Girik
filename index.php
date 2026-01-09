<?php
session_start();
include 'koneksi.php';

// 1. Logika Pencarian & Kategori
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : "";
$kategori = isset($_GET['kat']) ? mysqli_real_escape_string($conn, $_GET['kat']) : "";

// Query dasar
$query = "SELECT * FROM parfum WHERE 1=1";

if ($keyword != "") {
    $query .= " AND nama_parfum LIKE '%$keyword%'";
}

if ($kategori != "") {
    $query .= " AND kategori = '$kategori'";
}

$data = mysqli_query($conn, $query);

// 2. Menghitung jumlah item di keranjang untuk lencana (badge)
$jumlah_keranjang = 0;
if(isset($_SESSION['keranjang'])){
    foreach($_SESSION['keranjang'] as $id => $jumlah){
        $jumlah_keranjang += $jumlah;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Girik Parfum - Koleksi Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink: #ff5e8e;
            --dark: #2d3436;
            --bg: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding-bottom: 100px;
        }

        /* ===== HEADER ===== */
        header {
            background: linear-gradient(135deg, var(--pink), #ff9a9e);
            color: white;
            padding: 40px 20px 60px;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }

        /* ===== SEARCH BOX ===== */
        .search-wrapper {
            max-width: 500px;
            margin: -30px auto 20px;
            padding: 0 15px;
        }

        .search-box {
            display: flex;
            background: white;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .search-box input {
            flex: 1;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            outline: none;
            font-size: 14px;
        }

        .search-box button {
            background: var(--pink);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
        }

        /* ===== NAV MENU (RIWAYAT & KERANJANG) ===== */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .nav-link {
            text-decoration: none;
            color: var(--dark);
            background: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .nav-link:hover {
            background: var(--pink);
            color: white;
            transform: translateY(-2px);
        }

        /* ===== CATEGORY NAV ===== */
        .category-nav {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .cat-btn {
            text-decoration: none;
            padding: 8px 18px;
            background: #eee;
            color: #666;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
        }

        .cat-btn.active {
            background: var(--dark);
            color: white;
        }

        /* ===== GRID PRODUK ===== */
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
        }

        .card:hover { transform: translateY(-10px); }
        .card img { width: 100%; height: 250px; object-fit: cover; }
        .card-body { padding: 20px; text-align: center; flex-grow: 1; }
        .card-body h3 { margin: 0 0 10px; font-size: 18px; }
        .price { color: var(--pink); font-weight: 700; font-size: 20px; margin-bottom: 15px; }

        .btn-buy {
            width: 100%;
            background: var(--dark);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-buy:hover { background: var(--pink); }

        /* ===== FLOATING CART ===== */
        .floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--pink);
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(255, 94, 142, 0.4);
            z-index: 1000;
        }

        .floating-cart .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--dark);
            color: white;
            font-size: 11px;
            padding: 4px 7px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .not-found { grid-column: 1/-1; text-align: center; padding: 50px; color: #999; }
    </style>
</head>
<body>

<header>
    <h2>Girik Parfum</h2>
    <p>Temukan aroma jati dirimu di sini</p>
</header>

<div class="search-wrapper">
    <div class="search-box">
        <form action="" method="GET" style="display: flex; width: 100%;">
            <input type="text" name="cari" placeholder="Cari nama parfum..." value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit">Cari</button>
        </form>
    </div>
</div>

<div class="nav-menu">
    <a href="riwayat.php" class="nav-link">📜 Riwayat Pesanan</a>
    <a href="lihat_keranjang.php" class="nav-link">🛒 Keranjang</a>
</div>

<div class="category-nav">
    <a href="index.php" class="cat-btn <?= ($kategori == '') ? 'active' : '' ?>">Semua</a>
    <a href="index.php?kat=Pria" class="cat-btn <?= ($kategori == 'Pria') ? 'active' : '' ?>">Pria</a>
    <a href="index.php?kat=Wanita" class="cat-btn <?= ($kategori == 'Wanita') ? 'active' : '' ?>">Wanita</a>
    <a href="index.php?kat=Unisex" class="cat-btn <?= ($kategori == 'Unisex') ? 'active' : '' ?>">Unisex</a>
</div>

<a href="lihat_keranjang.php" class="floating-cart">
    <span style="font-size: 25px;">🛒</span>
    <?php if($jumlah_keranjang > 0): ?>
        <span class="badge"><?= $jumlah_keranjang ?></span>
    <?php endif; ?>
</a>



<div class="container">
    <?php if (mysqli_num_rows($data) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($data)): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($p['gambar']) ?>" alt="parfum">
                <div class="card-body">
                    <h3><?= htmlspecialchars($p['nama_parfum']) ?></h3>
                    <div class="price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                    
                    <form method="POST" action="keranjang.php">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="jumlah" value="1">
                        <button type="submit" name="beli" class="btn-buy">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="not-found">
            <h3>Ups! Produk tidak ditemukan.</h3>
            <p>Coba kata kunci lain atau pilih kategori yang tersedia.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>