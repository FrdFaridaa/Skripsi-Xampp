-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Jun 2020 pada 17.04
-- Versi server: 10.4.8-MariaDB
-- Versi PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farida`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `event`
--

CREATE TABLE `event` (
  `idEvent` int(11) NOT NULL,
  `namaEvent` varchar(300) NOT NULL,
  `dateEvent` date NOT NULL,
  `companyEvent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `event`
--

INSERT INTO `event` (`idEvent`, `namaEvent`, `dateEvent`, `companyEvent`) VALUES
(1, 'test', '2020-09-01', 1),
(4, 'test 2', '2020-06-26', 1),
(5, 'test 45', '2020-06-30', 1),
(6, 'test 100', '2020-06-27', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `message`
--

CREATE TABLE `message` (
  `idMessage` int(11) NOT NULL,
  `dariMessage` int(11) NOT NULL,
  `keMessage` int(11) NOT NULL,
  `txtMessage` text NOT NULL,
  `dateMessage` timestamp NOT NULL DEFAULT current_timestamp(),
  `lihatMessage` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `message`
--

INSERT INTO `message` (`idMessage`, `dariMessage`, `keMessage`, `txtMessage`, `dateMessage`, `lihatMessage`) VALUES
(1, 1, 25, 'test', '2020-06-25 14:28:30', 0),
(2, 1, 25, 'test2', '2020-06-25 14:28:51', 0),
(3, 1, 19, 'test', '2020-06-25 14:43:46', 0),
(4, 1, 25, 'test3', '2020-06-25 14:44:15', 0),
(5, 25, 1, 'test3 1', '2020-06-25 14:46:15', 0),
(6, 1, 25, 'test', '2020-06-26 08:38:18', 0),
(7, 1, 2, 'hai', '2020-06-26 08:50:47', 0),
(8, 2, 1, 'hai juga', '2020-06-26 08:54:31', 0),
(9, 1, 25, 'test', '2020-06-26 09:48:47', 0),
(11, 1, 2, 'lagi apa', '2020-06-26 09:51:06', 0),
(12, 1, 25, 'test', '2020-06-26 09:54:04', 0),
(13, 1, 25, 'p', '2020-06-26 09:58:55', 0),
(14, 1, 25, 'p', '2020-06-26 09:59:08', 0),
(15, 1, 25, 'p', '2020-06-26 09:59:14', 0),
(16, 1, 25, 'p', '2020-06-26 09:59:18', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model`
--

CREATE TABLE `model` (
  `idModel` int(11) NOT NULL,
  `userModel` int(11) NOT NULL,
  `jkModel` enum('l','p') NOT NULL DEFAULT 'l',
  `tglahModel` date NOT NULL,
  `tbModel` int(4) NOT NULL,
  `bbModel` int(4) NOT NULL,
  `usModel` int(4) NOT NULL,
  `jrModel` varchar(1) NOT NULL,
  `twModel` varchar(2) NOT NULL,
  `bsModel` varchar(2) NOT NULL,
  `wkModel` varchar(1) NOT NULL,
  `hijabModel` enum('y','t') NOT NULL DEFAULT 't'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `model`
--

INSERT INTO `model` (`idModel`, `userModel`, `jkModel`, `tglahModel`, `tbModel`, `bbModel`, `usModel`, `jrModel`, `twModel`, `bsModel`, `wkModel`, `hijabModel`) VALUES
(1, 2, 'l', '2010-12-02', 0, 0, 0, 'a', 're', 'i', 'c', 't'),
(10, 15, 'l', '2000-06-12', 184, 64, 28, 'i', '', '', 'k', ''),
(11, 19, 'p', '1999-05-29', 160, 43, 36, 'l', '', '', 'c', 't'),
(14, 21, 'l', '2020-06-16', 165, 55, 28, 'l', '', '', 'k', ''),
(15, 22, 'l', '2020-06-16', 167, 55, 28, 'l', '', '', 'h', 't'),
(16, 23, 'l', '2020-06-10', 122, 22, 23, 'k', '', '', 'k', 't'),
(17, 24, 'l', '2020-06-16', 122, 22, 21, 'a', '', '', 'k', 't'),
(18, 25, 'l', '2002-01-04', 56, 12, 12, 'a', 's', 'r', 'h', 't'),
(19, 26, 'p', '2012-06-16', 80, 20, 20, '-', 'o', 'a', 'k', 'y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `idUsers` int(11) NOT NULL,
  `namaUsers` varchar(300) NOT NULL,
  `mailUsers` varchar(300) NOT NULL,
  `passUsers` varchar(300) NOT NULL,
  `typeUsers` enum('company','model') NOT NULL DEFAULT 'company',
  `bioUsers` text NOT NULL,
  `fotoUsers` varchar(100) NOT NULL DEFAULT 'noimage.jpg',
  `tokenUsers` int(5) NOT NULL,
  `onlineUsers` int(1) NOT NULL,
  `createUsers` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updateUsers` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`idUsers`, `namaUsers`, `mailUsers`, `passUsers`, `typeUsers`, `bioUsers`, `fotoUsers`, `tokenUsers`, `onlineUsers`, `createUsers`, `updateUsers`) VALUES
(1, 'farida', 'farida', 'farida', 'company', 'my skripsi ðŸ˜ŠðŸ˜ŠðŸ˜ŠðŸ˜ŠðŸ˜Š\npower by react-native\nâœ¨âœ¨âœ¨ Modeling âœ¨âœ¨âœ¨', '1_111477.jpg', 3329, 0, '2020-06-26 09:53:00', '2020-06-09 21:03:57'),
(2, 'rino', 'rino', 'rino', 'model', 'testerssssssðŸŒˆðŸŒˆðŸŒˆðŸŒˆðŸŒˆ\n\nasdasdasd', '2_52360.jpg', 1411, 1, '2020-06-26 08:54:20', '2020-06-09 21:03:57'),
(19, 'Farida', 'Frdfarida29@gmail.com', '29051999', 'model', '', 'noimage.jpg', 2344, 0, '2020-06-16 04:29:15', '2020-06-16 04:25:17'),
(15, 'Rino2', 'Rino2', 'Rino2', 'model', '', 'noimage.jpg', 0, 0, '2020-06-12 15:01:05', '2020-06-12 15:01:05'),
(26, 'rina', 'rina', 'rina', 'model', '', 'noimage.jpg', 3929, 0, '2020-06-16 06:13:54', '2020-06-16 06:13:20'),
(25, 'ahlul', 'ahlul', 'ahlul', 'model', 'Love Koceng OyenðŸ˜˜ðŸ˜˜ðŸ˜˜ðŸ˜˜ðŸ˜˜', '25_126204.jpg', 1605, 0, '2020-06-21 04:41:08', '2020-06-16 05:50:55');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idEvent`);

--
-- Indeks untuk tabel `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`);

--
-- Indeks untuk tabel `model`
--
ALTER TABLE `model`
  ADD PRIMARY KEY (`idModel`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUsers`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `event`
--
ALTER TABLE `event`
  MODIFY `idEvent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `model`
--
ALTER TABLE `model`
  MODIFY `idModel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `idUsers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
