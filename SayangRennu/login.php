<?php
session_start();
include "koneksi.php";

// Jika sudah login, arahkan ke halaman sesuai role
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'pengelola') {
        header("Location: index1.php");
    } else {
        header("Location: index_nasabah.php");
    }
    exit();
}

$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Proses login pengelola
    if ($role == 'pengelola') {
        $sql = "SELECT * FROM pengelola WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Perbandingan password langsung (tidak menggunakan hash)
            if ($row['password'] === $password) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = 'pengelola';
                header("Location: index1.php");
                exit();
            } else {
                $error = "Password salah untuk pengelola!";
            }
        } else {
            $error = "Username pengelola tidak ditemukan!";
        }
        mysqli_stmt_close($stmt);

    // Proses login nasabah
    } elseif ($role == 'nasabah') {
        $sql = "SELECT * FROM nasabah WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verifikasi password dengan hashing
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = 'nasabah';
                header("Location: index_nasabah.php");
                exit();
            } else {
                $error = "Password salah untuk nasabah!";
            }
        } else {
            $error = "Username nasabah tidak ditemukan!";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Role tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bank Sampah</title>
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
            opacity: 0.4;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error {
            color: red;
            font-size: 0.875rem;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Background Image -->
    <img alt="A detailed image of a large pile of mixed waste including plastic, paper, and organic materials"
        class="background-image" height="1080" src="https://storage.googleapis.com/a1aa/image/wEEPUhph0s7ONpNOTHiI37eqb4pvVSXPsbOh3W1Hdn7iii7JA.jpg" width="1920" />

    <div class="login-container">
        <div class="login-form bg-white bg-opacity-80 rounded-lg shadow-lg p-8 max-w-md w-full">
            <h1 class="text-3xl font-semibold text-center text-green-700 mb-6">Login</h1>

            <!-- Login Form -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-6">
                    <label for="username" class="block text-lg font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Username" required />
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Password" required />
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <label for="role" class="block text-lg font-medium text-gray-700">Login Sebagai</label>
                    <select name="role" id="role" class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="pengelola">Pengelola</option>
                        <option value="nasabah">Nasabah</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-300">
                    Login
                </button>
            </form>

            <!-- Error Message -->
            <?php if (!empty($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
        </div>
    </div>
</body>
</html>