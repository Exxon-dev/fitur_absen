-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 17 Jul 2025 pada 03.49
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
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absen`
--

INSERT INTO `absen` (`id_absen`, `id_siswa`, `jam_masuk`, `jam_keluar`, `tanggal`, `keterangan`) VALUES
(81, 1, '08:07:24', NULL, '2025-07-14', 'Hadir'),
(82, 2, '08:16:02', NULL, '2025-07-14', 'Hadir'),
(84, 1, '14:15:28', NULL, '2025-07-16', 'Hadir'),
(85, 6899, '08:20:49', NULL, '2025-07-17', 'Hadir');

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan`
--

CREATE TABLE `catatan` (
  `id_catatan` int(11) NOT NULL,
  `id_pembimbing` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `id_sekolah` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `id_sekolah`, `username`, `password`) VALUES
(1, 'Amin Wahyudi', 1, 'amin', 'a'),
(2, 'Imam R Kurniawan', 0, 'imam', 'i'),
(3, 'Sidik Waloyo', 2, 'sidik', 's'),
(4, 'Rina Setiawati', 0, 'rina', 'r'),
(6, 'Dewi Anisa', 3, 'dewi', 'd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal`
--

CREATE TABLE `jurnal` (
  `id_jurnal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `id_siswa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurnal`
--

INSERT INTO `jurnal` (`id_jurnal`, `tanggal`, `keterangan`, `id_siswa`) VALUES
(3, '2024-09-22', 'Siswa mengikuti kegiatan pramuka.', 3),
(4, '2024-09-23', 'Siswa tidak hadir karena sakit.', 4),
(6, '2024-09-25', 'Siswa hadir di kelas.', 6),
(7, '2024-09-26', 'Siswa menghadiri seminar.', 7),
(8, '2024-09-27', 'Siswa belajar kelompok.', 8),
(11, '2024-09-30', 'Siswa berpartisipasi dalam lomba.', 1),
(12, '2024-09-30', 'Siswa melakukan tugas kelompok.', 2),
(13, '2024-09-30', 'Siswa mengikuti ujian harian.', 3),
(14, '2024-09-30', 'Siswa hadir dengan izin.', 4),
(15, '2024-09-30', 'Siswa tidak masuk karena sakit.', 5),
(16, '2024-09-30', 'Siswa membantu kegiatan sekolah.', 6),
(17, '2024-09-30', 'Siswa mengikuti diskusi kelas.', 7),
(18, '2024-09-30', 'Siswa memberikan presentasi.', 8),
(19, '2024-09-30', 'Siswa berlatih untuk ujian.', 9),
(20, '2024-09-30', 'Siswa mengerjakan proyek.', 10),
(21, '2024-09-30', 'Siswa mengikuti seminar.', 1),
(22, '2024-09-30', 'Siswa absen dengan izin.', 2),
(23, '2024-09-30', 'Siswa menyiapkan bahan ajar.', 3),
(24, '2024-09-30', 'Siswa menghadiri pertemuan orang tua.', 4),
(25, '2024-09-30', 'Siswa mengikuti kunjungan industri.', 5),
(26, '2024-09-30', 'Siswa melakukan kegiatan sosial.', 6),
(27, '2024-09-30', 'Siswa belajar kelompok.', 7),
(28, '2024-09-30', 'Siswa tidak hadir karena alasan pribadi.', 8),
(29, '2024-09-30', 'Siswa berpartisipasi dalam pameran.', 9),
(30, '2024-09-30', 'Siswa mengikuti pelatihan.', 10),
(32, '2024-09-30', 'Siswa mengikuti pertemuan organisasi.', 2),
(33, '2024-09-30', 'Siswa berkontribusi dalam proyek kelas.', 3),
(34, '2024-09-30', 'Siswa mendapatkan bimbingan.', 4),
(35, '2024-09-30', 'Siswa berpartisipasi dalam diskusi panel.', 5),
(36, '2024-09-30', 'Siswa menghadiri acara sekolah.', 6),
(37, '2024-09-30', 'Siswa tidak masuk karena urusan keluarga.', 7),
(38, '2024-09-30', 'Siswa membantu di perpustakaan.', 8),
(39, '2024-09-30', 'Siswa mengikuti kelas tambahan.', 9),
(40, '2024-09-30', 'Siswa melakukan tugas individu.', 10),
(41, '2024-09-30', 'Siswa berpartisipasi dalam olahraga.', 1),
(42, '2024-09-30', 'Siswa melakukan kegiatan seni.', 2),
(43, '2024-09-30', 'Siswa tidak hadir karena sakit.', 3),
(44, '2024-09-30', 'Siswa belajar mandiri.', 4),
(45, '2024-09-30', 'Siswa mengikuti acara spesial.', 5),
(46, '2024-09-30', 'Siswa mengerjakan laporan.', 6),
(47, '2024-09-30', 'Siswa berbagi pengalaman belajar.', 7),
(48, '2024-09-30', 'Siswa menghadiri kursus.', 8),
(49, '2024-10-15', 'Siswa tidak hadir karena alasan kesehatan.', 1),
(50, '2024-10-15', 'Siswa membantu dalam kegiatan komunitas.', 2),
(51, '2024-10-15', 'Siswa berpartisipasi dalam pameran seni.', 3),
(52, '2024-10-15', 'Siswa mengerjakan proyek kelompok.', 4),
(53, '2024-10-15', 'Siswa mengikuti kelas tambahan.', 5),
(54, '2024-10-15', 'Siswa menyusun laporan tugas.', 6),
(55, '2024-10-15', 'Siswa berkontribusi dalam proyek komunitas.', 7),
(56, '2024-10-15', 'Siswa berpartisipasi dalam kegiatan olahraga.', 8),
(57, '2024-10-15', 'Siswa membantu di perpustakaan sekolah.', 9),
(58, '2024-10-15', 'Siswa mengikuti kelas persiapan ujian.', 10),
(60, '2024-10-16', 'halooo apa kabar', 2),
(61, '2024-10-21', 'halooo semua', 1),
(62, '2024-10-22', 'tidak ada kegiatan di jam 8', 10),
(63, '2024-10-23', 'seperti biasanya', 7),
(64, '2025-07-04', 'UNQUE ', 6867),
(65, '2025-07-05', 'oi\r\n', 6867),
(67, '2025-07-06', 'oiii', 6867),
(69, '2025-07-08', 'oi', 6867),
(70, '2025-07-10', 'hallo', 6867),
(71, '2025-07-10', 'halo', 1),
(72, '2025-07-11', 'hai', 1),
(73, '2025-07-11', 'kjhbv', 6896),
(75, '2025-07-16', 'sdfghj', 1),
(76, '2025-07-17', 'hallo', 6899);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `nama_laporan` varchar(100) NOT NULL,
  `file` int(11) NOT NULL,
  `urut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembimbing`
--

CREATE TABLE `pembimbing` (
  `id_pembimbing` int(11) NOT NULL,
  `nama_pembimbing` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembimbing`
--

INSERT INTO `pembimbing` (`id_pembimbing`, `nama_pembimbing`, `username`, `password`) VALUES
(1, 'Maulida Nur Masruroh', 'ida', 'i'),
(2, 'Bu Lis', 'lis', 'lis123'),
(3, 'Fajar Hidayat', 'fajar', 'f'),
(4, 'Siti Fatimah', 'siti', 's');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_notifikasi`
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
-- Struktur dari tabel `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `nama_perusahaan` varchar(50) NOT NULL,
  `alamat_perusahaan` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `alamat_perusahaan`) VALUES
(1, 'PT Asta Brata Teknologo', 'Banyurip,Tegalrejo,Magelang'),
(3, 'PT Sejahtera Abadi', 'Jl. Raya No. 123, Yogyakarta'),
(4, 'CV Maju Jaya', 'Jl. Merdeka No. 456, Semarang'),
(5, 'PT Teknologi Wih Canggih', 'Jl. Canggih No. 789, Surakarta');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sekolah`
--

CREATE TABLE `sekolah` (
  `id_sekolah` int(11) NOT NULL,
  `nama_sekolah` varchar(50) NOT NULL,
  `alamat_sekolah` varchar(300) NOT NULL,
  `kepala_sekolah` varchar(25) NOT NULL,
  `logo_sekolah` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sekolah`
--

INSERT INTO `sekolah` (`id_sekolah`, `nama_sekolah`, `alamat_sekolah`, `kepala_sekolah`, `logo_sekolah`) VALUES
(1, 'SMK N Tembarak', 'Greges,Tembarak,Temanggung', 'Aster Aswiny,S.Pd,M.Pd', 'logo_smkntbk.png'),
(2, 'SMK N 2 Temanggung', 'Jalan Kartini, Temanggung, Kabupaten Temanggung', 'Drs. Suharna', 'logo_smkn2.png'),
(3, 'SMA N 1 Magelang', 'Jl. Pahlawan No. 10, Magelang', 'Dr. H. Joko Widodo', 'logo_sman1.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nisn` varchar(15) NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `pro_keahlian` varchar(20) NOT NULL,
  `TTL` varchar(100) NOT NULL,
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
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nisn`, `nama_siswa`, `no_wa`, `kelas`, `pro_keahlian`, `TTL`, `id_sekolah`, `id_perusahaan`, `tanggal_mulai`, `tanggal_selesai`, `id_pembimbing`, `id_guru`, `username`, `password`) VALUES
(1, '23101106', 'ekoo', '6285799788258', '12 RPL A', 'Perangkat Lunak', 'Magelang 25 November 2008', 3, 1, '2025-07-10', '2025-07-10', 1, 1, 'eko', 'eko');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`Id`, `username`, `password`, `nama`, `level`) VALUES
(1, 'admin', 'admin', 'frida', 'admin'),
(2, 'siswa', 'siswa', 'dian', 'siswa'),
(3, 'guru', 'guru', 'amin', 'guru'),
(22, 'guru2', 'guru2', 'pembimbing', 'pembimbing');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indeks untuk tabel `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id_catatan`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id_jurnal`);

--
-- Indeks untuk tabel `pembimbing`
--
ALTER TABLE `pembimbing`
  ADD PRIMARY KEY (`id_pembimbing`);

--
-- Indeks untuk tabel `pengaturan_notifikasi`
--
ALTER TABLE `pengaturan_notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indeks untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT untuk tabel `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id_catatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=987654322;

--
-- AUTO_INCREMENT untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT untuk tabel `pembimbing`
--
ALTER TABLE `pembimbing`
  MODIFY `id_pembimbing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengaturan_notifikasi`
--
ALTER TABLE `pengaturan_notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6900;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
