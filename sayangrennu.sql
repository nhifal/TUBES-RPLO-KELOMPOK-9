-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 21 Des 2024 pada 05.30
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sayangrennu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `nasabah_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nomorIdentitas` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `nomorTelepon` varchar(20) NOT NULL,
  `saldo` double NOT NULL DEFAULT 0,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`nasabah_id`, `nama`, `nomorIdentitas`, `alamat`, `nomorTelepon`, `saldo`, `username`, `password`) VALUES
(8, 'Nifall', '73090', 'Jl. Sunu', '08928712791', 8000, 'Nifal', '$2y$10$6kCa34oQx.wHp0QJsEBF6.V7UMuv3aICZNtuQRzBzvnByS5/..cfy'),
(9, 'Nifal', '12345', 'jl.Sunu', '081', 10000, 'nifall', '$2y$10$wUO7m9lKDMHpWZl9/jZuPuDdp8wKBNsODGfvWaTyqfPtJMD/ES2em');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengelola`
--

CREATE TABLE `pengelola` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengelola`
--

INSERT INTO `pengelola` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Nifal', 'nifal', '123'),
(2, 'Anugerah Zulkarnain', 'anugerah', '1234');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sampah`
--

CREATE TABLE `sampah` (
  `idSampah` int(11) NOT NULL,
  `jenisSampah` varchar(50) NOT NULL,
  `hargaPerKg` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sampah`
--

INSERT INTO `sampah` (`idSampah`, `jenisSampah`, `hargaPerKg`) VALUES
(1, 'Plastik', 2000),
(2, 'Minyak', 5000),
(3, 'Kertas', 1000),
(4, 'Besi', 7000),
(5, 'Aluminium', 11000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `idTransaksi` int(11) NOT NULL,
  `nasabah_id` int(11) DEFAULT NULL,
  `jenisSampah` varchar(255) DEFAULT NULL,
  `berat` double DEFAULT NULL,
  `hargaPerKg` double DEFAULT NULL,
  `totalNilai` double DEFAULT NULL,
  `idSampah` int(11) DEFAULT NULL,
  `tanggalTransaksi` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`idTransaksi`, `nasabah_id`, `jenisSampah`, `berat`, `hargaPerKg`, `totalNilai`, `idSampah`, `tanggalTransaksi`) VALUES
(1, 8, 'Plastik', 8, 1000, 8000, NULL, '2024-12-06'),
(2, 9, 'Minyak', 2, 5000, 10000, NULL, '2024-12-14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`nasabah_id`),
  ADD UNIQUE KEY `nomorIdentitas` (`nomorIdentitas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`idSampah`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`idTransaksi`),
  ADD KEY `nasabah_id` (`nasabah_id`),
  ADD KEY `fk_sampah` (`idSampah`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `nasabah_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sampah`
--
ALTER TABLE `sampah`
  MODIFY `idSampah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `idTransaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_sampah` FOREIGN KEY (`idSampah`) REFERENCES `sampah` (`idSampah`),
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`nasabah_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
