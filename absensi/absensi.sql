-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 31, 2025 at 06:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `koordinat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id_absen`, `id_siswa`, `jam_masuk`, `jam_keluar`, `tanggal`, `keterangan`, `ip_address`, `lokasi`, `koordinat`) VALUES
(167, 1, '14:27:59', '14:28:05', '2025-07-30', 'sakit', NULL, NULL, NULL),
(168, 6916, '14:27:59', '14:28:05', '2025-07-30', 'alpa', NULL, NULL, NULL),
(169, 1, '08:03:37', '08:05:03', '2025-07-31', 'Hadir', '103.105.206.1', 'Suzhou | Jiangsu | CN', '31.3041,120.5954');

-- --------------------------------------------------------

--
-- Table structure for table `catatan`
--

CREATE TABLE `catatan` (
  `id_catatan` int(11) NOT NULL,
  `id_pembimbing` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catatan`
--

INSERT INTO `catatan` (`id_catatan`, `id_pembimbing`, `catatan`, `id_jurnal`, `tanggal`) VALUES
(62, 1, 'lomba apa', 1, '2025-07-18'),
(76, 1, 'hai', 1, '2025-07-18'),
(77, 1, 'hai', 1, '2025-07-18'),
(78, 1, 'haii', 1, '2025-07-18'),
(79, 1, 'hai', 1, '2025-07-18'),
(80, 1, 'haiii', 1, '2025-07-18'),
(81, 1, 'hai', 72, '2025-07-18'),
(83, 1, 'lomba apa?', 79, '2025-07-18'),
(84, 1, 'haii juga', 82, '2025-07-22'),
(89, 1, 'hallo juga', 88, '2025-07-23'),
(90, 1, 'yang bener', 93, '2025-07-24'),
(91, 1, 'hallo', 95, '2025-07-29');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `id_sekolah` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `id_sekolah`, `username`, `password`) VALUES
(1, 'Amin Wahyudi', 3, 'amin', 'a'),
(2, 'Imam R Kurniawan', 0, 'imam', 'i'),
(3, 'Sidik Waloyo', 2, 'sidik', 's'),
(4, 'Rina Setiawati', 1, 'rina', 'r');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `id_jurnal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `id_siswa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurnal`
--

INSERT INTO `jurnal` (`id_jurnal`, `tanggal`, `keterangan`, `id_siswa`) VALUES
(63, '2024-10-23', 'seperti biasanya', 7),
(64, '2025-07-04', 'UNQUE ', 6867),
(65, '2025-07-05', 'oi\r\n', 6867),
(67, '2025-07-06', 'oiii', 6867),
(69, '2025-07-08', 'oi', 6867),
(70, '2025-07-10', 'hallo', 6867),
(71, '2025-07-10', 'hello', 1),
(72, '2025-07-11', 'hai', 1),
(73, '2025-07-11', 'kjhbv', 6896),
(76, '2025-07-17', 'hallo', 6899),
(77, '2025-07-17', 'oi', 6900),
(79, '2025-07-18', 'mengikuti lomba.', 6904),
(80, '2025-07-19', 'hallo pa kabar.', 1),
(81, '2025-07-21', 'hallo', 1),
(82, '2025-07-22', 'haiii', 1),
(91, '2025-07-23', 'hallo juga', 6915),
(93, '2025-07-23', 'Mampelajari git.', 1),
(94, '2025-07-23', 'refisi website absensi', 6914),
(95, '2025-07-29', 'haiiiiiiiii', 1);

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `nama_laporan` varchar(100) NOT NULL,
  `file` int(11) NOT NULL,
  `urut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembimbing`
--

CREATE TABLE `pembimbing` (
  `id_pembimbing` int(11) NOT NULL,
  `nama_pembimbing` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembimbing`
--

INSERT INTO `pembimbing` (`id_pembimbing`, `nama_pembimbing`, `username`, `password`) VALUES
(1, 'Maulida Nur Masruroh', 'ida', 'i'),
(2, 'hah', 'ha', 'h');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_notifikasi`
--

CREATE TABLE `pengaturan_notifikasi` (
  `id` int(11) NOT NULL,
  `jam_telat` time NOT NULL DEFAULT '08:00:00',
  `jam_cek` time NOT NULL DEFAULT '10:00:00',
  `pesan_belum_absen` text NOT NULL,
  `pesan_telat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `nama_perusahaan` varchar(50) NOT NULL,
  `alamat_perusahaan` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `alamat_perusahaan`) VALUES
(1, 'PT Asta Brata Teknologo', 'Banyurip,Tegalrejo,Magelang'),
(3, 'PT Sejahtera Abadi', 'Jl. Raya No. 123, Yogyakarta'),
(5, 'PT Teknologi Wih Canggih', 'Jl. Canggih No. 789, Surakarta');

-- --------------------------------------------------------

--
-- Table structure for table `sekolah`
--

CREATE TABLE `sekolah` (
  `id_sekolah` int(11) NOT NULL,
  `nama_sekolah` varchar(50) NOT NULL,
  `alamat_sekolah` varchar(300) NOT NULL,
  `kepala_sekolah` varchar(25) NOT NULL,
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sekolah`
--

INSERT INTO `sekolah` (`id_sekolah`, `nama_sekolah`, `alamat_sekolah`, `kepala_sekolah`, `logo_sekolah`, `username`, `password`) VALUES
(1, 'SMK N Tembarak', 'Greges,Tembarak,Temanggung', 'Aster Aswiny,S.Pd,M.Pd', 'logo_smkntbk.png', 'tembarak', 'tembarak'),
(2, 'SMK N 2 Temanggung', 'Jalan Kartini, Temanggung, Kabupaten Temanggung', 'Drs. Suharna', 'logo_smkn2.png', 'smk2tmg', 'smk2tmg'),
(3, 'SMA N 1 Magelang', 'Jl. Pahlawan No. 10, Magelang', 'Dr. H. Joko Widodo', 'logo_sman1.png', 'sma1mgl', 'sma1mgl');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` varchar(15) NOT NULL,
  `nisn` varchar(15) NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `pro_keahlian` varchar(50) NOT NULL,
  `TL` text NOT NULL,
  `TTGL` date NOT NULL,
  `id_sekolah` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `id_pembimbing` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nisn`, `nama_siswa`, `no_wa`, `kelas`, `pro_keahlian`, `TL`, `TTGL`, `id_sekolah`, `id_perusahaan`, `tanggal_mulai`, `tanggal_selesai`, `id_pembimbing`, `id_guru`, `username`, `password`) VALUES
(1, '23101106', '0084986208', 'eko', '6285799788258', '12 RPL A', 'Perangkat Lunak', 'Magelang', '2008-11-25', 3, 1, '2025-07-10', '2025-07-10', 1, 1, 'eko', 'eko'),
(6916, '23101107', '0084986208', 'ekooo', '6285799788258', '12 RPL A', 'Perangkat Lunak', 'Magelang 25 November 2008', '0000-00-00', 3, 1, '2025-07-10', '2025-07-10', 1, 1, 'eko1', 'eko1'),
(6917, '23101108', '0084986208', 'ek00', '6285799788258', '12 RPL A', 'Perangkat Lunak', 'Magelang 25 November 2008', '0000-00-00', 3, 1, '2025-07-10', '2025-07-10', 2, 2, 'eko2', 'eko2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `username`, `password`, `nama`, `level`) VALUES
(1, 'admin', 'admin', 'frida', 'admin'),
(2, 'siswa', 'siswa', 'dian', 'siswa'),
(3, 'guru', 'guru', 'amin', 'guru'),
(22, 'guru2', 'guru2', 'pembimbing', 'pembimbing'),
(23, 'sekolah', 'sekolah', 'sekolah', 'sekolah');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id_catatan`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id_jurnal`);

--
-- Indexes for table `pembimbing`
--
ALTER TABLE `pembimbing`
  ADD PRIMARY KEY (`id_pembimbing`);

--
-- Indexes for table `pengaturan_notifikasi`
--
ALTER TABLE `pengaturan_notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indexes for table `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id_catatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=987654322;

--
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `pembimbing`
--
ALTER TABLE `pembimbing`
  MODIFY `id_pembimbing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengaturan_notifikasi`
--
ALTER TABLE `pengaturan_notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6918;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
