<?php
session_start();
include "koneksi.php";  // Pastikan koneksi ke database sudah benar

// Cek jika belum login, arahkan ke login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data nasabah berdasarkan session username
$username = $_SESSION['username'];
$sql = "SELECT * FROM nasabah WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$nasabah = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Bank Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
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
            opacity: 0.2;
            filter: blur(8px);
        }

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .map-container:hover {
            transform: scale(1.03);
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        /* Button hover effect */
        .btn:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        /* Animation fadeIn */
        .fadeIn {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer Styles */
        .footer {
            background-color: #2d6a4f;
            padding: 20px 0;
            margin-top: 40px;
        }

        .footer a {
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
        }

        .footer a:hover {
            color: #80e0a7;
            transition: all 0.3s ease;
        }

        .footer p {
            font-size: 0.875rem;
            color: #d1d1d1;
        }

        .footer .social-icons i {
            font-size: 1.5rem;
        }

        .footer .social-icons a {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <!-- Background Image -->
    <img alt="Background image" class="background-image" height="1080" src="https://storage.googleapis.com/a1aa/image/Wq1yQqz0iQZGIF1UbE1cseGYnYewCoYPC378GCfTMV6PKKunA.jpg" width="1920"/>

    <!-- Navigation Bar -->
    <nav class="bg-green-700 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-lg font-bold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-4">
                <a href="laporan.php" class="text-white hover:text-green-300 transition duration-300">Laporan</a>
                <a href="logout.php" class="text-white hover:text-green-300 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-20 p-8 bg-white bg-opacity-80 rounded-lg shadow-xl max-w-4xl fadeIn">
        <h1 class="text-4xl font-bold text-center text-green-700 mb-6">Welcome, <?php echo $nasabah['nama']; ?>!</h1>
        <p class="text-lg text-gray-700 mb-8 leading-relaxed text-center">
            Selamat datang di sistem Bank Sampah Unit Sayang Rennu. Di sini Anda bisa melihat laporan transaksi Anda dan banyak lagi.
        </p>
        
        <!-- Action Buttons -->
        <div class="flex justify-center space-x-6 mb-8">
            <a href="laporan.php" class="btn w-1/3 py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-800 transition duration-300 text-center">
                Laporan
            </a>
        </div>

        <!-- Google Maps -->
        <div class="map-container mb-8">
            <!-- Google Maps Embed -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.77280455186!2d119.4411112!3d-5.140242!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd290e39b87d%3A0xe68edba9190d6442!2sSayang%20Rennu!5e0!3m2!1sen!2sid!4v1733406959857!5m2!1sen!2sid"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>

    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container mx-auto text-center text-white">
            <p class="text-sm">&copy; 2024 Bank Sampah Unit Sayang Rennu. All rights reserved.</p>
            <div class="mt-4 social-icons">
                <a href="https://facebook.com" target="_blank" class="text-white mx-2 hover:text-green-300">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="text-white mx-2 hover:text-green-300">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="text-white mx-2 hover:text-green-300">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
