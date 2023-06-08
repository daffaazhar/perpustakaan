<?php
require_once 'connect.php'; // Include the connect.php file to establish the database connection

// Execute a SELECT query
$query = pg_query($db, "SELECT * FROM petugas");
$librarians = pg_fetch_all($query);
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
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./" class="flex items-center text-[#707275] rounded-lg">
                        <i class="text-2xl bx bx-home group-hover:text-black transition duration-200"></i>
                        <span class="text-base font-semibold ml-4 group-hover:text-black transition duration-200">Dashboard</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 left-0 w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg"></span>
                    <a href="./petugas.php" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl text-gray-900 bx bx-user"></i>
                        <span class="text-base text-gray-900 font-semibold ml-4">Petugas</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./anggota.php" class="flex items-center rounded-lg">
                        <i class="text-2xl text-[#707275] bx bx-user"></i>
                        <span class="text-base text-[#707275] font-semibold ml-4">Anggota</span>
                    </a>
                </li>
                <li class="group relative px-6 py-3">
                    <span class="absolute inset-y-0 translate-x-[-28px] w-1 bg-[#2363DE] rounded-tr-lg rounded-br-lg group-hover:translate-x-[-24px] transition-transform"></span>
                    <a href="./buku.php" class="flex items-center text-gray-900 rounded-lg">
                        <i class="text-2xl text-[#707275] bx bx-book"></i>
                        <span class="text-base text-[#707275] font-semibold ml-4">Buku</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="bg-[#f9fafb] p-6 sm:ml-64 h-screen">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-semibold text-black">Data Petugas</h1>
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
                    Tambah Petugas
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-x-6">
            <div class="bg-white rounded-lg shadow-[0_0_0_1px_rgba(0,0,0,0.05)]">
                <div class="overflow-hidden">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b">
                                    <th class="pl-6 pr-4 py-3">Kode Petugas</th>
                                    <th class="pl-6 pr-4 py-3">Nama</th>
                                    <th class="pl-6 pr-4 py-3">Email</th>
                                    <th class="pl-6 pr-4 py-3">No HP</th>
                                    <th class="pl-6 pr-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <?php if (count($librarians) == 0) : ?>
                                    <td colspan="7" class="text-sm text-[#4b5563] pl-6 py-4">
                                        Tidak ada data yang dapat ditampilkan
                                    </td>
                                <?php else : ?>
                                    <?php foreach ($librarians as $librarian) : ?>
                                        <tr class="bg-white border-b">
                                            <td class="pl-6 pr-4 py-3 text-sm text-black">
                                                <?= $librarian['kode_petugas'] ?>
                                            </td>
                                            <td class="pl-6 pr-4 py-3 text-sm text-black">
                                                <?= $librarian['nama'] ?>
                                            </td>
                                            <td class="pl-6 pr-4 py-3 text-sm text-black">
                                                <?= $librarian['email'] ?>
                                            </td>
                                            <td class="pl-6 pr-4 py-3 text-sm text-black">
                                                <?= $librarian['no_hp'] ?>
                                            </td>
                                            <td class="pl-6 pr-4 py-3 text-sm text-black flex items-center justify-center">
                                                <label for="edit<?= $librarian["kode_petugas"] ?>" data-modal-target="edit<?= $librarian["kode_petugas"] ?>-modal" data-modal-toggle="edit<?= $librarian["kode_petugas"] ?>-modal" class="cursor-pointer text-sm text-blue-600">
                                                    Edit
                                                </label>
                                                <p class="mx-2">|</p>
                                                <label for="hapus<?= $librarian["kode_petugas"] ?>" data-modal-target="hapus<?= $librarian["kode_petugas"] ?>-modal" data-modal-toggle="hapus<?= $librarian["kode_petugas"] ?>-modal" class="cursor-pointer text-sm text-red-600">
                                                    Hapus
                                                </label>

                                                <input type="checkbox" id="edit<?= $librarian["kode_petugas"] ?>" class="modal-toggle" />
                                                <label for="edit<?= $librarian["kode_petugas"] ?>" class="modal cursor-pointer">
                                                    <label class="modal-box relative bg-white w-[27rem] p-8" for="">
                                                        <label for="edit<?= $librarian["kode_petugas"] ?>" class="btn btn-sm btn-circle absolute right-4 top-4 bg-[#e1e1e1] text-[#6B7280] border-0 hover:text-[#6B7280] hover:bg-[#e1e1e1]"><i class='bx bx-x text-2xl'></i></label>
                                                        <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-1 mt-2">Form Edit Anggota</h3>
                                                        <p class="text-[#2d333a] mb-6 text-center">Isi kolom untuk mengedit anggota</p>
                                                        <form class="space-y-3" action="./controller/petugas.php" method="POST">
                                                            <input name="kode_petugas" type="text" value="<?= $librarian["kode_petugas"] ?>" hidden>
                                                            <div class="relative mb-3 h-[50px]">
                                                                <input id="nama" class="form__input" type="text" name="nama" autocomplete="off" placeholder=" " value="<?= $librarian["nama"] ?>" required />
                                                                <label class="form__label" for="nama">Nama</label>
                                                            </div>
                                                            <div class="relative mb-3 h-[50px]">
                                                                <input id="email" class="form__input" type="text" name="email" autocomplete="off" placeholder=" " value="<?= $librarian["email"] ?>" required />
                                                                <label class="form__label" for="email">Email</label>
                                                            </div>
                                                            <div class="relative mb-3 h-[50px]">
                                                                <input id="no_hp" class="form__input" type="text" name="no_hp" autocomplete="off" placeholder=" " value="<?= $librarian["no_hp"] ?>" required />
                                                                <label class="form__label" for="no_hp">No HP</label>
                                                            </div>
                                                            <button type="submit" name="edit_petugas" class="w-full inline-block bg-[#2363DE] text-white px-4 py-2 rounded mt-4">Edit</button>
                                                        </form>
                                                    </label>
                                                </label>

                                                <input type="checkbox" id="hapus<?= $librarian["kode_petugas"] ?>" class="modal-toggle" />
                                                <label for="hapus<?= $librarian["kode_petugas"] ?>" class="modal cursor-pointer">
                                                    <label class="modal-box relative bg-white w-[27rem] p-8" for="">
                                                        <label for="hapus<?= $librarian["kode_petugas"] ?>" class="btn btn-sm btn-circle absolute right-4 top-4 bg-[#e1e1e1] text-[#6B7280] border-0 hover:text-[#6B7280] hover:bg-[#e1e1e1]"><i class='bx bx-x text-2xl'></i></label>
                                                        <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-6 mt-2">Apakah Anda Yakin Ingin Menghapus <?= $librarian["nama"] ?>?</h3>
                                                        <div class="flex justify-center gap-x-2">
                                                            <label for="hapus<?= $librarian["kode_petugas"] ?>" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-md border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 transition duration-300 cursor-pointer">Tidak</label>
                                                            <a href="./controller/petugas.php?kode_petugas=<?= $librarian['kode_petugas'] ?>" class="text-white bg-[#2363de] hover:bg-[#0d46b5] focus:ring-4 focus:outline-none focus:ring-[#0d46b5] font-medium rounded-md text-sm inline-flex items-center px-5 py-2.5 text-center mr-2 transition duration-300">
                                                                Ya, saya yakin.
                                                            </a>
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
            <h3 class="font-bold text-2xl text-[#2d333a] text-center mb-1 mt-2">Form Tambah Petugas</h3>
            <p class="text-[#2d333a] mb-6 text-center">Isi kolom untuk membuat kelas baru</p>
            <form class="space-y-3" action="./controller/petugas.php" method="POST">
                <div class="relative mb-3 h-[50px]">
                    <input id="nama" class="form__input" type="text" name="nama" autocomplete="off" placeholder=" " required />
                    <label class="form__label" for="nama">Nama</label>
                </div>
                <div class="relative mb-3 h-[50px]">
                    <input id="email" class="form__input" type="text" name="email" autocomplete="off" placeholder=" " required />
                    <label class="form__label" for="email">Email</label>
                </div>
                <div class="relative mb-3 h-[50px]">
                    <input id="no_hp" class="form__input" type="text" name="no_hp" autocomplete="off" placeholder=" " required />
                    <label class="form__label" for="no_hp">No HP</label>
                </div>
                <button type="submit" name="tambah_petugas" class="w-full inline-block bg-[#2363DE] text-white px-4 py-2 rounded mt-4">Tambah</button>
            </form>
        </label>
    </label>

    <script src="./js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>

</html>