-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 13. April 2017 jam 09:20
-- Versi Server: 5.1.36
-- Versi PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vslrep`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbleop`
--

CREATE TABLE IF NOT EXISTS `tbleop` (
  `ideop` smallint(5) unsigned zerofill NOT NULL,
  `refno` varchar(50) NOT NULL DEFAULT '0',
  `serialnumber` varchar(51) NOT NULL DEFAULT '0',
  `namakapal` varchar(51) NOT NULL DEFAULT '0',
  `dateeop` date NOT NULL,
  `houreop` varchar(5) NOT NULL,
  `nameport` varchar(250) NOT NULL,
  `arrivaltimes` date NOT NULL,
  `hourarrtimes` varchar(5) NOT NULL,
  `nortime` date NOT NULL,
  `hournortime` varchar(5) NOT NULL,
  `arrdraft` varchar(250) NOT NULL,
  `droppedanchor` varchar(250) NOT NULL,
  `position` varchar(250) NOT NULL,
  `fwe` varchar(250) NOT NULL,
  `robeop` varchar(250) NOT NULL,
  `mfoeop` varchar(250) NOT NULL,
  `mdoeop` varchar(250) NOT NULL,
  `mecyleop` varchar(250) NOT NULL,
  `mesyseop` varchar(250) NOT NULL,
  `aeloeop` varchar(250) NOT NULL,
  `sumptkeop` varchar(250) NOT NULL,
  `fweop` varchar(250) NOT NULL,
  `totaldist` varchar(250) NOT NULL,
  `totaltime` varchar(250) NOT NULL,
  `avspd` varchar(250) NOT NULL,
  `totalmfo` varchar(250) NOT NULL,
  `avmfo` varchar(250) NOT NULL,
  `totalmdo` varchar(250) NOT NULL,
  `avmdo` varchar(250) NOT NULL,
  `avrpm` varchar(250) NOT NULL,
  `avweather` varchar(250) NOT NULL,
  `robfwe` varchar(250) NOT NULL,
  `mfofwe` varchar(250) NOT NULL,
  `mdofwe` varchar(250) NOT NULL,
  `mecyllofwe` varchar(250) NOT NULL,
  `mesyslofwe` varchar(250) NOT NULL,
  `aelofwe` varchar(250) NOT NULL,
  `sumptkfwe` varchar(250) NOT NULL,
  `fwfwe` varchar(250) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  `lastreceive` varchar(25) NOT NULL,
  `addusrdt` varchar(25) NOT NULL,
  `updusrdt` varchar(25) NOT NULL,
  `delusrdt` varchar(25) NOT NULL,
  `deletests` tinyint(1) NOT NULL DEFAULT '0',
  `guid` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbleop`
--

INSERT INTO `tbleop` (`ideop`, `refno`, `serialnumber`, `namakapal`, `dateeop`, `houreop`, `nameport`, `arrivaltimes`, `hourarrtimes`, `nortime`, `hournortime`, `arrdraft`, `droppedanchor`, `position`, `fwe`, `robeop`, `mfoeop`, `mdoeop`, `mecyleop`, `mesyseop`, `aeloeop`, `sumptkeop`, `fweop`, `totaldist`, `totaltime`, `avspd`, `totalmfo`, `avmfo`, `totalmdo`, `avmdo`, `avrpm`, `avweather`, `robfwe`, `mfofwe`, `mdofwe`, `mecyllofwe`, `mesyslofwe`, `aelofwe`, `sumptkfwe`, `fwfwe`, `remarks`, `lastreceive`, `addusrdt`, `updusrdt`, `delusrdt`, `deletests`, `guid`) VALUES
(00001, '1/EOP/2017', 'B814-1675', 'ANDHIKA NARESWARI', '2017-02-06', '11.11', 'M. PANTAI', '2017-02-07', '22.22', '2017-02-08', '12.21', ' F 3.78 MTR / M 5.10 MTR / A 6.58 MTR', '09.01.2017/ 14.00 HRS', '02 00.68 N - 118 09.06 E', '09.01.2017/ 14.18 HRS', '09.01.2017/ 14.18 HRS', '252.57 MT', '22.25 MT', '4129 LTRS', '5415 LTRS', '1665 LTRS', '9673 LTRS', '245 MT', '245 MT', '111.6 HRS', '111.6 HRS', '111.6 HRS', '24.7 MT/ DAY', '0.2 MT', '0.13 MT/ DAY', '87.4', 'SLIGHT SEA', '09.01.2017 / 14.18 HRS LT', '251.72 MT', '22.25 MT', '4118 LTRS', '4118 LTRS', '1665 LTRS', '9666 LTRS', '245 MT', 'NIL', '2017-04-13 16:02:00', '00001/20170209/10:01:03', '', '', 0, 'db10ca47-2027-11e7-916f-46a155c4d9f3'),
(00002, '2/EOP/2017', 'B814-1675', 'ANDHIKA NARESWARI', '2017-02-04', '09.01', 'PORT EOP NARESWARI 2', '2017-02-05', '08.02', '2017-02-06', '07.30', 'ARR Draft', 'Dropped Anchor', 'Position', 'FWE', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2017-04-13 15:59:24', '00001/20170210/16:11:54', '', '', 0, '7dfbf33a-2027-11e7-916f-46a155c4d9f3'),
(00003, '3/EOP/2017', 'B814-1675', 'ANDHIKA NARESWARI', '0000-00-00', '', 'PORT EOP NARESWARI 3', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2017-04-13 16:01:45', '00001/20170217/09:56:31', '', '', 0, 'd22a39af-2027-11e7-916f-46a155c4d9f3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbleop_del`
--

CREATE TABLE IF NOT EXISTS `tbleop_del` (
  `ideop` smallint(5) unsigned zerofill NOT NULL,
  `refno` varchar(50) NOT NULL DEFAULT '0',
  `serialnumber` varchar(51) NOT NULL DEFAULT '0',
  `namakapal` varchar(51) NOT NULL DEFAULT '0',
  `dateeop` date NOT NULL,
  `houreop` varchar(5) NOT NULL,
  `nameport` varchar(250) NOT NULL,
  `arrivaltimes` date NOT NULL,
  `hourarrtimes` varchar(5) NOT NULL,
  `nortime` date NOT NULL,
  `hournortime` varchar(5) NOT NULL,
  `arrdraft` varchar(250) NOT NULL,
  `droppedanchor` varchar(250) NOT NULL,
  `position` varchar(250) NOT NULL,
  `fwe` varchar(250) NOT NULL,
  `robeop` varchar(250) NOT NULL,
  `mfoeop` varchar(250) NOT NULL,
  `mdoeop` varchar(250) NOT NULL,
  `mecyleop` varchar(250) NOT NULL,
  `mesyseop` varchar(250) NOT NULL,
  `aeloeop` varchar(250) NOT NULL,
  `sumptkeop` varchar(250) NOT NULL,
  `fweop` varchar(250) NOT NULL,
  `totaldist` varchar(250) NOT NULL,
  `totaltime` varchar(250) NOT NULL,
  `avspd` varchar(250) NOT NULL,
  `totalmfo` varchar(250) NOT NULL,
  `avmfo` varchar(250) NOT NULL,
  `totalmdo` varchar(250) NOT NULL,
  `avmdo` varchar(250) NOT NULL,
  `avrpm` varchar(250) NOT NULL,
  `avweather` varchar(250) NOT NULL,
  `robfwe` varchar(250) NOT NULL,
  `mfofwe` varchar(250) NOT NULL,
  `mdofwe` varchar(250) NOT NULL,
  `mecyllofwe` varchar(250) NOT NULL,
  `mesyslofwe` varchar(250) NOT NULL,
  `aelofwe` varchar(250) NOT NULL,
  `sumptkfwe` varchar(250) NOT NULL,
  `fwfwe` varchar(250) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  `lastreceive` varchar(25) NOT NULL,
  `addusrdt` varchar(25) NOT NULL,
  `updusrdt` varchar(25) NOT NULL,
  `delusrdt` varchar(25) NOT NULL,
  `deletests` tinyint(1) NOT NULL DEFAULT '0',
  `guid` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbleop_del`
--


-- --------------------------------------------------------

--
-- Struktur dari tabel `tbluserjenis`
--

CREATE TABLE IF NOT EXISTS `tbluserjenis` (
  `userid` int(5) unsigned zerofill NOT NULL,
  `username` varchar(512) NOT NULL,
  `userjenis` varchar(512) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbluserjenis`
--

INSERT INTO `tbluserjenis` (`userid`, `username`, `userjenis`) VALUES
(00002, 'hendra', 'admin'),
(00001, 'arifan', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblvessel`
--

CREATE TABLE IF NOT EXISTS `tblvessel` (
  `idvsl` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nmvsl` char(50) NOT NULL,
  `vsltype` char(50) NOT NULL,
  `year` char(4) NOT NULL,
  `hp` int(4) NOT NULL,
  `dwt` int(4) NOT NULL,
  `grt` int(4) NOT NULL,
  `remark` char(50) NOT NULL,
  `deletests` tinyint(1) NOT NULL,
  `addusrdt` varchar(25) NOT NULL,
  `updusrdt` varchar(25) NOT NULL,
  `delusrdt` varchar(25) NOT NULL,
  PRIMARY KEY (`idvsl`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data untuk tabel `tblvessel`
--

INSERT INTO `tblvessel` (`idvsl`, `nmvsl`, `vsltype`, `year`, `hp`, `dwt`, `grt`, `remark`, `deletests`, `addusrdt`, `updusrdt`, `delusrdt`) VALUES
(001, 'ANDHIKA SHARMILA', 'BULK CARRIER', '1986', 8678, 64975, 35609, '', 0, '', '', ''),
(002, 'JOHN CAINE', 'OIL CARRIER', '1992', 7200, 18056, 12317, '', 0, '', '', ''),
(003, 'ANDHIKA ARSANTI', 'OIL CARRIER', '1997', 6100, 18518, 13258, '', 0, '', '', ''),
(004, 'BULK CELEBES', 'SPB', '2006', 2720, 11000, 10325, '', 0, '', '', ''),
(005, 'ANDHIKA LARASATI', 'TANKER', '1990', 19690, 149817, 81194, '', 0, '', '', ''),
(006, 'ANDHIKA NARESWARI', 'BULK CARIER', '1996', 0, 0, 38232, '', 0, '', '', ''),
(007, 'ANDHIKA PARAMESTI', 'BULK CARRIER', '1996', 0, 0, 0, '', 0, '', '', ''),
(008, 'ANDHIKA  KANISHKA', 'BULK CARRIER', '1997', 11400, 0, 38489, '', 0, '', '', '');
