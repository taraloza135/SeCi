-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table secure_ci3.4dm1n
CREATE TABLE IF NOT EXISTS `4dm1n` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `status` enum('1','0') DEFAULT '1',
  PRIMARY KEY (`id_user`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table secure_ci3.4dm1n: 1 rows
/*!40000 ALTER TABLE `4dm1n` DISABLE KEYS */;
INSERT INTO `4dm1n` (`id_user`, `username`, `email`, `password`, `salt`, `status`) VALUES
	(1, 'techd', 'hardik@techdefence.com,taral@techdefence.com', 'b2398c3463d54e058c4a80b197a0f4389b81f3d4', '51532ff7bfc0077cab3322a35ad69749bb8b654c', '1');
/*!40000 ALTER TABLE `4dm1n` ENABLE KEYS */;


-- Dumping structure for table secure_ci3.customer_api_uids
CREATE TABLE IF NOT EXISTS `customer_api_uids` (
  `id_customer_api_uids` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` int(10) unsigned DEFAULT NULL,
  `uid` varchar(10) DEFAULT NULL,
  `accesstoken` varchar(20) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id_customer_api_uids`),
  KEY `id_customer` (`id_customer`),
  KEY `uid` (`uid`),
  KEY `token` (`accesstoken`),
  CONSTRAINT `FK_customer_api_uids_customer` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table secure_ci3.customer_api_uids: ~2 rows (approximately)
/*!40000 ALTER TABLE `customer_api_uids` DISABLE KEYS */;
INSERT INTO `customer_api_uids` (`id_customer_api_uids`, `id_customer`, `uid`, `accesstoken`, `datetime`) VALUES
	(1, 1, '39f139d0c4', '26828058c1598b7e9096', '2015-06-27 11:26:20'),
	(2, 16, '5fe5399129', '373aa27d36ee5d8b67ea', '2015-06-25 12:49:21');
/*!40000 ALTER TABLE `customer_api_uids` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
