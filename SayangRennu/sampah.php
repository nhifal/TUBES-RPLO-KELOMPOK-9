<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi sudah benar

// Cek jika pengguna sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pengelola') {
    header("Location: login.php");
    exit();
}

// Proses penambahan data sampah
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambahSampah'])) {
    $jenisSampah = $_POST['jenisSampah'];
    $hargaPerKg = $_POST['hargaPerKg'];

    // Query untuk menambah data sampah
    $sql = "INSERT INTO sampah (jenisSampah, hargaPerKg) VALUES (?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "sd", $jenisSampah, $hargaPerKg);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: sampah.php");
        exit();
    } else {
        die("Gagal menambah data sampah: " . mysqli_error($koneksi));
    }
    mysqli_stmt_close($stmt);
}

// Proses update harga sampah
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSampah'])) {
    $idSampah = $_POST['idSampah'];
    $hargaPerKg = $_POST['hargaPerKg'];

    // Query untuk update harga sampah
    $sql = "UPDATE sampah SET hargaPerKg = ? WHERE idSampah = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "di", $hargaPerKg, $idSampah);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: sampah.php");
        exit();
    } else {
        die("Gagal memperbarui harga sampah: " . mysqli_error($koneksi));
    }
    mysqli_stmt_close($stmt);
}

// Ambil data sampah untuk ditampilkan
$sqlSampah = "SELECT * FROM sampah";
$resultSampah = mysqli_query($koneksi, $sqlSampah);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-green-700 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-lg font-bold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-4">
                <a href="index1.php" class="text-white hover:text-green-200">Home</a>
                <a href="transaksi.php" class="text-white hover:text-green-200">Transaksi</a>
                <a href="logout.php" class="text-white hover:text-green-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Form Tambah Sampah -->
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-lg">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">Tambah Data Sampah</h1>
        <form action="" method="post" class="space-y-6">
            <div>
                <label for="jenisSampah" class="block text-lg font-medium text-gray-700">Jenis Sampah</label>
                <input type="text" name="jenisSampah" id="jenisSampah" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label for="hargaPerKg" class="block text-lg font-medium text-gray-700">Harga per Kg</label>
                <input type="number" step="0.01" name="hargaPerKg" id="hargaPerKg" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500" required>
            </div>
            <button type="submit" name="tambahSampah" class="w-full py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-600 transition duration-300">Tambah Sampah</button>
        </form>
    </div>

    <!-- Daftar Sampah -->
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-5xl">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">Daftar Sampah</h1>
        <table class="w-full table-auto border-collapse">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-2 border text-left">ID Sampah</th>
                    <th class="px-4 py-2 border text-left">Jenis Sampah</th>
                    <th class="px-4 py-2 border text-left">Harga per Kg</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <?php while ($rowSampah = mysqli_fetch_assoc($resultSampah)) { ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><?php echo $rowSampah['idSampah']; ?></td>
                        <td class="px-4 py-2"><?php echo $rowSampah['jenisSampah']; ?></td>
                        <td class="px-4 py-2"><?php echo number_format($rowSampah['hargaPerKg'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-2 text-center">
                            <!-- Form Update Harga Sampah -->
                            <form action="" method="post" class="inline">
                                <input type="hidden" name="idSampah" value="<?php echo $rowSampah['idSampah']; ?>">
                                <input type="number" step="0.01" name="hargaPerKg" value="<?php echo $rowSampah['hargaPerKg']; ?>" class="px-4 py-2 border rounded-lg shadow-sm" required>
                                <button type="submit" name="updateSampah" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-400 transition duration-300">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
