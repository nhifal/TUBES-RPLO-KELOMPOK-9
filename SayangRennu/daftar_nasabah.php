<?php
session_start();
include "koneksi.php";  // Pastikan koneksi ke database sudah benar

// Cek jika belum login, arahkan ke login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Query untuk memperbarui saldo di tabel nasabah
$updateSaldoQuery = "
    UPDATE nasabah n
    LEFT JOIN (
        SELECT nasabah_id, COALESCE(SUM(totalNilai), 0) AS totalSaldo
        FROM transaksi
        GROUP BY nasabah_id
    ) t ON n.nasabah_id = t.nasabah_id
    SET n.saldo = COALESCE(t.totalSaldo, 0);
";

if (!mysqli_query($koneksi, $updateSaldoQuery)) {
    die("Gagal memperbarui saldo: " . mysqli_error($koneksi));
}

// Ambil data nasabah untuk ditampilkan
$sql = "SELECT * FROM nasabah";
$result = mysqli_query($koneksi, $sql);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nasabah - Bank Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="bg-green-700 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-bold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-6">
                <a href="index1.php" class="text-white hover:text-green-300 transition duration-300">Home</a>
                <a href="daftar.php" class="text-white hover:text-green-300 transition duration-300">Daftar Nasabah</a>
                <a href="logout.php" class="text-white hover:text-green-300 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Daftar Nasabah -->
    <div class="container mx-auto mt-10 p-8 bg-white rounded-xl shadow-lg">
        <h1 class="text-4xl font-semibold text-center text-green-700 mb-6">Daftar Nasabah</h1>

        <table class="min-w-full table-auto border-collapse bg-white rounded-lg shadow-sm">
            <thead>
                <tr class="bg-green-700 text-white">
                    <th class="px-4 py-2 border-b text-left">Nama</th>
                    <th class="px-4 py-2 border-b text-left">Nomor Identitas</th>
                    <th class="px-4 py-2 border-b text-left">Alamat</th>
                    <th class="px-4 py-2 border-b text-left">Nomor Telepon</th>
                    <th class="px-4 py-2 border-b text-left">Saldo</th>
                    <th class="px-4 py-2 border-b text-left">Username</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="px-4 py-2 border-b"><?php echo $row['nama']; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $row['nomorIdentitas']; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $row['alamat']; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $row['nomorTelepon']; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo number_format($row['saldo'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $row['username']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="bg-green-700 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Bank Sampah Unit Sayang Rennu. All rights reserved.</p>
            <div class="mt-2">
                <a href="https://facebook.com" target="_blank" class="mx-2 hover:text-green-300">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="mx-2 hover:text-green-300">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="mx-2 hover:text-green-300">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
