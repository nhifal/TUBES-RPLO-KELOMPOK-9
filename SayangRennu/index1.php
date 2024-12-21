<?php
session_start();
include "koneksi.php";

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Pengguna";
$sql = "SELECT nama FROM pengelola WHERE username = '$username'";
    $hasil = mysqli_query($koneksi, $sql); 
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

        .map-container-wrapper {
            background-color: #fff; /* Menambahkan latar putih */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 90%; /* Mengatur lebar maksimal */
            transition: all 0.3s ease;
        }

        .map-container-wrapper:hover {
            transform: scale(1.03);
        }

        .map-container {
            height: 300px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
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

        .btn:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

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

        .nav-link:hover {
            transition: color 0.3s ease;
            color: #2d6a4f !important;
        }

        .content-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 5rem;
        }

        .footer {
            padding: 2rem 0;
            background-color: #2d6a4f;
        }
    </style>
</head>

<body>
    <!-- Background Image -->
    <img alt="A detailed image of a trash can overflowing with garbage" class="background-image" height="1080"
        src="https://storage.googleapis.com/a1aa/image/Wq1yQqz0iQZGIF1UbE1cseGYnYewCoYPC378GCfTMV6PKKunA.jpg" width="1920" />

    <!-- Navigation Bar -->
    <nav class="bg-green-700 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-bold">Bank Sampah Unit Sayang Rennu</a>
            <div class="space-x-6 hidden md:flex">
                <a href="daftar_nasabah.php" class="text-white hover:text-green-300 transition duration-300 nav-link">Nasabah</a>
                <a href="transaksi.php" class="text-white hover:text-green-300 transition duration-300 nav-link">Transaksi</a>
                <a href="sampah.php" class="text-white hover:text-green-300 transition duration-300 nav-link">Sampah</a>
                <a href="logout.php" class="text-white hover:text-green-300 transition duration-300 nav-link">Logout</a>
                <img src="user-logo.png" loading="lazy" alt="" class="image" style="height: 30px; width:30px;" />
                <h4 style="color: white; margin:0;">
                <?php
                    while ($data = mysqli_fetch_array($hasil)) { 
                  ?>
                  <p class = "nama"><?php echo $data['nama']; ?> </p>
                  <?php
                  }
                  ?>
                </h4>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto content-container">
        <h1 class="text-4xl font-bold text-center text-green-700 mb-6">Welcome to Bank Sampah Unit Sayang Rennu</h1>
        <p class="text-lg text-gray-700 mb-8 leading-relaxed text-center">
            Sampah adalah segala sesuatu yang tidak lagi dianggap berguna, yang telah dibuang atau tidak memiliki nilai
            bagi pemiliknya. Sampah dapat berupa berbagai jenis material yang berasal dari aktivitas manusia sehari-hari,
            baik itu dari rumah tangga, industri, pertanian, maupun kegiatan komersial lainnya. Sampah yang dihasilkan bisa
            berupa bahan organik maupun anorganik, dan setiap jenis sampah memerlukan penanganan yang berbeda-beda.
        </p>
        <div class="flex justify-center mb-8">
            <img alt="Detailed image of a trash can overflowing with garbage" class="rounded-xl shadow-2xl" height="400"
                src="https://storage.googleapis.com/a1aa/image/Wq1yQqz0iQZGIF1UbE1cseGYnYewCoYPC378GCfTMV6PKKunA.jpg" width="600" />
        </div>
    </div>

    <!-- Google Maps -->
    <div class="map-container-wrapper mb-8">
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.77280455186!2d119.4411112!3d-5.140242!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd290e39b87d%3A0xe68edba9190d6442!2sSayang%20Rennu!5e0!3m2!1sen!2sid!4v1733406959857!5m2!1sen!2sid"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container mx-auto text-center text-white">
            <p class="text-sm">&copy; 2024 Bank Sampah Unit Sayang Rennu. All rights reserved.</p>
            <div class="mt-4">
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
