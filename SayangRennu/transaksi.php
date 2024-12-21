<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi sudah benar

// Cek jika pengguna sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pengelola') {
    header("Location: login.php");
    exit();
}

// Ambil daftar nasabah
$sqlNasabah = "SELECT nasabah_id, nama FROM nasabah";
$resultNasabah = mysqli_query($koneksi, $sqlNasabah);

// Cek apakah query nasabah berhasil
if (!$resultNasabah) {
    die("Query nasabah gagal: " . mysqli_error($koneksi));
}

// Ambil data jenis sampah dan harga per kg dari tabel sampah
$sqlSampah = "SELECT idSampah, jenisSampah, hargaPerKg FROM sampah";
$resultSampah = mysqli_query($koneksi, $sqlSampah);
if (!$resultSampah) {
    die("Query jenis sampah gagal: " . mysqli_error($koneksi));
}

// Proses penambahan transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nasabahId = $_POST['nasabah_id']; // Ambil nasabah_id dari dropdown
    $jenisSampah = $_POST['jenisSampah'];
    $hargaPerKg = $_POST['hargaPerKg'];
    $berat = $_POST['berat'];
    $totalNilai = $berat * $hargaPerKg;
    $tanggalTransaksi = date('Y-m-d'); // Ambil tanggal tanpa jam, menit, detik

    // Masukkan data transaksi ke tabel
    $sql = "INSERT INTO transaksi (nasabah_id, jenisSampah, berat, hargaPerKg, totalNilai, tanggalTransaksi) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "isddds", $nasabahId, $jenisSampah, $berat, $hargaPerKg, $totalNilai, $tanggalTransaksi);
    if (mysqli_stmt_execute($stmt)) {
        // Perbarui saldo nasabah setelah transaksi berhasil
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

        // Redirect setelah transaksi berhasil
        header("Location: transaksi.php");
        exit();
    } else {
        die("Gagal menyimpan transaksi: " . mysqli_error($koneksi));
    }
    mysqli_stmt_close($stmt);
}


// Ambil daftar transaksi dengan join ke tabel nasabah
$sql = "
    SELECT 
        t.idTransaksi, 
        n.nama AS namaNasabah, 
        t.jenisSampah, 
        t.berat, 
        t.hargaPerKg, 
        t.totalNilai,
        t.tanggalTransaksi
    FROM transaksi t
    JOIN nasabah n ON t.nasabah_id = n.nasabah_id
";
$result = mysqli_query($koneksi, $sql);

// Cek apakah query transaksi berhasil
if (!$result) {
    die("Query transaksi gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Nasabah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-roboto">

    <!-- Navbar -->
    <nav class="bg-green-800 p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-semibold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-8">
                <a href="index1.php" class="text-white hover:text-green-400">Home</a>
                <a href="transaksi.php" class="text-white hover:text-green-400">Transaksi</a>
                <a href="sampah.php" class="text-white hover:text-green-200">Kelola Sampah</a>
                <a href="logout.php" class="text-white hover:text-green-400">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-12 p-8 bg-white rounded-lg shadow-2xl">
        <h1 class="text-3xl font-semibold text-center text-green-800 mb-6">Tambah Transaksi Sampah</h1>
        
        <!-- Form Transaksi -->
        <form action="" method="post" class="space-y-8">
            <!-- Pilihan Nasabah -->
            <div>
                <label for="nasabah_id" class="block text-lg font-medium text-gray-700">Pilih Nasabah</label>
                <select name="nasabah_id" id="nasabah_id" class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" required>
                    <option value="">Pilih Nasabah</option>
                    <?php while ($rowNasabah = mysqli_fetch_assoc($resultNasabah)) { ?>
                        <option value="<?php echo $rowNasabah['nasabah_id']; ?>">
                            <?php echo $rowNasabah['nama']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Jenis Sampah -->
            <div>
                <label for="jenisSampah" class="block text-lg font-medium text-gray-700">Jenis Sampah</label>
                <select name="jenisSampah" id="jenisSampah" class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" required>
                    <option value="">Pilih Jenis Sampah</option>
                    <?php while ($rowSampah = mysqli_fetch_assoc($resultSampah)) { ?>
                        <option value="<?php echo $rowSampah['jenisSampah']; ?>" data-harga="<?php echo $rowSampah['hargaPerKg']; ?>">
                            <?php echo $rowSampah['jenisSampah']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Harga per Kg -->
            <div>
                <label for="hargaPerKg" class="block text-lg font-medium text-gray-700">Harga per Kg</label>
                <input type="number" step="0.01" name="hargaPerKg" id="hargaPerKg" class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" readonly required>
            </div>

            <!-- Berat -->
            <div>
                <label for="berat" class="block text-lg font-medium text-gray-700">Berat (Kg)</label>
                <input type="number" step="0.01" name="berat" id="berat" class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-600 transition duration-300">Tambah Transaksi</button>
        </form>
    </div>

    <!-- Daftar Transaksi -->
    <div class="container mx-auto mt-12 p-8 bg-white rounded-lg shadow-2xl">
        <h2 class="text-3xl font-semibold text-center text-green-800 mb-6">Daftar Transaksi Sampah</h2>
        <table class="w-full table-auto border-collapse text-gray-700">
            <thead>
                <tr class="bg-green-100">
                    <th class="px-6 py-3 text-left border-b">ID Transaksi</th>
                    <th class="px-6 py-3 text-left border-b">Nama Nasabah</th>
                    <th class="px-6 py-3 text-left border-b">Jenis Sampah</th>
                    <th class="px-6 py-3 text-left border-b">Berat (Kg)</th>
                    <th class="px-6 py-3 text-left border-b">Harga per Kg</th>
                    <th class="px-6 py-3 text-left border-b">Total Nilai</th>
                    <th class="px-6 py-3 text-left border-b">Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="px-6 py-4 border-b"><?php echo $row['idTransaksi']; ?></td>
                        <td class="px-6 py-4 border-b"><?php echo $row['namaNasabah']; ?></td>
                        <td class="px-6 py-4 border-b"><?php echo $row['jenisSampah']; ?></td>
                        <td class="px-6 py-4 border-b"><?php echo $row['berat']; ?></td>
                        <td class="px-6 py-4 border-b"><?php echo number_format($row['hargaPerKg'], 2, ',', '.'); ?></td>
                        <td class="px-6 py-4 border-b"><?php echo number_format($row['totalNilai'], 2, ',', '.'); ?></td>
                        <td class="px-6 py-4 border-b"><?php echo $row['tanggalTransaksi']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('jenisSampah').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            document.getElementById('hargaPerKg').value = harga;
        });
    </script>

</body>
</html>
