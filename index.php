<?php
session_start();
require_once 'connect.php';

// Execute a SELECT query
$query = pg_query($db, "SELECT * FROM anggota");
$members = pg_fetch_all($query);

$lib_query = pg_query($db, "SELECT * FROM petugas");
$librarians = pg_fetch_all($lib_query);

$transaksi_query = pg_query($db, "SELECT t.*, t.kode_transaksi AS peminjaman, a.nama AS peminjam, p.nama AS petugas FROM transaksi t JOIN anggota a ON t.kode_anggota = a.kode_anggota JOIN petugas p ON t.kode_petugas = p.kode_petugas ORDER BY t.kode_transaksi DESC");
$borrows = pg_fetch_all($transaksi_query);

$book_query = pg_query($db, "SELECT * FROM buku JOIN kategori ON buku.kode_kategori = kategori.kode_kategori WHERE buku.jumlah > 0");
$books = pg_fetch_all($book_query);

$dipinjam_query = pg_query($db, "SELECT COUNT(kode_buku) AS jumlah FROM detail_transaksi WHERE status IS NULL");
$pinjem = pg_fetch_assoc($dipinjam_query);
$jumlah_dipinjam = $pinjem['jumlah']; // Mengambil jumlah dipinjam dari hasil query

$telat_query = pg_query($db, "SELECT COUNT(*) AS jumlah FROM transaksi WHERE denda > 0");
$telat = pg_fetch_assoc($telat_query);
$jumlah_telat = $telat['jumlah'];

$anggota_query = pg_query($db, "SELECT COUNT(*) AS jumlah FROM anggota");
$anggota = pg_fetch_assoc($anggota_query);
$jumlah_anggota = $anggota['jumlah'];
// Check if the query was successful

date_default_timezone_set('Asia/Jakarta');

// Mendapatkan tanggal hari ini
$tanggal = date('d F Y');

// Mendapatkan nama hari pada tanggal hari ini
$hari = date('l', strtotime($tanggal));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Perpustakaan</title>
</head>

<body class="scroll-smooth">
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 border-r border-[#edeced]" aria-label="Sidebar">
        <div class="h-full py-4 overflow-y-auto bg-gray-50">
            <div class="flex items-center pl-4 mb-6">
                <img src="./img/logo-himit.png" class="w-14 mr-3" alt="Flowbite Logo" />
                <span class="self-center text-xl font-bold whitespace-nowrap text-black">Perpustakaan</span>
            </div>
            <ul class="space-y-2 font-medium">
                <li class="relative px-6 py-3">
                    <span class="absolute inset-y-0 left-0 w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg"></span>
                    <a href="#" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl bx bx-home"></i>
                        <span class="text-base font-semibold ml-4">Perpustakaan</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./petugas.php" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl text-[#707275] bx bx-user group-hover:text-black transition duration-200"></i>
                        <span class="text-base text-[#707275] font-semibold ml-4 group-hover:text-black transition duration-200">Petugas</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./anggota.php" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl text-[#707275] bx bx-user group-hover:text-black transition duration-200"></i>
                        <span class="text-base text-[#707275] font-semibold ml-4 group-hover:text-black transition duration-200">Anggota</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./buku.php" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl text-[#707275] bx bx-book group-hover:text-black transition duration-200"></i>
                        <span class="text-base text-[#707275] font-semibold ml-4 group-hover:text-black transition duration-200">Buku</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="bg-[#f9fafb] p-6 sm:ml-64 h-screen">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-semibold text-black">Dashboard</h1>
            <div class="flex items-center gap-x-4">
                <div class="relative rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
                    <i class="text-lg text-[#6b7280] bx bx-bell"></i>
                    <span class="absolute bg-red-600 rounded-full w-2 h-2 top-0 right-0"></span>
                </div>
                <div class="relative rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
                    <i class="text-lg text-[#6b7280] bx bx-comment-dots"></i>
                    <span class="absolute bg-red-600 rounded-full w-2 h-2 top-0 right-0"></span>
                </div>
                <label for="peminjaman" data-modal-target="peminjaman-modal" data-modal-toggle="peminjaman-modal" class="px-4 py-2 flex items-center justify-center bg-white border border-gray-200 cursor-pointer rounded-lg hover:bg-[#2363DE] transition text-[#6b7280] hover:text-[#ffffff]">
                    Peminjaman
                </label>
            </div>
        </div>

        <div class="mb-6">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                    <div class="w-12 h-12 mr-4 text-blue-500 bg-[#faed8c] rounded-full flex justify-center items-center">
                        <i class='text-[#c2ac08] bx bxs-calendar text-lg'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">
                            <?= $hari ?>
                        </p>
                        <p class="text-lg font-semibold text-gray-700">
                            <?= $tanggal ?>
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                    <div class="w-12 h-12 mr-4 text-blue-500 bg-blue-100 rounded-full flex justify-center items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">
                            Jumlah Anggota
                        </p>
                        <p class="text-lg font-semibold text-gray-700">
                            <?= $jumlah_anggota ?>
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                    <div class="w-12 h-12 mr-4 text-orange-500 bg-orange-100 rounded-full flex justify-center items-center">
                        <i class='bx bxs-book text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">
                            Buku yang Dipinjam
                        </p>
                        <p class="text-lg font-semibold text-gray-700">
                            <?= $jumlah_dipinjam ?>
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                    <div class="w-12 h-12 mr-4 text-green-500 bg-green-100 rounded-full flex justify-center items-center">
                        <i class='bx bxs-time text-lg'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">
                            Telat Pengembalian
                        </p>
                        <p class="text-lg font-semibold text-gray-700">
                            <?= $jumlah_telat ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-x-6">
            <div class="bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                <div class="flex items-center justify-between mb-4 px-6 pt-6">
                    <h2 class="font-semibold text-xl text-black">Data Peminjaman Buku</h2>
                </div>
                <div class="overflow-hidden">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b">
                                    <th class="pl-6 pr-4 py-3">Kode</th>
                                    <th class="pl-6 pr-4 py-3">Peminjam</th>
                                    <th class="pl-6 pr-4 py-3">Petugas</th>
                                    <th class="pl-6 pr-4 py-3">Tanggal Pinjam</th>
                                    <th class="pl-6 pr-4 py-3">Tanggal Kembali</th>
                                    <th class="pl-6 pr-4 py-3 text-center" colspan="2">Status</th>
                                    <th class="pl-6 pr-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <?php if (count($borrows) == 0) : ?>
                                    <td colspan="7" class="text-sm text-[#4b5563] pl-6 py-4">
                                        Tidak ada data yang dapat ditampilkan
                                    </td>
                                <?php else : ?>
                                    <?php foreach ($borrows as $borrow) : ?>
                                        <tr class="text-gray-700">
                                            <td class="pl-6 pr-4 py-3 text-sm"><?= $borrow['peminjaman'] ?></td>
                                            <td class="pl-6 pr-4 py-3 text-sm"><?= $borrow['peminjam'] ?></td>
                                            <td class="pl-6 pr-4 py-3 text-sm"><?= $borrow['petugas'] ?></td>
                                            <td class="pl-6 pr-4 py-3 text-sm"><?= $borrow['tanggal_pinjam'] ?></td>
                                            <td class="pl-6 pr-4 py-3 text-sm">
                                                <?php if ($borrow['tanggal_kembali'] == null) : ?>
                                                    -
                                                <?php elseif ($borrow['tanggal_kembali'] !== null) : ?>
                                                    <?= $borrow['tanggal_kembali'] ?>
                                                <?php endif ?>
                                            </td>
                                            <td class="pl-6 pr-4 py-3 text-sm" colspan="2">
                                                <?php if ($borrow['tanggal_kembali'] == null) : ?>
                                                    <div class="bg-red-200 text-red-700 rounded-md p-1.5 text-center border border-red-600 text-xs">
                                                        <p>Belum Kembali</p>
                                                    </div>
                                                <?php elseif ($borrow['tanggal_kembali'] !== null && $borrow['denda'] > 0) : ?>
                                                    <button data-tooltip-target="tooltip-default-<?= $borrow['peminjaman'] ?>" type="button" class="bg-yellow-200 text-yellow-700 rounded-md p-1.5 text-center border border-yellow-600 text-xs w-full">Terlambat</button>
                                                    <div id="tooltip-default-<?= $borrow['peminjaman'] ?>" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                        Denda: Rp<?= $borrow['denda'] ?>
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                <?php elseif ($borrow['tanggal_kembali'] !== null) : ?>
                                                    <div class="bg-green-200 text-green-700 rounded-md p-1.5 text-center border border-green-600 text-xs">
                                                        <p>Sudah Kembali</p>
                                                    </div>
                                                <?php endif ?>
                                            </td>
                                            <td class="px-6 py-4 flex justify-center items-center">
                                                <div class="flex items-center">
                                                    <?php if ($borrow['tanggal_kembali'] === null) : ?>
                                                        <label for="pengembalian<?= $borrow["peminjaman"] ?>" data-modal-target="pengembalian<?= $borrow["peminjaman"] ?>-modal" data-modal-toggle="pengembalian<?= $borrow["peminjaman"] ?>-modal" class="cursor-pointer text-sm">
                                                            Kembalikan
                                                        </label>
                                                        <p class="mx-2 text-blue-600">|</p>
                                                    <?php endif ?>
                                                    <label for="detail<?= $borrow["peminjaman"] ?>" data-modal-target="detail<?= $borrow["peminjaman"] ?>-modal" data-modal-toggle="detail<?= $borrow["peminjaman"] ?>-modal" class="cursor-pointer text-sm">
                                                        Detail
                                                    </label>
                                                </div>
                                                <input type="checkbox" id="pengembalian<?= $borrow["peminjaman"] ?>" class="modal-toggle" />
                                                <label for="pengembalian<?= $borrow["peminjaman"] ?>" class="modal cursor-pointer">
                                                    <label class="modal-box relative bg-white w-[27rem] p-8" for="">
                                                        <label for="pengembalian<?= $borrow["peminjaman"] ?>" class="btn btn-sm btn-circle absolute right-4 top-4 bg-[#e1e1e1] text-[#6B7280] border-0 hover:text-[#6B7280] hover:bg-[#e1e1e1]"><i class='bx bx-x text-2xl'></i></label>
                                                        <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-6 mt-2">Apakah Anda Yakin Ingin Mengembalikan Buku?</h3>
                                                        <div class="flex justify-center gap-x-2">
                                                            <label for="pengembalian<?= $borrow["peminjaman"] ?>" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-md border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 transition duration-300 cursor-pointer">Tidak</label>
                                                            <a href="./controller/pengembalian.php?kode_transaksi=<?= $borrow['peminjaman'] ?>" class="text-white bg-[#2363de] hover:bg-[#0d46b5] focus:ring-4 focus:outline-none focus:ring-[#0d46b5] font-medium rounded-md text-sm inline-flex items-center px-5 py-2.5 text-center mr-2 transition duration-300">
                                                                Ya, saya yakin.
                                                            </a>
                                                        </div>
                                                    </label>
                                                </label>

                                                <input type="checkbox" id="detail<?= $borrow["peminjaman"] ?>" class="modal-toggle" />
                                                <label for="detail<?= $borrow["peminjaman"] ?>" class="modal cursor-pointer">
                                                    <label class="modal-box relative bg-white w-[27rem] p-8" for="">
                                                        <label for="detail<?= $borrow["peminjaman"] ?>" class="btn btn-sm btn-circle absolute right-4 top-4 bg-[#e1e1e1] text-[#6B7280] border-0 hover:text-[#6B7280] hover:bg-[#e1e1e1]"><i class='bx bx-x text-2xl'></i></label>
                                                        <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-6 mt-2">Detail Peminjaman #<?= $borrow['peminjaman'] ?></h3>
                                                        <div>
                                                            <div class="mb-4">
                                                                <p class="text-sm text-gray-900">Nama Peminjam</p>
                                                                <p><?= $borrow['peminjam'] ?></p>
                                                            </div>
                                                            <div class="mb-4">
                                                                <p class="text-sm text-gray-900">Nama Petugas</p>
                                                                <p><?= $borrow['petugas'] ?></p>
                                                            </div>
                                                            <div class="mb-4">
                                                                <p class="text-sm text-gray-900">Buku yang Dipinjam:</p>
                                                                <?php
                                                                $transaction = $borrow['peminjaman'];
                                                                $judul_query = pg_query($db, "SELECT * FROM detail_transaksi d JOIN buku b ON d.kode_buku = b.kode_buku WHERE kode_transaksi = '$transaction'");
                                                                $juduls = pg_fetch_all($judul_query);
                                                                ?>
                                                                <ol class="pl-5 mt-1 space-y-1 list-decimal">
                                                                    <?php foreach ($juduls as $judul) : ?>
                                                                        <li><?= $judul['judul_buku'] ?></li>
                                                                    <?php endforeach ?>
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="checkbox" id="peminjaman" class="modal-toggle" />
    <label for="peminjaman" class="modal cursor-pointer">
        <label class="modal-box relative bg-white w-[27rem] p-8" for="">
            <label for="peminjaman" class="btn btn-sm btn-circle absolute right-4 top-4 bg-[#e1e1e1] text-[#6B7280] border-0 hover:text-[#6B7280] hover:bg-[#e1e1e1]"><i class='bx bx-x text-2xl'></i></label>
            <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-6 mt-2">Form Peminjaman</h3>
            <form class="space-y-3" action="./controller/peminjaman.php" method="POST">
                <div>
                    <label for="peminjam" class="block text-sm font-medium text-gray-900 mb-2">Peminjam</label>
                    <select id="peminjam" name="peminjam" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full p-2.5" required>
                        <option value="" selected>Peminjam</option>
                        <?php foreach ($members as $member) : ?>
                            <option value="<?= $member['kode_anggota'] ?>"><?= $member['nama'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label for="petugas" class="block text-sm font-medium text-gray-900 mb-2">Petugas</label>
                    <select id="petugas" name="petugas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full p-2.5" required>
                        <option value="" selected>Petugas</option>
                        <?php foreach ($librarians as $librarian) : ?>
                            <option value="<?= $librarian['kode_petugas'] ?>"> <?= $librarian['nama'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label for="jumlah_buku" class="block text-sm font-medium text-gray-900 mb-2">Jumlah Buku yang Dipinjam</label>
                    <input id="jumlah_buku" name="jumlah_buku" type="number" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full p-2.5" placeholder="Jumlah Buku" required>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="buku" class="block text-sm font-medium text-gray-900">Buku yang dipinjam (Maksimal 3 buku)</label>
                        <button type="button" id="tambah_buku" class="text-blue-500 hover:bg-blue-600 hover:text-white text-md font-medium rounded-lg ml-6 w-6 h-6 transition duration-300"><i class='bx bx-plus'></i></button>
                    </div>

                    <select id="buku" name="buku[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full p-2.5" required>
                        <option value="" selected>Pilih Buku</option>
                        <?php foreach ($books as $book) : ?>
                            <option value="<?= $book['kode_buku'] ?>"> <?= $book['judul_buku'] ?> (<?= $book['penulis'] ?>)</option>
                        <?php endforeach ?>
                    </select>
                    <div id="daftar_buku" class="mt-2.5"></div>
                </div>

                <div>
                    <label for="jumlah_buku" class="block text-sm font-medium text-gray-900 mb-2">Tanggal Pinjam</label>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input datepicker datepicker-autohide type="text" id="start" name="start" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full pl-10 p-2.5" placeholder="MM/DD/YYYY" autocomplete="off">
                    </div>
                </div>
                <button type="submit" name="pinjam" class="w-full inline-block bg-[#2363DE] text-white px-4 py-2 rounded mt-4">Tambah</button>
            </form>
            </form>
        </label>
    </label>

    <script src="./js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script>
        const tambahBukuBtn = document.getElementById('tambah_buku');
        const daftarBukuContainer = document.getElementById('daftar_buku');

        tambahBukuBtn.addEventListener('click', function() {
            let i = 0;
            const bukuInput = document.createElement('div');
            bukuInput.className += "flex gap-x-2 mb-2.5";
            bukuInput.innerHTML = `
            <select id="buku" name="buku[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full p-2.5" required>
                <option value="" selected>Pilih Buku</option>
                <?php foreach ($books as $book) : ?>
                    <option value="<?= $book['kode_buku'] ?>"> <?= $book['judul_buku'] ?> (<?= $book['penulis'] ?>)</option>
                <?php endforeach ?>
            </select>
            <button type="button" class="hapus_buku bg-red-500 px-3 py-2 rounded-md"><i class='bx bxs-trash text-white'></i></button>
            `;
            daftarBukuContainer.appendChild(bukuInput);

            const hapusBukuBtns = document.getElementsByClassName('hapus_buku');
            for (let i = 0; i < hapusBukuBtns.length; i++) {
                hapusBukuBtns[i].addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah perilaku default tombol submit
                    event.target.parentElement.remove();
                });
            }
        });
    </script>
    </script>
</body>

</html>