<?php
session_start();
include "koneksi.php";  // Pastikan file koneksi.php sudah ada

$error = "";
$success = "";  // Variabel untuk menyimpan pesan sukses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nomorIdentitas = $_POST['nomorIdentitas'];
    $alamat = $_POST['alamat'];
    $nomorTelepon = $_POST['nomorTelepon'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Menggunakan password_hash untuk keamanan

    // Memeriksa apakah username atau nomor identitas sudah ada di database
    $sqlCheck = "SELECT * FROM nasabah WHERE username = ? OR nomorIdentitas = ?";
    $stmtCheck = mysqli_prepare($koneksi, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $username, $nomorIdentitas);
    mysqli_stmt_execute($stmtCheck);
    $result = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username atau Nomor Identitas sudah terdaftar!";
    } else {
        // Menyimpan data nasabah ke database
        $sql = "INSERT INTO nasabah (nama, nomorIdentitas, alamat, nomorTelepon, username, password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $nama, $nomorIdentitas, $alamat, $nomorTelepon, $username, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Pendaftaran berhasil! Silakan login.";  // Menambahkan pesan sukses
            header("refresh:3;url=login.php");  // Redirect ke halaman login setelah 3 detik
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi!";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($stmtCheck);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Nasabah - Bank Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <img alt="Background image" class="background-image" height="1080" src="https://storage.googleapis.com/a1aa/image/Wq1yQqz0iQZGIF1UbE1cseGYnYewCoYPC378GCfTMV6PKKunA.jpg" width="1920"/>

    <nav class="bg-green-700 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <div class="space-x-6">
            <!-- Home Link -->
            <a href="index1.php" class="text-white text-lg font-semibold hover:bg-green-800 hover:text-gray-100 px-4 py-2 rounded-lg transition duration-300 ease-in-out">
                Home
            </a>
            <!-- Logout Link -->
            <a href="logout.php" class="text-white text-lg font-semibold hover:bg-red-700 hover:text-gray-100 px-4 py-2 rounded-lg transition duration-300 ease-in-out">
                Logout
            </a>
        </div>
    </div>
</nav>


    <!-- Form Section -->
    <div class="container mx-auto mt-10 p-6 bg-white bg-opacity-80 rounded-lg shadow-lg max-w-lg">
        <h1 class="text-3xl font-bold text-center text-green-700 mb-6">Pendaftaran Nasabah</h1>

        <!-- Form Pendaftaran -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-4">
                <label for="nama" class="block text-lg font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Nama lengkap" required>
            </div>

            <div class="mb-4">
                <label for="nomorIdentitas" class="block text-lg font-medium text-gray-700">Nomor Identitas</label>
                <input type="text" name="nomorIdentitas" id="nomorIdentitas" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Nomor KTP" required>
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-lg font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" id="alamat" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Alamat lengkap" required></textarea>
            </div>

            <div class="mb-4">
                <label for="nomorTelepon" class="block text-lg font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="nomorTelepon" id="nomorTelepon" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Nomor Telepon" required>
            </div>

            <div class="mb-4">
                <label for="username" class="block text-lg font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Username" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Password" required>
            </div>

            <button type="submit" class="w-full py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-800 transition duration-300 focus:outline-none focus:ring-4 focus:ring-green-300">
                Daftar
            </button>
        </form>

        <!-- Menampilkan Pesan Error atau Sukses -->
        <?php if (!empty($error)) { ?>
            <p class="text-red-500 text-center mt-4"><?php echo $error; ?></p>
        <?php } ?>
        <?php if (!empty($success)) { ?>
            <p class="text-green-500 text-center mt-4"><?php echo $success; ?></p>
        <?php } ?>
    </div>
</body>
</html>
