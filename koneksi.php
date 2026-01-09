<?php
$conn = mysqli_connect("localhost", "root", "", "toko_parfum");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
