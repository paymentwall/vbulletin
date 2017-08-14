-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

--
-- Make sure you replace the database name
-- with the database name that your vBulletin
-- installation is using. Change the name
-- `dbvbulletin` to your database's name.
--

USE dbvbulletin;

--
-- Also make sure that you replace the default
-- table prefixes with what you are actually
-- using. Change `vb_` to what you are
-- using for your tables.
--

--
-- Dumping data for table `vb_paymentapi`
--

/*!40000 ALTER TABLE `vb_paymentapi` DISABLE KEYS */;
INSERT INTO `vb_paymentapi` (`title`,`currency`,`recurring`,`classname`,`active`,`settings`) VALUES 
 ('Paymentwall','usd,gbp,eur,aud,cad',0,'paymentwall',1,'a:3:{s:7:\"app_key\";a:3:{s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"f5dd6f7564468512dc0914d5547fa340\";s:8:\"validate\";s:6:\"string\";}s:10:\"secret_key\";a:3:{s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"32144b351aeaa9a183a3369a2bda0720\";s:8:\"validate\";s:6:\"string\";}s:11:\"widget_code\";a:3:{s:4:\"type\";s:4:\"text\";s:5:\"value\";s:4:\"p1_1\";s:8:\"validate\";s:6:\"string\";}}');
/*!40000 ALTER TABLE `vb_paymentapi` ENABLE KEYS */;

--
-- Dumping data for table `vb_phrase`
--

/*!40000 ALTER TABLE `vb_phrase` DISABLE KEYS */;
INSERT INTO `vb_phrase` (`phraseid`,`languageid`,`varname`,`fieldname`,`text`,`product`,`username`,`dateline`,`version`) VALUES 

 (NULL,-1,'setting_paymentwall_app_key_desc','subscription','This is your unique application key. You can get your application key in Applications > Application Settings of your Paymentwall Merchant Area.','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'setting_paymentwall_app_key_title','subscription','Paymentwall Application Key','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'setting_paymentwall_widget_code_desc','subscription','Your Paymentwall widget code','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'setting_paymentwall_secret_key_title','subscription','Paymentwall Secret Key','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'setting_paymentwall_widget_code_title','subscription','Paymentwall Widget Code','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'setting_paymentwall_secret_key_desc','subscription','Your Paymentwall secret key. This key is used to sign your widget and pingback requests to prevent attacks to your website.','vbulletin','admin',401947932,'3.8.7'),
 (NULL,-1,'paymentwall_order_instructions','subscription','Pay using <a href=\"http://www.paymentwall.com\" target=\"_blank\">Paymentwall</a>, the easiest way to purchase virtual currency and digital goods online.','vbulletin','admin',401947932,'3.8.7');
/*!40000 ALTER TABLE `vb_phrase` ENABLE KEYS */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
