/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50528
Source Host           : localhost:3306
Source Database       : ecatalog

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2022-11-20 18:39:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cl_kategori
-- ----------------------------
DROP TABLE IF EXISTS `cl_kategori`;
CREATE TABLE `cl_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `flag` varchar(2) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_by` varchar(255) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cl_kategori
-- ----------------------------
INSERT INTO `cl_kategori` VALUES ('1', null, 'Alat Bantu', 'Alat Bantu', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('2', null, 'Alat Uji', 'Alat Uji', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('3', null, 'Alat Ukur', 'Alat Ukur', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('4', '1', 'Lemari Asam', 'Lemari Asam', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('5', '2', 'Gravimetri', 'Gravimetri', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('6', '2', 'Kimia Fisik Lainnya', 'Kimia Fisik Lainnya', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('7', '2', 'Kromatografi', 'Kromatografi', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('8', '2', 'Spektroskopi', 'Spektroskopi', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('9', '2', 'Spektroskopi Atom', 'Spektroskopi Atom', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('10', '2', 'Titrimetri', 'Titrimetri', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('11', '3', 'Derajat Keasaman', 'Derajat Keasaman', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('12', '3', 'Kelembaban', 'Kelembaban', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('13', '3', 'Massa', 'Massa', null, null, null, null, null);
INSERT INTO `cl_kategori` VALUES ('14', '3', 'Panjang', 'Panjang', null, null, null, null, null);

-- ----------------------------
-- Table structure for tbl_master_barang
-- ----------------------------
DROP TABLE IF EXISTS `tbl_master_barang`;
CREATE TABLE `tbl_master_barang` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cl_reseller_id` int(11) DEFAULT NULL,
  `cl_kat_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `masa_berlaku` varchar(255) DEFAULT NULL,
  `merek` varchar(255) DEFAULT NULL,
  `no_produk` varchar(255) DEFAULT NULL,
  `unit_pengukuran` varchar(255) DEFAULT NULL,
  `nama_pemilik_setifikat` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `ukuran_dimensi` varchar(255) DEFAULT NULL,
  `sni` varchar(255) DEFAULT NULL,
  `keterangan_lain` varchar(255) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_by` varchar(255) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_master_barang
-- ----------------------------
