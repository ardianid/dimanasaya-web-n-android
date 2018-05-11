-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 30. Oktober 2013 jam 11:14
-- Versi Server: 5.5.8
-- Versi PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `salesmonitoring`
--
CREATE DATABASE `salesmonitoring` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `salesmonitoring`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ms_golongan1`
--

CREATE TABLE IF NOT EXISTS `ms_golongan1` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ms_golongan1`
--

INSERT INTO `ms_golongan1` (`kode`, `nama`) VALUES
('001', 'Pusat'),
('002', 'Kotabumi'),
('003', 'Tulang Bawang'),
('004', 'Baturaja');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ms_golongan2`
--

CREATE TABLE IF NOT EXISTS `ms_golongan2` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ms_golongan2`
--

INSERT INTO `ms_golongan2` (`kode`, `nama`) VALUES
('ASM', 'ASM'),
('KDA', 'KDA'),
('SALES', 'SALES'),
('SESKO', 'SESKO'),
('SM', 'SM'),
('SUPERVISOR', 'SUPERVISOR');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ms_pengguna`
--

CREATE TABLE IF NOT EXISTS `ms_pengguna` (
  `nama_pengguna` varchar(50) NOT NULL,
  `notelp` varchar(13) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `kd_perusahaan` varchar(15) NOT NULL,
  `aktif` int(11) NOT NULL,
  `kdgol1` varchar(10) NOT NULL,
  `kdgol2` varchar(10) NOT NULL,
  `jnismonitor` varchar(5) NOT NULL,
  `jarak` int(11) NOT NULL,
  `waktu` decimal(11,1) NOT NULL,
  `ceksms` int(11) NOT NULL DEFAULT '0',
  `cektelp` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `nama_pengguna` (`nama_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ms_pengguna`
--

INSERT INTO `ms_pengguna` (`nama_pengguna`, `notelp`, `notes`, `kd_perusahaan`, `aktif`, `kdgol1`, `kdgol2`, `jnismonitor`, `jarak`, `waktu`, `ceksms`, `cektelp`) VALUES
('bukit', '0868455361', '', '', 1, '002', 'SALES', 'Waktu', 0, 5.0, 0, 0),
('dian', '+628135517553', '', '', 1, '002', 'KDA', 'Waktu', 0, 0.1, 1, 1),
('fahri', '08257559835', '', '', 1, '001', 'SALES', 'Jarak', 20, 0.0, 0, 0),
('jason', '085355475568', '', '', 1, '001', 'SESKO', 'Waktu', 0, 15.0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ms_perusahaan`
--

CREATE TABLE IF NOT EXISTS `ms_perusahaan` (
  `kd_perusahaan` varchar(15) NOT NULL,
  `nama_perusahaan` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telp` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`kd_perusahaan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ms_perusahaan`
--


-- --------------------------------------------------------

--
-- Struktur dari tabel `ms_usersys`
--

CREATE TABLE IF NOT EXISTS `ms_usersys` (
  `nama_user` varchar(50) NOT NULL,
  `kd_perusahaan` varchar(15) NOT NULL,
  `password` text NOT NULL,
  UNIQUE KEY `nama_user` (`nama_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ms_usersys`
--

INSERT INTO `ms_usersys` (`nama_user`, `kd_perusahaan`, `password`) VALUES
('admin', 'fl', '152505e98776959b217ba8a672b785ac');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_aktifitas`
--

CREATE TABLE IF NOT EXISTS `tr_aktifitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notujuan` varchar(15) NOT NULL,
  `jam` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data untuk tabel `tr_aktifitas`
--


-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_lokasi`
--

CREATE TABLE IF NOT EXISTS `tr_lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(50) NOT NULL,
  `longi` varchar(50) NOT NULL,
  `loti` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `alamat` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=228 ;

--
-- Dumping data untuk tabel `tr_lokasi`
--

