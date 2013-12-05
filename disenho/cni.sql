-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2013 at 02:35 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cni`
--
CREATE DATABASE IF NOT EXISTS `cni` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cni`;

-- --------------------------------------------------------

--
-- Table structure for table `agencia`
--

CREATE TABLE IF NOT EXISTS `agencia` (
  `an` int(11) NOT NULL AUTO_INCREMENT,
  `aagencia` char(50) DEFAULT NULL,
  PRIMARY KEY (`an`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `agenciaoperador`
--

CREATE TABLE IF NOT EXISTS `agenciaoperador` (
  `aon` int(11) NOT NULL AUTO_INCREMENT,
  `aoagencia` int(11) NOT NULL,
  `aooperador` int(11) NOT NULL,
  PRIMARY KEY (`aon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `almacen`
--

CREATE TABLE IF NOT EXISTS `almacen` (
  `aln` int(11) NOT NULL AUTO_INCREMENT,
  `alalmacen` char(50) DEFAULT NULL,
  PRIMARY KEY (`aln`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asociado`
--

CREATE TABLE IF NOT EXISTS `asociado` (
  `asasociado` int(11) NOT NULL,
  `asrazonsocial` char(100) DEFAULT NULL,
  `asrotulo` char(100) DEFAULT NULL,
  `asmailprincipal` char(50) DEFAULT NULL,
  `asmailssecundarios` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bulto`
--

CREATE TABLE IF NOT EXISTS `bulto` (
  `bn` int(11) NOT NULL AUTO_INCREMENT,
  `bbulto` char(50) DEFAULT NULL,
  `btipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`bn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `camion`
--

CREATE TABLE IF NOT EXISTS `camion` (
  `can` int(11) NOT NULL AUTO_INCREMENT,
  `caplaca` char(10) DEFAULT NULL,
  `catransportista` int(11) DEFAULT NULL,
  PRIMARY KEY (`can`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `consignatario`
--

CREATE TABLE IF NOT EXISTS `consignatario` (
  `cn` int(11) NOT NULL AUTO_INCREMENT,
  `cconsignatario` char(50) DEFAULT NULL,
  PRIMARY KEY (`cn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contenedor`
--

CREATE TABLE IF NOT EXISTS `contenedor` (
  `con` int(11) NOT NULL AUTO_INCREMENT,
  `cocontenedor` char(20) DEFAULT NULL,
  `cotipo` int(11) NOT NULL,
  `copeso` float DEFAULT NULL,
  PRIMARY KEY (`con`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `imo`
--

CREATE TABLE IF NOT EXISTS `imo` (
  `imon` int(11) NOT NULL AUTO_INCREMENT,
  `imoimo` char(5) DEFAULT NULL,
  PRIMARY KEY (`imon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `itn` int(11) NOT NULL AUTO_INCREMENT,
  `ititem` char(50) DEFAULT NULL,
  PRIMARY KEY (`itn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lugar`
--

CREATE TABLE IF NOT EXISTS `lugar` (
  `ln` int(11) NOT NULL AUTO_INCREMENT,
  `lalmacen` int(11) DEFAULT NULL,
  `llugar` char(50) DEFAULT NULL,
  PRIMARY KEY (`ln`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `manifiesto`
--

CREATE TABLE IF NOT EXISTS `manifiesto` (
  `mn` int(11) NOT NULL AUTO_INCREMENT,
  `mitiem` int(11) NOT NULL,
  `magenciaoperador` int(11) NOT NULL,
  `mnave` int(11) NOT NULL,
  `mviaje` char(10) DEFAULT NULL,
  `mnromfto` char(10) DEFAULT NULL,
  `mtipotransito` char(50) DEFAULT NULL,
  `mcontenedor` int(11) NOT NULL,
  `mbl` char(20) DEFAULT NULL,
  `mpuertoembarque` int(11) NOT NULL,
  `mpuertodescarga` int(11) NOT NULL,
  `mpuertodestino` int(11) NOT NULL,
  `mmercancia` int(11) NOT NULL,
  `mneto` float DEFAULT NULL,
  `mbruto` float DEFAULT NULL,
  `mservicio` int(11) NOT NULL,
  `mimo` int(11) NOT NULL,
  `msellos` char(20) DEFAULT NULL,
  `mbultos` int(11) DEFAULT NULL,
  `mconsignatario` int(11) NOT NULL,
  `mestado` char(20) DEFAULT NULL,
  `mperiodo` char(10) DEFAULT NULL,
  `mfecha` datetime NOT NULL,
  PRIMARY KEY (`mn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mercancia`
--

CREATE TABLE IF NOT EXISTS `mercancia` (
  `mn` int(11) NOT NULL AUTO_INCREMENT,
  `mmercancia` char(100) DEFAULT NULL,
  PRIMARY KEY (`mn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nave`
--

CREATE TABLE IF NOT EXISTS `nave` (
  `nn` int(11) NOT NULL AUTO_INCREMENT,
  `nnave` char(20) DEFAULT NULL,
  `noperador` int(11) NOT NULL,
  PRIMARY KEY (`nn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `operador`
--

CREATE TABLE IF NOT EXISTS `operador` (
  `opn` int(11) NOT NULL AUTO_INCREMENT,
  `opoperador` char(50) DEFAULT NULL,
  PRIMARY KEY (`opn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `planificacion`
--

CREATE TABLE IF NOT EXISTS `planificacion` (
  `pln` int(11) NOT NULL AUTO_INCREMENT,
  `plturno` int(11) DEFAULT NULL,
  `pllugar` int(11) DEFAULT NULL,
  `plcontenedor` int(11) DEFAULT NULL,
  `plcamion` int(11) DEFAULT NULL,
  `plmatriz` char(50) DEFAULT NULL,
  `plcantidad` char(10) DEFAULT NULL,
  `plpeso` char(10) DEFAULT NULL,
  `plbulto` int(11) DEFAULT NULL,
  `plbaroti` char(50) DEFAULT NULL,
  `pldestino` int(11) DEFAULT NULL,
  `plfecha` datetime DEFAULT NULL,
  PRIMARY KEY (`pln`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `puerto`
--

CREATE TABLE IF NOT EXISTS `puerto` (
  `pn` int(11) NOT NULL AUTO_INCREMENT,
  `ppuerto` char(50) DEFAULT NULL,
  PRIMARY KEY (`pn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `servicio`
--

CREATE TABLE IF NOT EXISTS `servicio` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `sservicio` char(10) DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbulto`
--

CREATE TABLE IF NOT EXISTS `tbulto` (
  `tbn` int(11) NOT NULL AUTO_INCREMENT,
  `tbtipo` char(100) DEFAULT NULL,
  PRIMARY KEY (`tbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tcontenedor`
--

CREATE TABLE IF NOT EXISTS `tcontenedor` (
  `tcn` int(11) NOT NULL AUTO_INCREMENT,
  `tctipo` char(20) DEFAULT NULL,
  PRIMARY KEY (`tcn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transportista`
--

CREATE TABLE IF NOT EXISTS `transportista` (
  `transn` int(11) NOT NULL AUTO_INCREMENT,
  `transtransportista` char(50) DEFAULT NULL,
  PRIMARY KEY (`transn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `tn` int(11) NOT NULL AUTO_INCREMENT,
  `tturno` char(20) DEFAULT NULL,
  PRIMARY KEY (`tn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
