<?php
session_start();
include "koneksi.php";

// Cek jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil nasabah_id dari sesi login
$username = $_SESSION['username'];

// Ambil data nasabah berdasarkan username
$queryNasabah = "SELECT nasabah_id, nama FROM nasabah WHERE username = '$username'";
$resultNasabah = mysqli_query($koneksi, $queryNasabah);

if (!$resultNasabah) {
    die("Query nasabah gagal: " . mysqli_error($koneksi));
}

$nasabah = mysqli_fetch_assoc($resultNasabah);

if (!$nasabah) {
    die("Data nasabah tidak ditemukan.");
}

$nasabah_id = $nasabah['nasabah_id'];
$nama_nasabah = $nasabah['nama'];

// Hitung total transaksi dan saldo akhir berdasarkan nasabah_id
$queryLaporan = "
    SELECT 
        COALESCE(COUNT(t.idTransaksi), 0) AS totalTransaksi, 
        COALESCE(n.saldo, 0) AS saldoAkhir 
    FROM transaksi t
    LEFT JOIN nasabah n ON t.nasabah_id = n.nasabah_id
    WHERE n.nasabah_id = $nasabah_id
";

$resultLaporan = mysqli_query($koneksi, $queryLaporan);

if (!$resultLaporan) {
    die("Query laporan gagal: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($resultLaporan);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nasabah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="bg-green-700 p-6 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-semibold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-6">
                <a href="index_nasabah.php" class="text-white hover:text-green-300">Home</a>
                <a href="logout.php" class="text-white hover:text-green-300">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-semibold text-center text-gray-800">Laporan Bulanan</h1>
            <h2 class="text-xl text-center mt-4 text-gray-600">Halo, <?php echo htmlspecialchars($nama_nasabah); ?>!</h2>

            <!-- Form Laporan -->
            <form method="post" class="mt-8 space-y-6">

                <a href="createlaporan.php" class="w-full px-6 py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 mt-4 inline-block text-center">
                    Buat Laporan
                </a>
            </form>

            <!-- Display Data -->
            <div class="mt-8 text-lg text-gray-700 space-y-4">
                <p>Total Transaksi: <strong><?php echo $data['totalTransaksi']; ?> kali</strong></p>
                <p>Saldo Akhir: <strong>Rp <?php echo number_format($data['saldoAkhir'], 2, ',', '.'); ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-green-700 text-white text-center py-4 mt-12">
        <p>&copy; 2024 Bank Sampah Unit Sayang Rennu. All rights reserved.</p>
    </footer>

</body>

</html>
