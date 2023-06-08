<?php
session_start();
require_once '../connect.php';

$kode_transaksi = $_GET["kode_transaksi"];
$tanggal_kembali = date("Y-m-d"); // Mendapatkan tanggal saat ini

// Lakukan query untuk mendapatkan tanggal_pinjam dan kode_buku dari tabel Transaksi dan Detail_Transaksi
$sql = "SELECT t.tanggal_pinjam, dt.kode_buku
        FROM transaksi t
        JOIN detail_transaksi dt ON t.kode_transaksi = dt.kode_transaksi
        WHERE t.kode_transaksi = $1";
$query = pg_prepare($db, "get_transaksi", $sql);
$query = pg_execute($db, "get_transaksi", array($kode_transaksi));
$row = pg_fetch_assoc($query);
$tanggal_pinjam = $row['tanggal_pinjam'];

// Menghitung selisih hari antara tanggal_kembali dan tanggal_pinjam
$selisih_hari = (strtotime($tanggal_kembali) - strtotime($tanggal_pinjam)) / (60 * 60 * 24);

// Inisialisasi variabel denda
$denda = 0;

// Jika selisih hari lebih dari 7 hari (1 minggu)
if ($selisih_hari > 7) {
    $denda = 10000 * ($selisih_hari - 7); // Menghitung denda
}

// Mulai transaksi
pg_query($db, "BEGIN");

try {
    // Update tanggal_kembali dan denda pada tabel Transaksi
    $sql_update_transaksi = "UPDATE transaksi SET tanggal_kembali = $1, denda = $2 WHERE kode_transaksi = $3";
    $query_update_transaksi = pg_prepare($db, "update_transaksi", $sql_update_transaksi);
    $query_update_transaksi = pg_execute($db, "update_transaksi", array($tanggal_kembali, $denda, $kode_transaksi));

    // Update status pada tabel Detail_Transaksi menjadi "kembali"
    $sql_update_detail = "UPDATE detail_transaksi SET status = 'kembali' WHERE kode_transaksi = $1";
    $query_update_detail = pg_prepare($db, "update_detail", $sql_update_detail);
    $query_update_detail = pg_execute($db, "update_detail", array($kode_transaksi));

    // Mengembalikan buku dan menambahkan jumlah buku pada tabel Buku
    $sql_update_buku = "UPDATE buku SET jumlah = jumlah + 1 WHERE kode_buku = $1";
    $query_update_buku = pg_prepare($db, "update_buku", $sql_update_buku);

    $kode_buku = $row['kode_buku'];
    $query_update_buku = pg_execute($db, "update_buku", array($kode_buku));

    // Commit transaksi
    pg_query($db, "COMMIT");

    // Redirect ke halaman index.php
    header("Location: ../index.php");
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    pg_query($db, "ROLLBACK");
    die("Gagal menyimpan perubahan...");
}
