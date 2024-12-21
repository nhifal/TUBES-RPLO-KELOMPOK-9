<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi sudah benar

// Cek jika pengguna sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'nasabah') {
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

// Ambil bulan dan tahun dari parameter GET, jika ada
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // default bulan adalah bulan sekarang
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // default tahun adalah tahun sekarang

// Ambil data transaksi dan saldo berdasarkan nasabah_id dan filter berdasarkan bulan dan tahun
$queryTransaksi = "
    SELECT 
        t.idTransaksi, 
        t.jenisSampah, 
        t.berat, 
        t.hargaPerKg, 
        t.totalNilai, 
        n.saldo,
        DATE_FORMAT(t.tanggalTransaksi, '%d/%m/%Y') AS tanggal
    FROM 
        transaksi t
    JOIN 
        nasabah n ON t.nasabah_id = n.nasabah_id
    WHERE 
        t.nasabah_id = $nasabah_id
        AND MONTH(t.tanggalTransaksi) = $bulan
        AND YEAR(t.tanggalTransaksi) = $tahun
";

$resultTransaksi = mysqli_query($koneksi, $queryTransaksi);

if (!$resultTransaksi) {
    die("Query transaksi gagal: " . mysqli_error($koneksi));
}

// Ambil saldo terakhir nasabah
$querySaldo = "SELECT saldo FROM nasabah WHERE nasabah_id = $nasabah_id";
$resultSaldo = mysqli_query($koneksi, $querySaldo);

if (!$resultSaldo) {
    die("Query saldo gagal: " . mysqli_error($koneksi));
}

$saldoData = mysqli_fetch_assoc($resultSaldo);
$saldoAkhir = $saldoData['saldo'];

// Menambahkan saldo per bulan berdasarkan transaksi
$saldoPerBulan = 0;

while ($row = mysqli_fetch_assoc($resultTransaksi)) {
    $saldoPerBulan += $row['totalNilai'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Nasabah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        .navbar {
            background-color: #28a745;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .table-header {
            background-color: #28a745;
            color: white;
        }
        .table-row:nth-child(even) {
            background-color: #f7fafc;
        }
        .table-row:hover {
            background-color: #e6f4ea;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="navbar p-6 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-semibold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-6">
                <a href="index_nasabah.php" class="text-white hover:text-green-300">Home</a>
                <a href="logout.php" class="text-white hover:text-green-300">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center mb-6">Laporan Transaksi Nasabah</h2>
        
        <div class="mb-4 text-center">
            <p class="text-lg">Nama Nasabah: <strong><?php echo $nama_nasabah; ?></strong></p>
        </div>

        <!-- Formulir Pilihan Bulan dan Tahun -->
        <form action="" method="get" class="mb-6 flex justify-center space-x-4">
            <div class="flex items-center">
                <label for="bulan" class="mr-2 text-gray-700">Bulan</label>
                <select name="bulan" class="border border-gray-300 p-2 rounded">
                    <option value="01" <?php echo $bulan == '01' ? 'selected' : ''; ?>>Januari</option>
                    <option value="02" <?php echo $bulan == '02' ? 'selected' : ''; ?>>Februari</option>
                    <option value="03" <?php echo $bulan == '03' ? 'selected' : ''; ?>>Maret</option>
                    <option value="04" <?php echo $bulan == '04' ? 'selected' : ''; ?>>April</option>
                    <option value="05" <?php echo $bulan == '05' ? 'selected' : ''; ?>>Mei</option>
                    <option value="06" <?php echo $bulan == '06' ? 'selected' : ''; ?>>Juni</option>
                    <option value="07" <?php echo $bulan == '07' ? 'selected' : ''; ?>>Juli</option>
                    <option value="08" <?php echo $bulan == '08' ? 'selected' : ''; ?>>Agustus</option>
                    <option value="09" <?php echo $bulan == '09' ? 'selected' : ''; ?>>September</option>
                    <option value="10" <?php echo $bulan == '10' ? 'selected' : ''; ?>>Oktober</option>
                    <option value="11" <?php echo $bulan == '11' ? 'selected' : ''; ?>>November</option>
                    <option value="12" <?php echo $bulan == '12' ? 'selected' : ''; ?>>Desember</option>
                </select>
            </div>

            <div class="flex items-center">
                <label for="tahun" class="mr-2 text-gray-700">Tahun</label>
                <select name="tahun" class="border border-gray-300 p-2 rounded">
                    <option value="2024" <?php echo $tahun == '2024' ? 'selected' : ''; ?>>2024</option>
                    <option value="2023" <?php echo $tahun == '2023' ? 'selected' : ''; ?>>2023</option>
                    <option value="2022" <?php echo $tahun == '2022' ? 'selected' : ''; ?>>2022</option>
                    <!-- Tambahkan tahun lain sesuai kebutuhan -->
                </select>
            </div>

            <button type="submit" class="bg-green-500 text-white p-2 rounded ml-4 hover:bg-green-600 focus:outline-none">Tampilkan</button>
        </form>

        <!-- Tabel Transaksi Nasabah -->
        <div class="table-container overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="table-header">
                    <tr>
                        <th class="px-4 py-2 border">Jenis Sampah</th>
                        <th class="px-4 py-2 border">Berat (kg)</th>
                        <th class="px-4 py-2 border">Harga per Kg (Rp)</th>
                        <th class="px-4 py-2 border">Total Nilai (Rp)</th>
                        <th class="px-4 py-2 border">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Reset pointer untuk transaksi
                    mysqli_data_seek($resultTransaksi, 0); 
                    while ($row = mysqli_fetch_assoc($resultTransaksi)) : ?>
                        <tr class="table-row">
                            <td class="px-4 py-2 border"><?php echo $row['jenisSampah']; ?></td>
                            <td class="px-4 py-2 border"><?php echo $row['berat']; ?></td>
                            <td class="px-4 py-2 border"><?php echo number_format($row['hargaPerKg'], 2, ',', '.'); ?></td>
                            <td class="px-4 py-2 border"><?php echo number_format($row['totalNilai'], 2, ',', '.'); ?></td>
                            <td class="px-4 py-2 border"><?php echo $row['tanggal']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
<!-- Menampilkan saldo per bulan dan saldo akhir -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">

    <!-- Saldo Per Bulan -->
    <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
        <p class="text-lg font-semibold mb-2">Saldo Per Bulan:</p>
        <p class="text-3xl font-bold"><?php echo "Rp " . number_format($saldoPerBulan, 2, ',', '.'); ?></p>
    </div>

    <!-- Saldo Akhir -->
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
        <p class="text-lg font-semibold mb-2">Saldo Akhir:</p>
        <p class="text-3xl font-bold"><?php echo "Rp " . number_format($saldoAkhir, 2, ',', '.'); ?></p>
    </div>
    
</div>

</body>
</html>
