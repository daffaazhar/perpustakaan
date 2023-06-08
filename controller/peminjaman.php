<?php
session_start();
require_once '../connect.php';

if (isset($_POST["pinjam"])) {
    $peminjam = $_POST["peminjam"];
    $petugas = $_POST["petugas"];
    $start = $_POST["start"];
    $jumlah_buku = $_POST["jumlah_buku"];

    if ($jumlah_buku > 3) {
        $_SESSION['message'] = "Maksimal 3 buku";
        header("Location: ../index.php");
        exit;
    }

    // Lakukan transaksi peminjaman
    $sql_transaksi = "INSERT INTO transaksi (kode_transaksi, kode_anggota, kode_petugas, tanggal_pinjam) VALUES (nextval('kode_transaksi'), '$peminjam', '$petugas', '$start') RETURNING kode_transaksi";
    $query_transaksi = pg_query($db, $sql_transaksi);

    if ($query_transaksi) {
        // Ambil kode_transaksi yang baru saja di-generate
        $kode_transaksi = pg_fetch_result($query_transaksi, 0, 'kode_transaksi');

        // Loop untuk setiap buku yang ingin dipinjam
        for ($i = 0; $i < $jumlah_buku; $i++) {
            $kode_buku = $_POST["buku"][$i];

            // Kurangi jumlah buku yang dipinjam
            $sql_update_buku = "UPDATE buku SET jumlah = jumlah - 1 WHERE kode_buku = '$kode_buku'";
            $query_update_buku = pg_query($db, $sql_update_buku);

            if (!$query_update_buku) {
                die("Gagal menyimpan perubahan...");
            }

            // Masukkan data peminjaman buku ke tabel "Detail_Transaksi"
            $sql_detail = "INSERT INTO detail_transaksi (kode_detail_transaksi, kode_transaksi, kode_buku) VALUES (nextval('kode_detail_transaksi'), '$kode_transaksi', '$kode_buku')";
            pg_query($db, $sql_detail);
        }

        header("Location: ../index.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else {
    die("Akses dilarang...");
}
