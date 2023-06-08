<?php
require_once '../connect.php';

if (isset($_POST["tambah_petugas"])) {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_hp = $_POST["no_hp"];

    $sql = "INSERT INTO petugas (kode_petugas, nama, email, no_hp) VALUES (nextval('kode_petugas'), '$nama', '$email', '$no_hp')";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../petugas.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else if (isset($_GET["kode_petugas"])) {
    $kode_petugas = $_GET["kode_petugas"];

    $sql = "DELETE FROM petugas WHERE kode_petugas = '$kode_petugas'";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../petugas.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else if (isset($_POST["edit_petugas"])) {
    $kode_petugas = $_POST["kode_petugas"];
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_hp = $_POST["no_hp"];

    $sql = "UPDATE petugas SET nama='$nama', email='$email', no_hp='$no_hp' WHERE kode_petugas = '$kode_petugas'";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../petugas.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else {
    die("Akses dilarang...");
}
