<?php
require_once '../connect.php';

if (isset($_POST["tambah_anggota"])) {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_hp = $_POST["no_hp"];

    $sql = "INSERT INTO anggota (kode_anggota, nama, email, no_hp) VALUES (nextval('kode_anggota'), '$nama', '$email', '$no_hp')";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../anggota.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else if (isset($_GET["kode_anggota"])) {
    $kode_anggota = $_GET["kode_anggota"];

    $sql = "DELETE FROM anggota WHERE kode_anggota = '$kode_anggota'";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../anggota.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else if (isset($_POST["edit_anggota"])) {
    $kode_anggota = $_POST["kode_anggota"];
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $no_hp = $_POST["no_hp"];

    $sql = "UPDATE anggota SET nama='$nama', email='$email', no_hp='$no_hp' WHERE kode_anggota = '$kode_anggota'";
    $query = pg_query($db, $sql);
    if ($query) {
        header("Location: ../anggota.php");
        exit();
    } else {
        die("Gagal menyimpan perubahan...");
    }
} else {
    die("Akses dilarang...");
}
