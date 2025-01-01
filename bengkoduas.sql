-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 11:44 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkoduas`
--

-- --------------------------------------------------------

--
-- Table structure for table `inputmhs`
--

CREATE TABLE `inputmhs` (
  `id` int(11) NOT NULL,
  `namaMhs` varchar(255) NOT NULL,
  `nim` varchar(15) NOT NULL,
  `ipk` float NOT NULL,
  `sks` int(1) NOT NULL,
  `matakuliah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inputmhs`
--

INSERT INTO `inputmhs` (`id`, `namaMhs`, `nim`, `ipk`, `sks`, `matakuliah`) VALUES
(3, 'Mario', 'A11.2021.13550', 2.9, 20, '-, Algoritma dan Pemrograman, Struktur Data'),
(7, 'fadlil', 'A11.2021.13552', 3, 24, '-, Basis Data, Kecerdasan Buatan, Manajemen Proyek TI');

-- --------------------------------------------------------

--
-- Table structure for table `jwl_matakuliah`
--

CREATE TABLE `jwl_matakuliah` (
  `id` int(11) NOT NULL,
  `matakuliah` varchar(250) NOT NULL,
  `sks` int(1) NOT NULL,
  `kelp` varchar(10) DEFAULT NULL,
  `ruangan` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jwl_matakuliah`
--

INSERT INTO `jwl_matakuliah` (`id`, `matakuliah`, `sks`, `kelp`, `ruangan`) VALUES
(1, 'Algoritma dan Pemrograman', 3, 'A11.4201', 'H6.1'),
(2, 'Struktur Data', 3, 'A11.4202', 'H6.2'),
(3, 'Basis Data', 3, 'A11.4203', 'H6.3'),
(4, 'Sistem Operasi', 3, 'A11.4204', 'H6.4'),
(5, 'Jaringan Komputer', 3, 'A11.4205', 'H6.5'),
(6, 'Pemrograman Web', 3, 'A11.4206', 'H6.6'),
(7, 'Kecerdasan Buatan', 3, 'A11.4207', 'H6.7'),
(8, 'Rekayasa Perangkat Lunak', 3, 'A11.4208', 'H6.8'),
(9, 'Manajemen Proyek TI', 3, 'A11.4209', 'H6.9'),
(10, 'Pemrograman Mobile', 3, 'A11.4210', 'H6.10');

-- --------------------------------------------------------

--
-- Table structure for table `jwl_mhs`
--

CREATE TABLE `jwl_mhs` (
  `id` int(11) NOT NULL,
  `mhs_id` int(11) NOT NULL,
  `matakuliah` varchar(255) NOT NULL,
  `sks` int(11) NOT NULL,
  `kelp` varchar(50) DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jwl_mhs`
--

INSERT INTO `jwl_mhs` (`id`, `mhs_id`, `matakuliah`, `sks`, `kelp`, `ruangan`) VALUES
(9, 3, 'Algoritma dan Pemrograman', 3, 'A11.4201', 'H6.1'),
(10, 3, 'Struktur Data', 3, 'A11.4202', 'H6.2'),
(11, 7, 'Basis Data', 3, 'A11.4203', 'H6.3'),
(12, 7, 'Kecerdasan Buatan', 3, 'A11.4207', 'H6.7'),
(13, 7, 'Manajemen Proyek TI', 3, 'A11.4209', 'H6.9');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inputmhs`
--
ALTER TABLE `inputmhs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwl_matakuliah`
--
ALTER TABLE `jwl_matakuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwl_mhs`
--
ALTER TABLE `jwl_mhs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mhs_id` (`mhs_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inputmhs`
--
ALTER TABLE `inputmhs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jwl_matakuliah`
--
ALTER TABLE `jwl_matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jwl_mhs`
--
ALTER TABLE `jwl_mhs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jwl_mhs`
--
ALTER TABLE `jwl_mhs`
  ADD CONSTRAINT `jwl_mhs_ibfk_1` FOREIGN KEY (`mhs_id`) REFERENCES `inputmhs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
