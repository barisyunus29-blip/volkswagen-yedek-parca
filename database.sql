CREATE DATABASE IF NOT EXISTS `volkswagenyp` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `volkswagenyp`;

CREATE TABLE IF NOT EXISTS `stok` (
  `Stok_Kodu` varchar(50) NOT NULL,
  `Marka` varchar(50) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `Yil` varchar(10) DEFAULT NULL,
  `Aciklama` text DEFAULT NULL,
  `Fiyat` decimal(10,2) DEFAULT NULL,
  `fotograf` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Stok_Kodu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Örnek admin kullanıcısı (Kullanıcı adı: admin, Şifre: 123456)
-- Not: Gerçek kullanımda şifreler hash'lenmelidir.
INSERT INTO `admin` (`username`, `password`) VALUES ('admin', '123456');
